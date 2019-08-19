<?php

namespace app\api\model\sharing;

use app\common\model\sharing\OrderGoods as OrderGoodsModel;

/**
 * 拼团订单商品模型
 * Class OrderGoods
 * @package app\api\model
 */
class OrderGoods extends OrderGoodsModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'content',
        'wxapp_id',
        'create_time',
    ];

    /**
     * 获取未评价的商品
     * @param $order_id
     * @return OrderGoods[]|false
     * @throws \think\exception\DbException
     */
    public static function getNotCommentGoodsList($order_id)
    {
        return self::all(['order_id' => $order_id, 'is_comment' => 0], ['orderM', 'image']);
    }

}
