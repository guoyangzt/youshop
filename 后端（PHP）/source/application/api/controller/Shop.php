<?php

namespace app\api\controller;

use app\api\model\store\Shop as ShopModel;


/**
 * 门店列表
 * Class Shop
 * @package app\api\controller
 */
class Shop extends Controller
{
    /**
     * 门店列表
     * @param string $longitude
     * @param string $latitude
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($longitude = '', $latitude = '')
    {
        $model = new ShopModel;
        $list = $model->getList(true, $longitude, $latitude);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 门店详情
     * @param $shop_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function detail($shop_id)
    {
        $detail = ShopModel::detail($shop_id);
        return $this->renderSuccess(compact('detail'));
    }

}