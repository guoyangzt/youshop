<?php

namespace app\common\model\sharing;

use app\common\model\BaseModel;

/**
 * 拼团拼单成员模型
 * Class ActiveUsers
 * @package app\common\model\sharing
 */
class ActiveUsers extends BaseModel
{
    protected $name = 'sharing_active_users';
    protected $updateTime = false;

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
     * 关联拼团订单表
     * @return \think\model\relation\BelongsTo
     */
    public function sharingOrder()
    {
        return $this->belongsTo('Order', 'order_id');
    }

    /**
     * 新增拼团拼单成员记录
     * @param $data
     * @return false|int
     */
    public static function add($data)
    {
        return (new static)->save($data);
    }

}
