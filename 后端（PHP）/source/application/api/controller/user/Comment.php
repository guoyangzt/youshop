<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\Order as OrderModel;
use app\api\model\OrderGoods as OrderGoodsModel;
use app\api\model\Comment as CommentModel;

/**
 * 订单评价管理
 * Class Comment
 * @package app\api\controller\user
 */
class Comment extends Controller
{
    /**
     * 待评价订单商品列表
     * @param $order_id
     * @return array
     * @throws \Exception
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function order($order_id)
    {
        // 用户信息
        $user = $this->getUser();
        // 订单信息
        $order = OrderModel::getUserOrderDetail($order_id, $user['user_id']);
        // 验证订单是否已完成
        $model = new CommentModel;
        if (!$model->checkOrderAllowComment($order)) {
            return $this->renderError($model->getError());
        }
        // 待评价商品列表
        /* @var \think\Collection $goodsList */
        $goodsList = OrderGoodsModel::getNotCommentGoodsList($order_id);
        if ($goodsList->isEmpty()) {
            return $this->renderError('该订单没有可评价的商品');
        }
        // 提交商品评价
        if ($this->request->isPost()) {
            $formData = $this->request->post('formData', '', null);
            if ($model->addForOrder($order, $goodsList, $formData)) {
                return $this->renderSuccess([], '评价发表成功');
            }
            return $this->renderError($model->getError() ?: '评价发表失败');
        }
        return $this->renderSuccess(compact('goodsList'));
    }

}