<?php

namespace app\common\model\recharge;

use app\common\model\BaseModel;
use app\common\enum\recharge\order\RechargeType as RechargeTypeEnum;
use app\common\enum\recharge\order\PayStatus as PayStatusTypeEnum;

/**
 * 用户充值订单模型
 * Class Order
 * @package app\common\model\recharge
 */
class Order extends BaseModel
{
    protected $name = 'recharge_order';

    /**
     * 获取当前模型属性
     * @return array
     */
    public static function getAttributes()
    {
        return [
            // 充值方式
            'rechargeType' => RechargeTypeEnum::data(),
            // 支付状态
            'pay_status' => PayStatusTypeEnum::data(),
        ];
    }

    /**
     * 关联会员记录表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('app\common\model\User');
    }

    /**
     * 关联订单套餐快照表
     * @return \think\model\relation\HasOne
     */
    public function orderPlan()
    {
        return $this->hasOne('OrderPlan', 'order_id');
    }

    /**
     * 付款状态
     * @param $value
     * @return array
     */
    public function getRechargeTypeAttr($value)
    {
        return ['text' => RechargeTypeEnum::data()[$value]['name'], 'value' => $value];
    }

    /**
     * 付款状态
     * @param $value
     * @return array
     */
    public function getPayStatusAttr($value)
    {
        return ['text' => PayStatusTypeEnum::data()[$value]['name'], 'value' => $value];
    }

    /**
     * 付款时间
     * @param $value
     * @return array
     */
    public function getPayTimeAttr($value)
    {
        return [
            'text' => $value > 0 ? date('Y-m-d H:i:s', $value) : '',
            'value' => $value
        ];
    }

    /**
     * 获取订单详情
     * @param $where
     * @return Order|null
     * @throws \think\exception\DbException
     */
    public static function detail($where)
    {
        return static::get($where);
    }

}