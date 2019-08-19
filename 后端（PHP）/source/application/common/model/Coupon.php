<?php

namespace app\common\model;

/**
 * 优惠券模型
 * Class Coupon
 * @package app\common\model
 */
class Coupon extends BaseModel
{
    protected $name = 'coupon';

    /**
     * 追加字段
     * @var array
     */
    protected $append = [
        'state'
    ];

    /**
     * 优惠券状态 (是否可领取)
     * @param $value
     * @param $data
     * @return array
     */
    public function getStateAttr($value, $data)
    {
        if (isset($data['is_receive']) && $data['is_receive']) {
            return ['text' => '已领取', 'value' => 0];
        }
        if ($data['total_num'] > -1 && $data['receive_num'] >= $data['total_num']) {
            return ['text' => '已抢光', 'value' => 0];
        }
        if ($data['expire_type'] == 20 && ($data['end_time'] + 86400) < time()) {
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
        return self::get($coupon_id);
    }

}
