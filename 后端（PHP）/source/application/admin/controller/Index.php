<?php

namespace app\admin\controller;


/**
 * 后台首页
 * Class Index
 * @package app\admin\controller
 */
class Index extends Controller
{
    /**
     * 后台首页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch('index');
    }

}
