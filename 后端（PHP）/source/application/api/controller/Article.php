<?php

namespace app\api\controller;

use app\api\model\Article as ArticleModel;
use app\api\model\article\Category as CategoryModel;

/**
 * 文章控制器
 * Class Article
 * @package app\api\controller
 */
class Article extends Controller
{
    /**
     * 文章首页
     * @return array
     */
    public function index()
    {
        // 文章分类列表
        $categoryList = CategoryModel::getAll();
        return $this->renderSuccess(compact('categoryList'));
    }

    /**
     * 文章列表
     * @param int $category_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($category_id = 0)
    {
        $model = new ArticleModel;
        $list = $model->getList($category_id);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 文章详情
     * @param $article_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function detail($article_id)
    {
        $detail = ArticleModel::detail($article_id);
        return $this->renderSuccess(compact('detail'));
    }

}
