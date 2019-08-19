<?php

namespace app\api\model;

use app\common\model\Express as ExpressModel;

/**
 * 物流公司模型
 * Class Express
 * @package app\api\model
 */
class Express extends ExpressModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'express_code',
        'sort',
        'wxapp_id',
        'create_time',
        'update_time'
    ];

}