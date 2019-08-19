<?php

namespace app\common\model;

use think\Db;

/**
 * 商品评价模型
 * Class Comment
 * @package app\common\model
 */
class Comment extends BaseModel
{
    protected $name = 'comment';

    /**
     * 所属订单
     * @return \think\model\relation\BelongsTo
     */
    public function orderM()
    {
        return $this->belongsTo('Order');
    }

    /**
     * 订单商品
     * @return \think\model\relation\BelongsTo
     */
    public function OrderGoods()
    {
        return $this->belongsTo('OrderGoods');
    }

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }

    /**
     * 关联评价图片表
     * @return \think\model\relation\HasMany
     */
    public function image()
    {
        return $this->hasMany('CommentImage')->order(['id' => 'asc']);
    }

    /**
     * 评价详情
     * @param $comment_id
     * @return Comment|null
     * @throws \think\exception\DbException
     */
    public static function detail($comment_id)
    {
        return self::get($comment_id, ['user', 'orderM', 'OrderGoods', 'image.file']);
    }

    /**
     * 更新记录
     * @param $data
     * @return bool
     */
    public function edit($data)
    {
        return $this->transaction(function () use ($data) {
            // 删除评价图片
            $this->image()->delete();
            // 添加评论图片
            isset($data['images']) && $this->addCommentImages($data['images']);
            // 是否为图片评价
            $data['is_picture'] = !$this->image()->select()->isEmpty();
            // 更新评论记录
            return $this->allowField(true)->save($data);
        });
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