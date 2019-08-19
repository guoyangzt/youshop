<?php

namespace app\api\model;

use app\common\model\WxappCategory as WxappCategoryModel;

/**
 * 微信小程序分类页模板
 * Class WxappCategory
 * @package app\api\model
 */
class WxappCategory extends WxappCategoryModel
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

}