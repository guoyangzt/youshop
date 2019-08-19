<?php

namespace app\common\model\sharing;

use app\common\model\BaseModel;

/**
 * 拼团商品评价模型
 * Class Comment
 * @package app\common\model\sharing
 */
class Comment extends BaseModel
{
    protected $name = 'sharing_comment';

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
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\User");
    }

    /**
     * 关联评价图片表
     * @return \think\model\relation\HasMany
     */
    public function image()
    {
        return $this->hasMany('CommentImage', 'comment_id')->order(['id' => 'asc']);
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

}