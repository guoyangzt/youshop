<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\Express as ExpressModel;
use app\api\model\OrderGoods as OrderGoodsModel;
use app\api\model\OrderRefund as OrderRefundModel;

/**
 * 订单售后服务
 * Class service
 * @package app\api\controller\user\order
 */
class Refund extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();   // 用户信息
    }

    /**
     * 用户售后单列表
     * @param int $state
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($state = -1)
    {
        $model = new OrderRefundModel;
        $list = $model->getList($this->user['user_id'], (int)$state);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 申请售后
     * @param $order_goods_id
     * @return array
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function apply($order_goods_id)
    {
        // 订单商品详情
        $goods = OrderGoodsModel::detail($order_goods_id);
        if (isset($goods['refund']) && !empty($goods['refund'])) {
            return $this->renderError('当前商品已申请售后');
        }
        if (!$this->request->isPost()) {
            return $this->renderSuccess(['detail' => $goods]);
        }
        // 新增售后单记录
        $model = new OrderRefundModel;
        if ($model->apply($this->user, $goods, $this->request->post())) {
            return $this->renderSuccess([], '提交成功');
        }
        return $this->renderError($model->getError() ?: '提交失败');
    }

    /**
     * 售后单详情
     * @param $order_refund_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function detail($order_refund_id)
    {
        // 售后单详情
        $detail = OrderRefundModel::detail([
            'user_id' => $this->user['user_id'],
            'order_refund_id' => $order_refund_id
        ]);
        if (empty($detail)) {
            return $this->renderError('售后单不存在');
        }
        // 物流公司列表
        $expressList = ExpressModel::getAll();
        return $this->renderSuccess(compact('detail', 'expressList'));
    }

    /**
     * 用户发货
     * @param $order_refund_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delivery($order_refund_id)
    {
        // 售后单详情
        $model = OrderRefundModel::detail([
            'user_id' => $this->user['user_id'],
            'order_refund_id' => $order_refund_id
        ]);
        if ($model->delivery($this->postData())) {
            return $this->renderSuccess([], '操作成功');
        }
        return $this->renderError($model->getError() ?: '提交失败');
    }

}