<?php

namespace app\store\controller\content;

use app\store\controller\Controller;
use app\store\model\UploadFile as UploadFileModel;

/**
 * 文件库管理
 * Class Files
 * @package app\store\controller
 */
class Files extends Controller
{
    /**
     * 文件列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new UploadFileModel;
        $list = $model->getList(-1, '', 0);
        return $this->fetch('index', compact('list'));
    }

    /**
     * 回收站列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function recycle()
    {
        $model = new UploadFileModel;
        $list = $model->getList(-1, '', 1);
        return $this->fetch('recycle', compact('list'));
    }

    /**
     * 移入回收站
     * @param $file_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function recovery($file_id)
    {
        // 文章详情
        $model = UploadFileModel::detail($file_id);
        if (!$model->setRecycle(true)) {
            return $this->renderError($model->getError() ?: '操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /**
     * 移出回收站
     * @param $file_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function restore($file_id)
    {
        // 商品详情
        $model = UploadFileModel::detail($file_id);
        if (!$model->setRecycle(false)) {
            return $this->renderError('操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /**
     * 删除文件
     * @param $file_id
     * @return array|bool
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function delete($file_id)
    {
        // 商品详情
        $model = UploadFileModel::detail($file_id);
        if (!$model->setDelete()) {
            return $this->renderError($model->getError() ?: '操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

}