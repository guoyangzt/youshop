<?php

namespace app\api\controller;

use app\api\model\Coupon as CouponModel;

/**
 * 优惠券中心
 * Class Coupon
 * @package app\api\controller
 */
class Coupon extends Controller
{
    /**
     * 优惠券列表
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists()
    {
        $model = new CouponModel;
        $list = $model->getList($this->getUser(false));
        return $this->renderSuccess(compact('list'));
    }

}