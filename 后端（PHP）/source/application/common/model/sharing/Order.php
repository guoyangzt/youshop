<?php

namespace app\common\model\sharing;

use think\Hook;
use app\common\model\BaseModel;
use app\common\model\store\shop\Order as ShopOrder;
use app\common\service\Order as OrderService;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\DeliveryType as DeliveryTypeEnum;
use app\common\enum\order\PayType as PayTypeEnum;
use app\common\enum\order\PayStatus as PayStatusEnum;
use app\common\library\helper;

/**
 * 拼团订单模型
 * Class Order
 * @package app\common\model
 */
class Order extends BaseModel
{
    protected $name = 'sharing_order';

    /**
     * 追加字段
     * @var array
     */
    protected $append = [
        'state_text',   // 售后单状态文字描述
    ];

    /**
     * 订单模型初始化
     */
    public static function init()
    {
        parent::init();
        // 监听订单处理事件
        $static = new static;
        Hook::listen('sharing_order', $static);
    }

    /**
     * 关联拼单表
     * @return \think\model\relation\BelongsTo
     */
    public function active()
    {
        return $this->belongsTo('Active');
    }

    /**
     * 订单商品列表
     * @return \think\model\relation\HasMany
     */
    public function goods()
    {
        return $this->hasMany('OrderGoods', 'order_id');
    }

    /**
     * 关联订单收货地址表
     * @return \think\model\relation\HasOne
     */
    public function address()
    {
        return $this->hasOne('OrderAddress', 'order_id');
    }

    /**
     * 关联订单收货地址表
     * @return \think\model\relation\HasOne
     */
    public function extract()
    {
        return $this->hasOne('OrderExtract', 'order_id');
    }

    /**
     * 关联自提门店表
     * @return \think\model\relation\BelongsTo
     */
    public function extractShop()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\store\\Shop", 'extract_shop_id');
    }

    /**
     * 关联门店店员表
     * @return \think\model\relation\BelongsTo
     */
    public function extractClerk()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\store\\shop\\Clerk", 'extract_clerk_id');
    }

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\User");
    }

    /**
     * 关联物流公司表
     * @return \think\model\relation\BelongsTo
     */
    public function express()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\Express");
    }

    /**
     * 拼团订单状态文字描述
     * @param $value
     * @param $data
     * @return string
     */
    public function getStateTextAttr($value, $data)
    {
        if (!isset($data['active_status'])) {
            $data['active_status'] = '';
        }
        // 订单状态：已完成
        if ($data['order_status'] == 30) {
            return '已完成';
        }
        // 订单状态：已取消
        if ($data['order_status'] == 20) {
            // 拼单未成功
            if ($data['order_type'] == 20 && $data['active_status'] == 30) {
                return $data['is_refund'] ? '拼团未成功，已退款' : '拼团未成功，待退款';
            }
            return '已取消';
        }
        // 付款状态
        if ($data['pay_status'] == 10) {
            return '待付款';
        }
        // 订单类型：单独购买
        if ($data['order_type'] == 10) {
            if ($data['delivery_status'] == 10) {
                return '已付款，待发货';
            }
            if ($data['receipt_status'] == 10) {
                return '已发货，待收货';
            }
        }
        // 订单类型：拼团
        if ($data['order_type'] == 20) {
            // 拼单未成功
            if ($data['active_status'] == 30) {
                return $data['is_refund'] ? '拼团未成功，已退款' : '拼团未成功，待退款';
            }
            // 拼单中
            if ($data['active_status'] == 10) {
                return '已付款，待成团';
            }
            // 拼单成功
            if ($data['active_status'] == 20) {
                if ($data['delivery_status'] == 10) {
                    return '拼团成功，待发货';
                }
                if ($data['receipt_status'] == 10) {
                    return '已发货，待收货';
                }
            }
        }
        return $value;
    }

    /**
     * 获取器：拼单状态
     * @param $value
     * @return array|bool
     */
    public function getActiveStatusAttr($value)
    {
        if (is_null($value)) {
            return false;
        }
        $state = [
            0 => '未拼单',
            10 => '拼单中',
            20 => '拼单成功',
            30 => '拼单失败',
        ];
        return ['text' => $state[$value], 'value' => $value];
    }

    /**
     * 获取器：订单金额(含优惠折扣)
     * @param $value
     * @param $data
     * @return string
     */
    public function getOrderPriceAttr($value, $data)
    {
        // 兼容旧数据：订单金额
        if ($value == 0) {
            return helper::bcadd(helper::bcsub($data['total_price'], $data['coupon_money']), $data['update_price']);
        }
        return $value;
    }

    /**
     * 改价金额（差价）
     * @param $value
     * @return array
     */
    public function getUpdatePriceAttr($value)
    {
        return [
            'symbol' => $value < 0 ? '-' : '+',
            'value' => sprintf('%.2f', abs($value))
        ];
    }

    /**
     * 订单类型
     * @param $value
     * @return array
     */
    public function getOrderTypeAttr($value)
    {
        $status = [10 => '单独购买', 20 => '拼团'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 付款状态
     * @param $value
     * @return array
     */
    public function getPayTypeAttr($value)
    {
        return ['text' => PayTypeEnum::data()[$value]['name'], 'value' => $value];
    }

    /**
     * 付款状态
     * @param $value
     * @return array
     */
    public function getPayStatusAttr($value)
    {
        return ['text' => PayStatusEnum::data()[$value]['name'], 'value' => $value];
    }

    /**
     * 发货状态
     * @param $value
     * @return array
     */
    public function getDeliveryStatusAttr($value)
    {
        $status = [10 => '待发货', 20 => '已发货'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 收货状态
     * @param $value
     * @return array
     */
    public function getReceiptStatusAttr($value)
    {
        $status = [10 => '待收货', 20 => '已收货'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 收货状态
     * @param $value
     * @return array
     */
    public function getOrderStatusAttr($value)
    {
        $status = [10 => '进行中', 20 => '已取消', 21 => '待取消', 30 => '已完成', 40 => '拼团失败'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 配送方式
     * @param $value
     * @return array
     */
    public function getDeliveryTypeAttr($value)
    {
        return ['text' => DeliveryTypeEnum::data()[$value]['name'], 'value' => $value];
    }

    /**
     * 生成订单号
     * @return string
     */
    protected function orderNo()
    {
        return OrderService::createOrderNo();
    }

    /**
     * 订单详情
     * @param int|array $where
     * @param array $with
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($where, $with = [
        'active',
        'goods' => ['image'],
        'address',
        'extract',
        'express',
        'extract_shop.logo',
        'extract_clerk'
    ])
    {
        is_array($where) ? $filter = $where : $filter['order_id'] = (int)$where;
        return self::get($filter, $with);
    }

    /**
     * 批量获取订单列表
     * @param $orderIds
     * @param array $with 关联查询
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getListByIds($orderIds, $with = [])
    {
        $data = $this->getListByInArray('order_id', $orderIds, $with);
        return helper::arrayColumn2Key($data, 'order_id');
    }

    /**
     * 批量获取订单列表
     * @param string $field
     * @param array $data
     * @param array $with
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getListByInArray($field, $data, $with = [])
    {
        return $this->with($with)
            ->alias('order')
            ->field('order.*, active.status as active_status')
            ->join('sharing_active active', 'order.active_id = active.active_id', 'LEFT')
            ->where($field, 'in', $data)
            ->where('order.is_delete', '=', 0)
            ->select();
    }

    /**
     * 根据订单号批量查询
     * @param $orderNos
     * @param array $with
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getListByOrderNos($orderNos, $with = [])
    {
        return $this->getListByInArray('order_no', $orderNos, $with);
    }

    /**
     * 确认核销（自提订单）
     * @param int $clerkId 核销员id
     * @return bool|false|int
     */
    public function verificationOrder($clerkId)
    {
        if (
            $this['pay_status']['value'] != 20
            || $this['delivery_type']['value'] != DeliveryTypeEnum::EXTRACT
            || $this['delivery_status']['value'] == 20
            || in_array($this['order_status']['value'], [20, 21])
            // 拼团订单验证拼单状态
            || ($this['order_type']['value'] == 20 ? $this['active']['status']['value'] != 20 : false)
        ) {
            $this->error = '该订单不满足核销条件';
            return false;
        }
        $this->transaction(function () use ($clerkId) {
            // 更新订单状态：已发货、已收货
            $this->save([
                'extract_clerk_id' => $clerkId,  // 核销员
                'delivery_status' => 20,
                'delivery_time' => time(),
                'receipt_status' => 20,
                'receipt_time' => time(),
                'order_status' => 30
            ]);
            // 新增订单核销记录
            ShopOrder::add(
                $this['order_id'],
                $this['extract_shop_id'],
                $this['extract_clerk_id'],
                OrderTypeEnum::SHARING
            );
        });
        return true;
    }

}
