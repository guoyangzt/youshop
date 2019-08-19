<?php

namespace app\store\controller;

use app\store\model\Store as StoreModel;

/**
 * 后台首页
 * Class Index
 * @package app\store\controller
 */
class Index extends Controller
{
    /**
     * 后台首页
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function index()
    {
        // 当前用户菜单url
        $menus = $this->menus();
        $url = current(array_values($menus))['index'];
        if ($url !== 'index/index') {
            $this->redirect($url);
        }
        $model = new StoreModel;
        return $this->fetch('index', ['data' => $model->getHomeData()]);
    }

}
