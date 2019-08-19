<?php

namespace app\common\model\dealer;

use think\Hook;
use app\common\model\BaseModel;
use app\common\enum\OrderType as OrderTypeEnum;

/**
 * 分销商订单模型
 * Class Apply
 * @package app\common\model\dealer
 */
class Order extends BaseModel
{
    protected $name = 'dealer_order';

    /**
     * 订单模型初始化
     */
    public static function init()
    {
        parent::init();
        // 监听分销商订单行为管理
        $static = new static;
        Hook::listen('DealerOrder', $static);
    }

    /**
     * 订单所属用户
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('app\common\model\User');
    }

    /**
     * 一级分销商用户
     * @return \think\model\relation\BelongsTo
     */
    public function dealerFirst()
    {
        return $this->belongsTo('User', 'first_user_id');
    }

    /**
     * 二级分销商用户
     * @return \think\model\relation\BelongsTo
     */
    public function dealerSecond()
    {
        return $this->belongsTo('User', 'second_user_id');
    }

    /**
     * 三级分销商用户
     * @return \think\model\relation\BelongsTo
     */
    public function dealerThird()
    {
        return $this->belongsTo('User', 'third_user_id');
    }

    /**
     * 订单类型
     * @param $value
     * @return array
     */
    public function getOrderTypeAttr($value)
    {
        $types = OrderTypeEnum::getTypeName();
        return ['text' => $types[$value], 'value' => $value];
    }

    /**
     * 订单详情
     * @param $where
     * @return Order|null
     * @throws \think\exception\DbException
     */
    public static function detail($where)
    {
        return static::get($where);
    }

    /**
     * 发放分销订单佣金
     * @param array|\think\Model $order 订单详情
     * @param int $orderType 订单类型
     * @return bool|false|int
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public static function grantMoney(&$order, $orderType = OrderTypeEnum::MASTER)
    {
        // 订单是否已完成
        if ($order['order_status']['value'] != 30) {
            return false;
        }
        // 佣金结算天数
        $settleDays = Setting::getItem('settlement', $order['wxapp_id'])['settle_days'];
        // 判断该订单是否满足结算时间 (订单完成时间 + 佣金结算时间) ≤ 当前时间
        $deadlineTime = $order['receipt_time'] + ((int)$settleDays * 86400);
        if ($settleDays > 0 && $deadlineTime > time()) {
            return false;
        }
        // 分销订单详情
        $model = self::detail(['order_id' => $order['order_id'], 'order_type' => $orderType]);
        if (!$model || $model['is_settled'] == 1) {
            return false;
        }
        // 重新计算分销佣金
        $capital = $model->getCapitalByOrder($order);
        // 发放一级分销商佣金
        $model['first_user_id'] > 0 && User::grantMoney($model['first_user_id'], $capital['first_money']);
        // 发放二级分销商佣金
        $model['second_user_id'] > 0 && User::grantMoney($model['second_user_id'], $capital['second_money']);
        // 发放三级分销商佣金
        $model['third_user_id'] > 0 && User::grantMoney($model['third_user_id'], $capital['third_money']);
        // 更新分销订单记录
        return $model->save([
            'order_price' => $capital['orderPrice'],
            'first_money' => $capital['first_money'],
            'second_money' => $capital['second_money'],
            'third_money' => $capital['third_money'],
            'is_settled' => 1,
            'settle_time' => time()
        ]);
    }

    /**
     * 计算订单分销佣金
     * @param $order
     * @return array
     */
    protected function getCapitalByOrder(&$order)
    {
        // 分销佣金设置
        $setting = Setting::getItem('commission', $order['wxapp_id']);
        // 分销层级
        $level = Setting::getItem('basic', $order['wxapp_id'])['level'];
        // 分销订单佣金数据
        $capital = [
            // 订单总金额(不含运费)
            'orderPrice' => bcsub($order['pay_price'], $order['express_price'], 2),
            // 一级分销佣金
            'first_money' => 0.00,
            // 二级分销佣金
            'second_money' => 0.00,
            // 三级分销佣金
            'third_money' => 0.00
        ];
        // 计算分销佣金
        foreach ($order['goods'] as $goods) {
            // 判断商品存在售后退款则不计算佣金
            if ($this->checkGoodsRefund($goods)) {
                continue;
            }
            // 商品实付款金额
            $goodsPrice = min($capital['orderPrice'], $goods['total_pay_price']);
            // 计算商品实际佣金
            $goodsCapital = $this->calculateGoodsCapital($setting, $goods, $goodsPrice);
            // 累积分销佣金
            $level >= 1 && $capital['first_money'] += $goodsCapital['first_money'];
            $level >= 2 && $capital['second_money'] += $goodsCapital['second_money'];
            $level == 3 && $capital['third_money'] += $goodsCapital['third_money'];
        }
        return $capital;
    }

    /**
     * 计算商品实际佣金
     * @param $setting
     * @param $goods
     * @param $goodsPrice
     * @return array
     */
    private function calculateGoodsCapital($setting, $goods, $goodsPrice)
    {
        // 判断是否开启商品单独分销
        if ($goods['is_ind_dealer'] == false) {
            // 全局分销比例
            return [
                'first_money' => $goodsPrice * ($setting['first_money'] * 0.01),
                'second_money' => $goodsPrice * ($setting['second_money'] * 0.01),
                'third_money' => $goodsPrice * ($setting['third_money'] * 0.01)
            ];
        }
        // 商品单独分销
        if ($goods['dealer_money_type'] == 10) {
            // 分销佣金类型：百分比
            return [
                'first_money' => $goodsPrice * ($goods['first_money'] * 0.01),
                'second_money' => $goodsPrice * ($goods['second_money'] * 0.01),
                'third_money' => $goodsPrice * ($goods['third_money'] * 0.01)
            ];
        } else {
            return [
                'first_money' => $goods['total_num'] * $goods['first_money'],
                
                'second_money' => $goods['total_num'] * $goods['second_money'],
                'third_money' => $goods['total_num'] * $goods['third_money']
            ];
        }
    }

    /**
     * 验证商品是否存在售后
     * @param $goods
     * @return bool
     */
    private function checkGoodsRefund(&$goods)
    {
        return !empty($goods['refund'])
            && $goods['refund']['type']['value'] == 10
            && $goods['refund']['is_agree']['value'] != 20;
    }

}
