<?php

namespace app\store\controller\content\files;

use app\store\controller\Controller;
use app\store\model\UploadGroup as GroupModel;

/**
 * 文件分组
 * Class Group
 * @package app\store\controller\content
 */
class Group extends Controller
{
    /**
     * 文件分组列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new GroupModel;
        $list = $model->getList();
        return $this->fetch('index', compact('list'));
    }

    /**
     * 添加文件分组
     * @return array|mixed
     */
    public function add()
    {
        $model = new GroupModel;
        if (!$this->request->isAjax()) {
            return $this->fetch('add');
        }
        // 新增记录
        if ($model->add($this->postData('group'))) {
            return $this->renderSuccess('添加成功', url('content.files.group/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑文件分组
     * @param $group_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($group_id)
    {
        // 分组详情
        $model = GroupModel::detail($group_id);
        if (!$this->request->isAjax()) {
            return $this->fetch('edit', compact('model'));
        }
        // 更新记录
        if ($model->edit($this->postData('group'))) {
            return $this->renderSuccess('更新成功', url('content.files.group/index'));
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除文件分组
     * @param $group_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($group_id)
    {
        $model = GroupModel::detail($group_id);
        if (!$model->remove()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
