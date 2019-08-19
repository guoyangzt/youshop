<?php

namespace app\common\model;

use think\Hook;

/**
 * 用户优惠券模型
 * Class UserCoupon
 * @package app\common\model
 */
class UserCoupon extends BaseModel
{
    protected $name = 'user_coupon';

    /**
     * 追加字段
     * @var array
     */
    protected $append = ['state'];

    /**
     * 订单模型初始化
     */
    public static function init()
    {
        parent::init();
        // 监听优惠券处理事件
        $static = new static;
        Hook::listen('UserCoupon', $static);
    }

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }

    /**
     * 优惠券状态
     * @param $value
     * @param $data
     * @return array
     */
    public function getStateAttr($value, $data)
    {
        if ($data['is_use']) {
            return ['text' => '已使用', 'value' => 0];
        }
        if ($data['is_expire']) {
            return ['text' => '已过期', 'value' => 0];
        }
        return ['text' => '', 'value' => 1];
    }

    /**
     * 优惠券颜色
     * @param $value
     * @return mixed
     */
    public function getColorAttr($value)
    {
        $status = [10 => 'blue', 20 => 'red', 30 => 'violet', 40 => 'yellow'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 优惠券类型
     * @param $value
     * @return mixed
     */
    public function getCouponTypeAttr($value)
    {
        $status = [10 => '满减券', 20 => '折扣券'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 折扣率
     * @param $value
     * @return mixed
     */
    public function getDiscountAttr($value)
    {
        return $value / 10;
    }

    /**
     * 有效期-开始时间
     * @param $value
     * @return mixed
     */
    public function getStartTimeAttr($value)
    {
        return ['text' => date('Y/m/d', $value), 'value' => $value];
    }

    /**
     * 有效期-结束时间
     * @param $value
     * @return mixed
     */
    public function getEndTimeAttr($value)
    {
        return ['text' => date('Y/m/d', $value), 'value' => $value];
    }

    /**
     * 优惠券详情
     * @param $coupon_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($coupon_id)
    {
        return static::get($coupon_id);
    }

    /**
     * 设置优惠券使用状态
     * @param int $couponId 用户的优惠券id
     * @param bool $isUse 是否已使用
     * @return false|int
     */
    public static function setIsUse($couponId, $isUse = true)
    {
        return (new static)->save(['is_use' => (int)$isUse], ['user_coupon_id' => $couponId]);
    }

}