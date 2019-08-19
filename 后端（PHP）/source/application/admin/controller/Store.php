<?php

namespace app\admin\controller;

use app\admin\model\Wxapp as WxappModel;
use app\admin\model\store\User as StoreUser;

/**
 * 小程序商城管理
 * Class Store
 * @package app\admin\controller
 */
class Store extends Controller
{
    /**
     * 小程序列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new WxappModel;
        return $this->fetch('index', [
            'list' => $list = $model->getList(),
            'names' => $model->getStoreName($list)
        ]);
    }

    /**
     * 进入商城
     * @param $wxapp_id
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function enter($wxapp_id)
    {
        $model = new StoreUser;
        $model->login($wxapp_id);
        $this->redirect('store/index/index');
    }

    /**
     * 回收站列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function recycle()
    {
        $model = new WxappModel;
        return $this->fetch('recycle', [
            'list' => $list = $model->getList(true),
            'names' => $model->getStoreName($list)
        ]);
    }

    /**
     * 添加小程序
     * @return array|mixed
     * @throws \think\exception\PDOException
     */
    public function add()
    {
        $model = new WxappModel;
        if (!$this->request->isAjax()) {
            return $this->fetch('add');
        }
        // 新增记录
        if ($model->add($this->postData('store'))) {
            return $this->renderSuccess('添加成功', url('store/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 回收小程序
     * @param $wxapp_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function recovery($wxapp_id)
    {
        // 商品详情
        $model = WxappModel::detail($wxapp_id);
        if (!$model->recycle()) {
            return $this->renderError('操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /**
     * 移出回收站
     * @param $wxapp_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function move($wxapp_id)
    {
        // 商品详情
        $model = WxappModel::detail($wxapp_id);
        if (!$model->recycle(false)) {
            return $this->renderError('操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /**
     * 删除小程序
     * @param $wxapp_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($wxapp_id)
    {
        // 商品详情
        $model = WxappModel::detail($wxapp_id);
        if (!$model->setDelete()) {
            return $this->renderError('操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

}