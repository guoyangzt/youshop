<?php

namespace app\store\controller\setting;

use app\store\controller\Controller;
use app\store\model\Express as ExpressModel;

/**
 * 物流公司
 * Class Express
 * @package app\store\controller\setting
 */
class Express extends Controller
{
    /**
     * 物流公司列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new ExpressModel;
        $list = $model->getList();
        return $this->fetch('index', compact('list'));
    }

    /**
     * 删除物流公司
     * @param $express_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($express_id)
    {
        $model = ExpressModel::detail($express_id);
        if (!$model->remove()) {
            $error = $model->getError() ?: '删除失败';
            return $this->renderError($error);
        }
        return $this->renderSuccess('删除成功');
    }

    /**
     * 添加物流公司
     * @return array|mixed
     */
    public function add()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('add');
        }
        // 新增记录
        $model = new ExpressModel;
        if ($model->add($this->postData('express'))) {
            return $this->renderSuccess('添加成功', url('setting.express/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑物流公司
     * @param $express_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($express_id)
    {
        // 模板详情
        $model = ExpressModel::detail($express_id);
        if (!$this->request->isAjax()) {
            return $this->fetch('edit', compact('model'));
        }
        // 更新记录
        if ($model->edit($this->postData('express'))) {
            return $this->renderSuccess('更新成功', url('setting.express/index'));
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 物流公司编码表
     * @return mixed
     */
    public function company()
    {
        return $this->fetch('company');
    }

}