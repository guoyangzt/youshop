<?php

namespace app\store\controller\shop;

use app\store\controller\Controller;
use app\store\model\store\Shop as ShopModel;
use app\store\model\store\shop\Order as OrderModel;

/**
 * 订单核销记录
 * Class Order
 * @package app\store\controller\shop
 */
class Order extends Controller
{
    /**
     * 订单核销记录列表
     * @param int $shop_id
     * @param string $search
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index($shop_id = 0, $search = '')
    {
        // 核销记录列表
        $model = new OrderModel;
        $list = $model->getList($shop_id, $search);
        // 门店列表
        $shopList = (new ShopModel)->getList();
        return $this->fetch('index', compact('list', 'shopList'));
    }

}