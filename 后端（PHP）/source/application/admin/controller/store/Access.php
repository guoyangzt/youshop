<?php

namespace app\admin\controller\store;

use app\admin\controller\Controller;
use app\admin\model\store\Access as AccesscModel;

/**
 * 商家用户权限控制器
 * Class StoreUser
 * @package app\store\controller
 */
class Access extends Controller
{
    /**
     * 权限列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new AccesscModel;
        $list = $model->getList();
        return $this->fetch('index', compact('list'));
    }

    /**
     * 添加权限
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add()
    {
        $model = new AccesscModel;
        if (!$this->request->isAjax()) {
            // 权限列表
            $accessList = $model->getList();
            return $this->fetch('add', compact('accessList'));
        }
        // 新增记录
        if ($model->add($this->postData('access'))) {
            return $this->renderSuccess('添加成功', url('store.access/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 更新权限
     * @param $access_id
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit($access_id)
    {
        // 权限详情
        $model = AccesscModel::detail($access_id);
        if (!$this->request->isAjax()) {
            // 权限列表
            $accessList = $model->getList();
            return $this->fetch('edit', compact('model', 'accessList'));
        }
        // 更新记录
        if ($model->edit($this->postData('access'))) {
            return $this->renderSuccess('更新成功', url('store.access/index'));
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除权限
     * @param $access_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($access_id)
    {
        // 权限详情
        $model = AccesscModel::detail($access_id);
        if (!$model->remove()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
