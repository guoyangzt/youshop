<?php

namespace app\api\controller;

use app\api\model\Cart as CartModel;
use app\api\model\Order as OrderModel;

/**
 * 购物车管理
 * Class Cart
 * @package app\api\controller
 */
class Cart extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /* @var \app\api\model\Cart $model */
    private $model;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();
        $this->model = new CartModel($this->user);
    }

    /**
     * 购物车列表
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists()
    {
        // 请求参数
        $param = $this->request->param();
        $cartIds = isset($param['cart_ids']) ? $param['cart_ids'] : '';
        // 购物车商品列表
        $goodsList = $this->model->getList($cartIds);
        // 获取订单结算信息
        $order = (new OrderModel)->getCart($this->user, $param, $goodsList);
        return $this->renderSuccess($order);
    }

    /**
     * 加入购物车
     * @param int $goods_id 商品id
     * @param int $goods_num 商品数量
     * @param string $goods_sku_id 商品sku索引
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function add($goods_id, $goods_num, $goods_sku_id)
    {
        if (!$this->model->add($goods_id, $goods_num, $goods_sku_id)) {
            return $this->renderError($this->model->getError() ?: '加入购物车失败');
        }
        // 购物车商品总数量
        $totalNum = $this->model->getGoodsNum();
        return $this->renderSuccess(['cart_total_num' => $totalNum], '加入购物车成功');
    }

    /**
     * 减少购物车商品数量
     * @param $goods_id
     * @param $goods_sku_id
     * @return array
     */
    public function sub($goods_id, $goods_sku_id)
    {
        $this->model->sub($goods_id, $goods_sku_id);
        return $this->renderSuccess();
    }

    /**
     * 删除购物车中指定商品
     * @param $goods_sku_id (支持字符串ID集)
     * @return array
     */
    public function delete($goods_sku_id)
    {
        $this->model->delete($goods_sku_id);
        return $this->renderSuccess();
    }

}
