<?php

namespace app\store\controller\apps\sharing\order;

use app\store\controller\Controller;
use app\store\model\sharing\Order as OrderModel;
use app\store\model\Express as ExpressModel;

/**
 * 拼团订单操作控制器
 * Class Operate
 * @package app\store\controller\order
 */
class Operate extends Controller
{
    /* @var OrderModel $model */
    private $model;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new OrderModel;
    }

    /**
     * 订单导出
     * @param string $dataType
     * @throws \think\exception\DbException
     */
    public function export($dataType)
    {
        return $this->model->exportList($dataType, $this->request->param());
    }

    /**
     * 批量发货
     * @return array|bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function batchDelivery()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('batchDelivery', [
                'express_list' => ExpressModel::getAll()
            ]);
        }
        if ($this->model->batchDelivery($this->postData('order'))) {
            return $this->renderSuccess('发货成功');
        }
        return $this->renderError($this->model->getError() ?: '发货失败');
    }

//    /**
//     * 批量发货模板
//     */
//    public function deliveryTpl()
//    {
//        return $this->model->deliveryTpl();
//    }

    /**
     * 审核：用户取消订单
     * @param $order_id
     * @return array|bool
     * @throws \think\exception\DbException
     */
    public function confirmCancel($order_id)
    {
        $model = OrderModel::detail($order_id);
        if ($model->confirmCancel($this->postData('order'))) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 拼团失败手动退款
     * @param $order_id
     * @return array|bool
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function refund($order_id)
    {
        $model = OrderModel::detail($order_id);
        if ($model->refund()) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 门店自提核销
     * @param $order_id
     * @return array|bool
     * @throws \think\exception\DbException
     */
    public function extract($order_id)
    {
        $model = OrderModel::detail($order_id);
        $data = $this->postData('order');
        if ($model->verificationOrder($data['extract_clerk_id'])) {
            return $this->renderSuccess('核销成功');
        }
        return $this->renderError($model->getError() ?: '核销失败');
    }

}
