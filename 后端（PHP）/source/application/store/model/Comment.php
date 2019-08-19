<?php

namespace app\store\model;

use app\common\model\Comment as CommentModel;

/**
 * 商品评价模型
 * Class Comment
 * @package app\store\model
 */
class Comment extends CommentModel
{
    /**
     * 软删除
     * @return false|int
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }

    /**
     * 获取评价总数量
     * @return int|string
     */
    public function getCommentTotal()
    {
        return $this->where(['is_delete' => 0])->count();
    }

}