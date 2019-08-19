<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\UserCoupon as UserCouponModel;

/**
 * 用户优惠券
 * Class Coupon
 * @package app\api\controller
 */
class Coupon extends Controller
{
    /* @var UserCouponModel $model */
    private $model;

    /* @var \app\api\model\User $model */
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new UserCouponModel;
        $this->user = $this->getUser();
    }

    /**
     * 优惠券列表
     * @param string $data_type
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists($data_type = 'all')
    {
        $is_use = false;
        $is_expire = false;
        switch ($data_type) {
            case 'not_use':
                $is_use = false;
                break;
            case 'is_use':
                $is_use = true;
                break;
            case 'is_expire':
                $is_expire = true;
                break;
        }
        $list = $this->model->getList($this->user['user_id'], $is_use, $is_expire);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 领取优惠券
     * @param $coupon_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function receive($coupon_id)
    {
        if ($this->model->receive($this->user, $coupon_id)) {
            return $this->renderSuccess([], '领取成功');
        }
        return $this->renderError($this->model->getError() ?: '添加失败');
    }

}