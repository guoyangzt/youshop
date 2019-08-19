<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用行为扩展定义文件
return [
    // 应用初始化
    'app_init' => [],
    // 应用开始
    'app_begin' => [
        'app\\common\\behavior\\App'
    ],
    // 模块初始化
    'module_init' => [],
    // 操作开始执行
    'action_begin' => [],
    // 视图内容过滤
    'view_filter' => [],
    // 日志写入
    'log_write' => [],
    // 应用结束
    'app_end' => [],

    // 订单行为管理
    'order' => [
        'app\\task\\behavior\\Order'
    ],

    // 优惠券行为管理
    'UserCoupon' => [
        'app\\task\\behavior\\UserCoupon'
    ],

    // 分销商订单行为管理
    'DealerOrder' => [
        'app\\task\\behavior\\DealerOrder'
    ],

    // 拼团订单行为管理
    'sharing_order' => [
        'app\\task\\behavior\\sharing\\Order'
    ],

    // 拼团拼单行为管理
    'sharing_active' => [
        'app\\task\\behavior\\sharing\\Active'
    ],

    // 会员等级行为管理
    'user_grade' => [
        'app\\task\\behavior\\user\\Grade'
    ],

];
