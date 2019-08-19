<?php

namespace app\admin\model;

use app\common\model\WxappCategory as WxappCategoryModel;

/**
 * 微信小程序分类页模板
 * Class WxappCategory
 * @package app\store\model
 */
class WxappCategory extends WxappCategoryModel
{
    /**
     * 新增分类页模板
     * @param $wxapp_id
     * @return false|int
     */
    public function insertDefault($wxapp_id)
    {
        return $this->save([
            'wxapp_id' => $wxapp_id,
            'category_style' => '11',
            'share_title' => '',
        ]);
    }

}