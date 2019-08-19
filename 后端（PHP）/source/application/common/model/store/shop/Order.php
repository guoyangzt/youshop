<?php

namespace app\common\model\store\shop;

use app\common\model\BaseModel;
use app\common\enum\OrderType as OrderTypeEnum;

/**
 * 商家门店核销订单记录模型
 * Class Clerk
 * @package app\common\model\store
 */
class Order extends BaseModel
{
    protected $name = 'store_shop_order';
    protected $updateTime = false;

    /**
     * 关联门店表
     * @return \think\model\relation\BelongsTo
     */
    public function shop()
    {
        $module = static::getCalledModule() ?: 'common';
        return $this->BelongsTo("app\\{$module}\\model\\store\\Shop");
    }

    /**
     * 关联店员表
     * @return \think\model\relation\BelongsTo
     */
    public function clerk()
    {
        $module = static::getCalledModule() ?: 'common';
        return $this->BelongsTo("app\\{$module}\\model\\store\\shop\\Clerk");
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
     * 新增核销记录
     * @param int $order_id 订单id
     * @param int $shop_id 门店id
     * @param int $clerk_id 核销员id
     * @param int $order_type
     * @return mixed
     */
    public static function add(
        $order_id,
        $shop_id,
        $clerk_id,
        $order_type = OrderTypeEnum::MASTER
    )
    {
        return (new static)->save([
            'order_id' => $order_id,
            'order_type' => $order_type,
            'shop_id' => $shop_id,
            'clerk_id' => $clerk_id,
            'wxapp_id' => static::$wxapp_id
        ]);
    }

}