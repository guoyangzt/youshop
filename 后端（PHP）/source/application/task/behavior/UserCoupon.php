<?php

namespace app\task\behavior;

use think\Cache;
use app\task\model\UserCoupon as UserCouponModel;

/**
 * 优惠券行为管理
 * Class UserCoupon
 * @package app\task\behavior
 */
class UserCoupon
{
    /* @var \app\task\model\UserCoupon $model */
    private $model;

    /**
     * 执行函数
     * @param $model
     * @return bool
     */
    public function run($model)
    {
        if (!$model instanceof UserCouponModel) {
            return new UserCouponModel and false;
        }
        $this->model = $model;
        if (!Cache::has('__task_space__UserCoupon')) {
            // 设置优惠券过期状态
            $this->setExpired();
            Cache::set('__task_space__UserCoupon', time(), 3600);
        }
        return true;
    }

    /**
     * 设置优惠券过期状态
     * @return false|int
     */
    private function setExpired()
    {
        // 获取已过期的优惠券ID集
        $couponIds = $this->model->getExpiredCouponIds();
        // 记录日志
        $this->dologs('setExpired', [
            'couponIds' => json_encode($couponIds),
        ]);
        // 更新已过期状态
        return $this->model->setIsExpire($couponIds);
    }

    /**
     * 记录日志
     * @param $method
     * @param array $params
     * @return bool|int
     */
    private function dologs($method, $params = [])
    {
        $value = 'UserCoupon --' . $method;
        foreach ($params as $key => $val)
            $value .= ' --' . $key . ' ' . $val;
        return log_write($value);
    }

}
