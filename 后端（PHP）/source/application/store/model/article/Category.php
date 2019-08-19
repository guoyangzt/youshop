<?php

namespace app\store\model\article;

use think\Cache;
use app\store\model\Article as ArticleModel;
use app\common\model\article\Category as CategoryModel;

/**
 * 文章分类模型
 * Class Category
 * @package app\store\model\article
 */
class Category extends CategoryModel
{
    /**
     * 分类详情
     * @param $category_id
     * @return Category|null
     * @throws \think\exception\DbException
     */
    public static function detail($category_id)
    {
        return static::get($category_id);
    }

    /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        $data['wxapp_id'] = self::$wxapp_id;
        $this->deleteCache();
        return $this->allowField(true)->save($data);
    }

    /**
     * 编辑记录
     * @param $data
     * @return bool|int
     */
    public function edit($data)
    {
        $this->deleteCache();
        return $this->allowField(true)->save($data);
    }

    /**
     * 删除商品分类
     * @param $category_id
     * @return bool|int
     */
    public function remove($category_id)
    {
        // 判断是否存在文章
        $articleCount = ArticleModel::getArticleTotal(['category_id' => $category_id]);
        if ($articleCount > 0) {
            $this->error = '该分类下存在' . $articleCount . '个文章，不允许删除';
            return false;
        }
        $this->deleteCache();
        return $this->delete();
    }

    /**
     * 删除缓存
     * @return bool
     */
    private function deleteCache()
    {
        return Cache::rm('article_category_' . self::$wxapp_id);
    }

}
