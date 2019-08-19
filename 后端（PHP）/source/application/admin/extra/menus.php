<?php
/**
 * 后台菜单配置
 *    'home' => [
 *       'name' => '首页',                // 菜单名称
 *       'icon' => 'icon-home',          // 图标 (class)
 *       'index' => 'index/index',         // 链接
 *     ],
 */
return [
    'store' => [
        'name' => '小程序商城',
        'icon' => 'icon-shangcheng',
        'submenu' => [
            [
                'name' => '商城列表',
                'index' => 'store/index',
                'uris' => [
                    'store/index',
                    'store/add',
                ]
            ],
            [
                'name' => '回收站',
                'index' => 'store/recycle'
            ],
            [
                'name' => '权限管理',
                'index' => 'store.access/index'
            ]
        ],
    ],
    'setting' => [
        'name' => '系统设置',
        'icon' => 'icon-shezhi',
        'submenu' => [
            [
                'name' => '清理缓存',
                'index' => 'setting.cache/clear'
            ],
            [
                'name' => '环境检测',
                'index' => 'setting.science/index'
            ],
        ],
    ],
];
