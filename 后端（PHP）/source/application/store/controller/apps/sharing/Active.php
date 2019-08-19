<?php

namespace app\store\controller\apps\sharing;

use app\store\controller\Controller;
use app\store\model\sharing\Active as ActiveModel;
use app\store\model\sharing\ActiveUsers as ActiveUsersModel;

/**
 * 拼单管理控制器
 * Class Active
 * @package app\store\controller\apps\sharing
 */
class Active extends Controller
{
    /**
     * 拼单列表
     * @param null $active_id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index($active_id = null)
    {
        $model = new ActiveModel;
        $list = $model->getList($active_id);
        return $this->fetch('index', compact('list'));
    }

    /**
     *
     * @param $active_id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function users($active_id)
    {
        $model = new ActiveUsersModel;
        $list = $model->getList($active_id);
        return $this->fetch('users', compact('list'));
    }

}