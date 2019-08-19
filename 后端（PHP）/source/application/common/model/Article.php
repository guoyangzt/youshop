<?php

namespace app\common\model;

/**
 * 文章模型
 * Class Article
 * @package app\common\model
 */
class Article extends BaseModel
{
    protected $name = 'article';
    protected $append = ['show_views'];

    /**
     * 关联文章封面图
     * @return \think\model\relation\HasOne
     */
    public function image()
    {
        return $this->hasOne('uploadFile', 'file_id', 'image_id');
    }

    /**
     * 关联文章分类表
     * @return \think\model\relation\BelongsTo
     */
    public function category()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->BelongsTo("app\\{$module}\\model\\article\\Category");
    }

    /**
     * 展示的浏览次数
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getShowViewsAttr($value, $data)
    {
        return $data['virtual_views'] + $data['actual_views'];
    }

    /**
     * 文章详情
     * @param $article_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($article_id)
    {
        return self::get($article_id, ['image', 'category']);
    }

}
