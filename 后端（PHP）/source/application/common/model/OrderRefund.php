<?php


namespace app\common\model;

/**
 * 售后单模型
 * Class OrderRefund
 * @package app\common\model\wxapp
 */
class OrderRefund extends BaseModel
{
    protected $name = 'order_refund';

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
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
        return $this->hasMany('OrderRefundImage');
    }

    /**
     * 关联物流公司表
     * @return \think\model\relation\BelongsTo
     */
    public function express()
    {
        return $this->belongsTo('Express');
    }

    /**
     * 关联用户表
     * @return \think\model\relation\HasOne
     */
    public function address()
    {
        return $this->hasOne('OrderRefundAddress');
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
        return static::get($where, ['order_master', 'image.file', 'order_goods.image', 'express', 'address']);
    }

}