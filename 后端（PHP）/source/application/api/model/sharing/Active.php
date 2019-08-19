<?php

namespace app\api\model\sharing;

use app\common\exception\BaseException;
use app\common\model\sharing\Active as ActiveModel;

/**
 * 拼团拼单模型
 * Class Active
 * @package app\api\model\sharing
 */
class Active extends ActiveModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'create_time',
        'update_time'
    ];

    /**
     * 新增拼单记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        return $this->save($data);
    }

    /**
     * 根据商品id获取进行中的拼单列表
     * @param $goods_id
     * @param int $limit
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function getActivityListByGoods($goods_id, $limit = 15)
    {
        return (new static)->with(['user'])
            ->where('goods_id', '=', $goods_id)
            ->where('status', '=', 10)
            ->limit($limit)
            ->select();
    }

}
