<?php

namespace app\store\controller\apps\dealer;

use app\store\controller\Controller;
use app\store\model\dealer\Order as OrderModel;

/**
 * 分销订单
 * Class Order
 * @package app\store\controller\apps\dealer
 */
class Order extends Controller
{
    /**
     * 分销订单列表
     * @param null $user_id
     * @param int $is_settled
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index($user_id = null, $is_settled = -1)
    {
        $model = new OrderModel;
        $list = $model->getList($user_id, $is_settled);
        return $this->fetch('index', compact('list'));
    }

}