<?php

namespace app\api\model\sharing;

use app\common\model\sharing\ActiveUsers as ActiveUsersModel;

/**
 * 拼团拼单成员模型
 * Class ActiveUsers
 * @package app\api\model\sharing
 */
class ActiveUsers extends ActiveUsersModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'create_time',
    ];

}
