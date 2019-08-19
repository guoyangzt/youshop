<?php

namespace app\api\controller\sharing;

use app\api\controller\Controller;
use app\api\model\sharing\Category as CategoryModel;

/**
 * 拼团首页控制器
 * Class Active
 * @package app\api\controller\sharing
 */
class Index extends Controller
{
    /**
     * 拼团首页
     * @return array
     */
    public function index()
    {
        // 拼团分类列表
        $categoryList = CategoryModel::getCacheAll();
        return $this->renderSuccess(compact('categoryList'));
    }

}
