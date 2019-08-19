<?php

namespace app\store\model\sharing;

use app\common\model\sharing\Comment as CommentModel;

/**
 * 商品评价模型
 * Class Comment
 * @package app\store\model\sharing
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

    /**
     * 更新记录
     * @param $data
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function edit($data)
    {
        // 开启事务
        $this->startTrans();
        try {
            // 删除评价图片
            $this->image()->delete();
            // 添加评论图片
            isset($data['images']) && $this->addCommentImages($data['images']);
            // 是否为图片评价
            $data['is_picture'] = !$this->image()->select()->isEmpty();
            // 更新评论记录
            $this->allowField(true)->save($data);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 添加评论图片
     * @param $images
     * @return int
     */
    private function addCommentImages($images)
    {
        $data = array_map(function ($image_id) {
            return [
                'image_id' => $image_id,
                'wxapp_id' => self::$wxapp_id
            ];
        }, $images);
        return $this->image()->saveAll($data);
    }

    /**
     * 获取评价列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        return $this->with(['user', 'orderM', 'OrderGoods'])
            ->where('is_delete', '=', 0)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => request()->request()
            ]);
    }

}