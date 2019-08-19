<?php

namespace app\store\model;

use app\common\model\WxappCategory as WxappCategoryModel;

/**
 * 微信小程序分类页模板
 * Class WxappCategory
 * @package app\store\model
 */
class WxappCategory extends WxappCategoryModel
{
    /**
     * 编辑记录
     * @param $data
     * @return bool|int
     */
    public function edit($data)
    {
        return $this->allowField(true)->save($data) !== false;
    }

}