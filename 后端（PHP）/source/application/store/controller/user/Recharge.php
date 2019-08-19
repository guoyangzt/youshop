<?php

namespace app\store\controller\user;

use app\store\controller\Controller;
use app\store\model\recharge\Order as OrderModel;

/**
 * 余额记录
 * Class Recharge
 * @package app\store\controller\user
 */
class Recharge extends Controller
{
    /**
     * 充值记录
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function order()
    {
        $model = new OrderModel;
        return $this->fetch('order', [
            // 充值记录列表
            'list' => $model->getList($this->request->param()),
            // 属性集
            'attributes' => $model::getAttributes(),
        ]);
    }

}