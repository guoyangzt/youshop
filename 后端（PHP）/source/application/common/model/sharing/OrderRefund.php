<?php


namespace app\common\model\sharing;

use app\common\model\BaseModel;

/**
 * 售后单模型
 * Class OrderRefund
 * @package app\common\model\sharing
 */
class OrderRefund extends BaseModel
{
    protected $name = 'sharing_order_refund';

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
     * 关联订单主表
     * @return \think\model\relation\BelongsTo
     */
    public function orderMaster()
    {
        return $this->belongsTo('Order');
    }

    /**
     * 关联订单商品表
     * @return \think\model\relation\BelongsTo
     */
    public function orderGoods()
    {
        return $this->belongsTo('OrderGoods');
    }

    /**
     * 关联图片记录表
     * @return \think\model\relation\HasMany
     */
    public function image()
    {
        return $this->hasMany('OrderRefundImage', 'order_refund_id');
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
     * 关联用户表
     * @return \think\model\relation\HasOne
     */
    public function address()
    {
        return $this->hasOne('OrderRefundAddress', 'order_refund_id');
    }

    /**
     * 售后类型
     * @param $value
     * @return array
     */
    public function getTypeAttr($value)
    {
        $status = [10 => '退货退款', 20 => '换货'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 商家是否同意售后
     * @param $value
     * @return array
     */
    public function getIsAgreeAttr($value)
    {
        $status = [0 => '待审核', 10 => '已同意', 20 => '已拒绝'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 售后单状态
     * @param $value
     * @return array
     */
    public function getStatusAttr($value)
    {
        $status = [0 => '进行中', 10 => '已拒绝', 20 => '已完成', 30 => '已取消'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 售后单详情
     * @param $where
     * @return static|null
     * @throws \think\exception\DbException
     */
    public static function detail($where)
    {
        return static::get($where, ['image.file', 'order_goods.image', 'express', 'address']);
    }

}