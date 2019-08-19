<?php

namespace app\store\controller\shop;

use app\store\controller\Controller;
use app\store\model\store\Shop as ShopModel;
use app\store\model\store\shop\Clerk as ClerkModel;

/**
 * 门店店员控制器
 * Class Clerk
 * @package app\store\controller\shop
 */
class Clerk extends Controller
{
    /**
     * 店员列表
     * @param int $shop_id
     * @param string $search
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index($shop_id = 0, $search = '')
    {
        // 店员列表
        $model = new ClerkModel;
        $list = $model->getList(-1, $shop_id, $search);
        // 门店列表
        $shopList = (new ShopModel)->getList();
        return $this->fetch('index', compact('list', 'shopList'));
    }

    /**
     * 添加店员
     * @return array|bool|mixed
     * @throws \Exception
     */
    public function add()
    {
        $model = new ClerkModel;
        if (!$this->request->isAjax()) {
            // 门店列表
            $shopList = (new ShopModel)->getList();
            return $this->fetch('add', compact('shopList'));
        }
        // 新增记录
        if ($model->add($this->postData('clerk'))) {
            return $this->renderSuccess('添加成功', url('shop.clerk/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑店员
     * @param $clerk_id
     * @return array|bool|mixed
     * @throws \think\exception\DbException
     */
    public function edit($clerk_id)
    {
        // 店员详情
        $model = ClerkModel::detail($clerk_id);
        if (!$this->request->isAjax()) {
            // 门店列表
            $shopList = (new ShopModel)->getList();
            return $this->fetch('edit', compact('model', 'shopList'));
        }
        // 新增记录
        if ($model->edit($this->postData('clerk'))) {
            return $this->renderSuccess('更新成功', url('shop.clerk/index'));
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除店员
     * @param $clerk_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($clerk_id)
    {
        // 店员详情
        $model = ClerkModel::detail($clerk_id);
        if (!$model->setDelete()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}