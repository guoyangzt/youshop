<?php

namespace app\admin\model\store;

use app\common\model\store\Access as AccessModel;

/**
 * 商家用户权限模型
 * Class Access
 * @package app\admin\model\store
 */
class Access extends AccessModel
{
    /**
     * 获取权限列表
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        $all = static::getAll();
        return $this->formatTreeData($all);
    }

    /**
     * 新增记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        $data['wxapp_id'] = self::$wxapp_id;
        return $this->allowField(true)->save($data);
    }

    /**
     * 更新记录
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit($data)
    {
        // 判断上级角色是否为当前子级
        if ($data['parent_id'] > 0) {
            // 获取所有上级id集
            $parentIds = $this->getTopAccessIds($data['parent_id']);
            if (in_array($this['access_id'], $parentIds)) {
                $this->error = '上级权限不允许设置为当前子权限';
                return false;
            }
        }
        return $this->allowField(true)->save($data) !== false;
    }

    /**
     * 删除权限
     * @return bool|int
     * @throws \think\exception\DbException
     */
    public function remove()
    {
        // 判断是否存在下级权限
        if (self::detail(['parent_id' => $this['access_id']])) {
            $this->error = '当前权限下存在子权限，请先删除';
            return false;
        }
        return $this->delete();
    }

    /**
     * 获取所有上级id集
     * @param $access_id
     * @param null $all
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getTopAccessIds($access_id, &$all = null)
    {
        static $ids = [];
        is_null($all) && $all = $this->getAll();
        foreach ($all as $item) {
            if ($item['access_id'] == $access_id && $item['parent_id'] > 0) {
                $ids[] = $item['parent_id'];
                $this->getTopAccessIds($item['parent_id'], $all);
            }
        }
        return $ids;
    }

    /**
     * 获取权限列表
     * @param $all
     * @param int $parent_id
     * @param int $deep
     * @return array
     */
    private function formatTreeData(&$all, $parent_id = 0, $deep = 1)
    {
        static $tempTreeArr = [];
        foreach ($all as $key => $val) {
            if ($val['parent_id'] == $parent_id) {
                // 记录深度
                $val['deep'] = $deep;
                // 根据角色深度处理名称前缀
                $val['name_h1'] = $this->htmlPrefix($deep) . $val['name'];
                $tempTreeArr[] = $val;
                $this->formatTreeData($all, $val['access_id'], $deep + 1);
            }
        }
        return $tempTreeArr;
    }

    private function htmlPrefix($deep)
    {
        // 根据角色深度处理名称前缀
        $prefix = '';
        if ($deep > 1) {
            for ($i = 1; $i <= $deep - 1; $i++) {
                $prefix .= '&nbsp;&nbsp;&nbsp;├ ';
            }
            $prefix .= '&nbsp;';
        }
        return $prefix;
    }

    /**
     * 新增默认权限
     */
    public function insertDefault()
    {
        $defaultData = $this->defaultData();
        $this->buildData($defaultData);
    }

    /**
     * 生成并写入默认数据
     * @param $defaultData
     * @param int $parent_id
     */
    private function buildData(&$defaultData, $parent_id = 0)
    {
        foreach ($defaultData as $key => $item) {
            // 保存数据
            $model = new static;
            $model->save([
                'name' => $item['name'],
                'url' => $item['url'],
                'parent_id' => $parent_id,
                'sort' => 100,
            ]);
            if (isset($item['subset']) && !empty($item['subset'])) {
                $this->buildData($item['subset'], $model['access_id']);
            }
        }
    }

    /**
     * 默认权限数据
     * @return array
     */
    private function defaultData()
    {
        return [
            [
                'name' => '首页',
                'url' => 'index/index'
            ],
            [
                'name' => '管理员',
                'url' => 'store',
                'subset' => [
                    [
                        'name' => '管理员管理',
                        'url' => 'store.user',
                        'subset' => [
                            [
                                'name' => '管理员列表',
                                'url' => 'store.user/index'
                            ],
                            [
                                'name' => '添加管理员',
                                'url' => 'store.user/add'
                            ],
                            [
                                'name' => '编辑管理员',
                                'url' => 'store.user/edit'
                            ],
                            [
                                'name' => '删除管理员',
                                'url' => 'store.user/delete'
                            ],
                        ]
                    ],
                    [
                        'name' => '角色管理',
                        'url' => 'store.role',
                        'subset' => [
                            [
                                'name' => '角色列表',
                                'url' => 'store.role/index'
                            ],
                            [
                                'name' => '添加角色',
                                'url' => 'store.role/add'
                            ],
                            [
                                'name' => '编辑角色',
                                'url' => 'store.role/edit'
                            ],
                            [
                                'name' => '删除角色',
                                'url' => 'store.role/delete'
                            ],
                        ]
                    ],
                    [
                        'name' => '权限管理',
                        'url' => 'store.access',
                        'subset' => [
                            [
                                'name' => '权限列表',
                                'url' => 'store.access/index'
                            ],
                            [
                                'name' => '添加权限',
                                'url' => 'store.access/add'
                            ],
                            [
                                'name' => '编辑权限',
                                'url' => 'store.access/edit'
                            ],
                            [
                                'name' => '删除权限',
                                'url' => 'store.access/delete'
                            ],
                        ]
                    ],
                ]
            ],
            [
                'name' => '商品管理',
                'url' => 'goods',
                'subset' => [
                    [
                        'name' => '商品管理',
                        'url' => 'goods',
                        'subset' => [
                            [
                                'name' => '商品列表',
                                'url' => 'goods/index',
                            ],
                            [
                                'name' => '添加商品',
                                'url' => 'goods/add',
                            ],
                            [
                                'name' => '编辑商品',
                                'url' => 'goods/edit',
                            ],
                            [
                                'name' => '复制商品',
                                'url' => 'goods/copy',
                            ],
                            [
                                'name' => '删除商品',
                                'url' => 'goods/delete',
                            ],
                            [
                                'name' => '商品上下架',
                                'url' => 'goods/state',
                            ],
                        ]
                    ],
                    [
                        'name' => '商品分类',
                        'url' => 'goods.category',
                        'subset' => [
                            [
                                'name' => '分类列表',
                                'url' => 'goods.category/index',
                            ],
                            [
                                'name' => '添加分类',
                                'url' => 'goods.category/add',
                            ],
                            [
                                'name' => '编辑分类',
                                'url' => 'goods.category/edit',
                            ],
                            [
                                'name' => '删除分类',
                                'url' => 'goods.category/delete',
                            ],
                        ],
                    ],
                    [
                        'name' => '商品评价',
                        'url' => 'goods.comment',
                        'subset' => [
                            [
                                'name' => '评价列表',
                                'url' => 'goods.comment/index',
                            ],
                            [
                                'name' => '评价详情',
                                'url' => 'goods.comment/detail',
                            ],
                            [
                                'name' => '删除评价',
                                'url' => 'goods.comment/delete',
                            ],
                        ],
                    ],
                ]
            ],
            [
                'name' => '订单管理',
                'url' => 'order',
                'subset' => [
                    [
                        'name' => '订单列表',
                        'url' => '',
                        'subset' => [
                            [
                                'name' => '待发货',
                                'url' => 'order/delivery_list'
                            ],
                            [
                                'name' => '待收货',
                                'url' => 'order/receipt_list'
                            ],
                            [
                                'name' => '待付款',
                                'url' => 'order/pay_list'
                            ],
                            [
                                'name' => '已完成',
                                'url' => 'order/complete_list'
                            ],
                            [
                                'name' => '已取消',
                                'url' => 'order/cancel_list'
                            ],
                            [
                                'name' => '全部订单',
                                'url' => 'order/all_list',
                            ],
                        ]
                    ],
                    [
                        'name' => '订单详情',
                        'url' => '',
                        'subset' => [
                            [
                                'name' => '详情信息',
                                'url' => 'order/detail',
                            ],
                            [
                                'name' => '确认发货',
                                'url' => 'order/delivery',
                            ],
                            [
                                'name' => '修改订单价格',
                                'url' => 'order/updateprice',
                            ],
                        ]
                    ],
                    [
                        'name' => '订单导出',
                        'url' => 'order.operate/export',
                    ],
                    [
                        'name' => '批量发货',
                        'url' => 'order.operate/batchdelivery',
                    ],
                ]
            ],
            [
                'name' => '用户管理',
                'url' => 'user',
                'subset' => [
                    [
                        'name' => '用户列表',
                        'url' => 'user/index'
                    ],
                    [
                        'name' => '删除用户',
                        'url' => 'user/delete'
                    ],
                ]
            ],
            [
                'name' => '营销设置',
                'url' => 'market',
                'subset' => [
                    [
                        'name' => '优惠券',
                        'url' => 'coupon',
                        'subset' => [
                            [
                                'name' => '优惠券列表',
                                'url' => 'market.coupon/index',
                            ],
                            [
                                'name' => '新增优惠券',
                                'url' => 'market.coupon/add',
                            ],
                            [
                                'name' => '编辑优惠券',
                                'url' => 'market.coupon/edit',
                            ],
                            [
                                'name' => '删除优惠券',
                                'url' => 'market.coupon/delete',
                            ],
                            [
                                'name' => '领取记录',
                                'url' => 'market.coupon/receive',
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => '小程序',
                'url' => 'wxapp',
                'subset' => [
                    [
                        'name' => '小程序设置',
                        'url' => 'wxapp/setting',
                    ],
                    [
                        'name' => '页面管理',
                        'url' => 'wxapp.page',
                        'subset' => [
                            [
                                'name' => '页面设计',
                                'url' => '',
                                'subset' => [
                                    [
                                        'name' => '页面列表',
                                        'url' => 'wxapp.page/index',
                                    ],
                                    [
                                        'name' => '新增页面',
                                        'url' => 'wxapp.page/add',
                                    ],
                                    [
                                        'name' => '编辑页面',
                                        'url' => 'wxapp.page/edit',
                                    ],
                                    [
                                        'name' => '设为首页',
                                        'url' => 'wxapp.page/sethome',
                                    ],
                                ]
                            ],
                            [
                                'name' => '分类页模板',
                                'url' => 'wxapp.page/category',
                            ],
                            [
                                'name' => '页面链接',
                                'url' => 'wxapp.page/links',
                            ],
                        ]
                    ],
                    [
                        'name' => '帮助中心',
                        'url' => 'wxapp.help',
                        'subset' => [
                            [
                                'name' => '帮助列表',
                                'url' => 'wxapp.help/index',
                            ],
                            [
                                'name' => '新增帮助',
                                'url' => 'wxapp.help/add',
                            ],
                            [
                                'name' => '编辑帮助',
                                'url' => 'wxapp.help/edit',
                            ],
                            [
                                'name' => '删除帮助',
                                'url' => 'wxapp.help/delete',
                            ],
                        ]
                    ],
                ]
            ],
            [
                'name' => '应用中心',
                'url' => 'apps',
                'subset' => [
                    [
                        'name' => '分销中心',
                        'url' => 'apps.dealer',
                        'subset' => [
                            [
                                'name' => '入驻申请',
                                'url' => 'apps.dealer.apply',
                                'subset' => [
                                    [
                                        'name' => '申请列表',
                                        'url' => 'apps.dealer.apply/index'
                                    ],
                                    [
                                        'name' => '分销商审核',
                                        'url' => 'apps.dealer.apply/submit'
                                    ]
                                ]
                            ],
                            [
                                'name' => '分销商用户',
                                'url' => 'apps.dealer.user',
                                'subset' => [
                                    [
                                        'name' => '分销商列表',
                                        'url' => 'apps.dealer.user/index',
                                    ],
                                    [
                                        'name' => '删除分销商',
                                        'url' => 'apps.dealer.user/delete'
                                    ],
                                    [
                                        'name' => '分销商二维码',
                                        'url' => 'apps.dealer.user/qrcode'
                                    ]
                                ]
                            ],
                            [
                                'name' => '分销订单',
                                'url' => 'apps.dealer.order/index',
                            ],
                            [
                                'name' => '提现申请',
                                'url' => 'apps.dealer.withdraw',
                                'subset' => [
                                    [
                                        'name' => '申请列表',
                                        'url' => 'apps.dealer.withdraw/index',
                                    ],
                                    [
                                        'name' => '提现审核',
                                        'url' => 'apps.dealer.withdraw/submit'
                                    ],
                                    [
                                        'name' => '确认打款',
                                        'url' => 'apps.dealer.withdraw/money'
                                    ]
                                ]
                            ],
                            [
                                'name' => '分销设置',
                                'url' => 'apps.dealer.setting/index',
                            ],
                            [
                                'name' => '分销海报',
                                'url' => 'apps.dealer.setting/qrcode',
                            ],
                        ]
                    ],
                ]
            ],
            [
                'name' => '设置',
                'url' => 'setting',
                'subset' => [
                    [
                        'name' => '商城设置',
                        'url' => 'setting/store',
                    ],
                    [
                        'name' => '交易设置',
                        'url' => 'setting/trade',
                    ],
                    [
                        'name' => '配送设置',
                        'url' => 'setting.delivery',
                        'subset' => [
                            [
                                'name' => '运费模板列表',
                                'url' => 'setting.delivery/index'
                            ],
                            [
                                'name' => '新增运费模板',
                                'url' => 'setting.delivery/add'
                            ],
                            [
                                'name' => '编辑运费模板',
                                'url' => 'setting.delivery/edit'
                            ],
                            [
                                'name' => '删除运费模板',
                                'url' => 'setting.delivery/delete'
                            ],
                        ]
                    ],
                    [
                        'name' => '物流公司',
                        'url' => 'setting.express',
                        'subset' => [
                            [
                                'name' => '物流公司列表',
                                'url' => 'setting.express/index'
                            ],
                            [
                                'name' => '新增物流公司',
                                'url' => 'setting.express/add'
                            ],
                            [
                                'name' => '编辑物流公司',
                                'url' => 'setting.express/edit'
                            ],
                            [
                                'name' => '删除物流公司',
                                'url' => 'setting.express/delete'
                            ],
                        ]
                    ],
                    [
                        'name' => '短信通知',
                        'url' => 'setting/sms',
                    ],
                    [
                        'name' => '模板消息',
                        'url' => 'setting/tplmsg',
                    ],
                    [
                        'name' => '上传设置',
                        'url' => 'setting/storage',
                    ],
                    [
                        'name' => '其他',
                        'url' => '',
                        'subset' => [
                            [
                                'name' => '清理缓存',
                                'url' => 'setting.cache/clear',
                            ],
                        ]
                    ]
                ]
            ],
        ];
    }

}