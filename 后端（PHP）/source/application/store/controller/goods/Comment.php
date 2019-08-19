<?php

namespace app\store\controller\goods;

use app\store\controller\Controller;
use app\store\model\Comment as CommentModel;

/**
 * 商品评价管理
 * Class Comment
 * @package app\store\controller\goods
 */
class Comment extends Controller
{
    /**
     * 评价列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new CommentModel;
        $list = $model->getList();
        return $this->fetch('index', compact('list'));
    }

    /**
     * 评价详情
     * @param $comment_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function detail($comment_id)
    {
        // 评价详情
        $model = CommentModel::detail($comment_id);
        if (!$this->request->isAjax()) {
            return $this->fetch('detail', compact('model'));
        }
        // 更新记录
        if ($model->edit($this->postData('comment'))) {
            return $this->renderSuccess('更新成功', url('goods.comment/index'));
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除评价
     * @param $comment_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($comment_id)
    {
        $model = CommentModel::get($comment_id);
        if (!$model->setDelete()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}