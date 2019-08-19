<?php

namespace app\common\model\article;

use think\Cache;
use app\common\model\BaseModel;

/**
 * 文章分类模型
 * Class Category
 * @package app\common\model
 */
class Category extends BaseModel
{
    protected $name = 'article_category';

    /**
     * 所有分类
     * @return mixed
     */
    public static function getALL()
    {
        $model = new static;
        if (!Cache::get('article_category_' . $model::$wxapp_id)) {
            $data = $model->order(['sort' => 'asc', 'create_time' => 'asc'])->select();
            $all = !empty($data) ? $data->toArray() : [];
            Cache::tag('cache')->set('article_category_' . $model::$wxapp_id, $all);
        }
        return Cache::get('article_category_' . $model::$wxapp_id);
    }

}
