<?php

namespace app\api\model\sharing;

use app\common\model\sharing\Category as CategoryModel;

/**
 * 拼团商品分类模型
 * Class Category
 * @package app\common\model\sharing
 */
class Category extends CategoryModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'update_time'
    ];

}
