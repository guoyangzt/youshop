<?php

namespace app\store\controller\data;

use app\store\controller\Controller;
use app\store\model\Goods as GoodsModel;
use app\store\model\Category as CategoryModel;

/**
 * 商品数据控制器
 * Class Goods
 * @package app\store\controller\data
 */
class Goods extends Controller
{
    /* @var \app\store\model\Goods $model */
    private $model;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new GoodsModel;
        $this->view->engine->layout(false);
    }

    /**
     * 商品列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function lists()
    {
        // 商品分类
        $catgory = CategoryModel::getCacheTree();
        // 商品列表
        $list = $this->model->getList($this->request->param());
        return $this->fetch('list', compact('list', 'catgory'));
    }

}
