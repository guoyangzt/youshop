<?php

namespace app\common\model\dealer;

use app\common\model\BaseModel;
use think\Cache;

/**
 * 分销商设置模型
 * Class Apply
 * @package app\common\model\dealer
 */
class Setting extends BaseModel
{
    protected $name = 'dealer_setting';
    protected $createTime = false;

    /**
     * 获取器: 转义数组格式
     * @param $value
     * @return mixed
     */
    public function getValuesAttr($value)
    {
        return json_decode($value, true);
    }

    /**
     * 修改器: 转义成json格式
     * @param $value
     * @return string
     */
    public function setValuesAttr($value)
    {
        return json_encode($value);
    }

    /**
     * 获取指定项设置
     * @param $key
     * @param $wxapp_id
     * @return array
     */
    public static function getItem($key, $wxapp_id = null)
    {
        $data = static::getAll($wxapp_id);
        return isset($data[$key]) ? $data[$key]['values'] : [];
    }

    /**
     * 获取分销商设置
     * @param null $wxapp_id
     * @return array|mixed
     */
    public static function getAll($wxapp_id = null)
    {
        $self = new static;
        is_null($wxapp_id) && $wxapp_id = $self::$wxapp_id;
        if (!$data = Cache::get('dealer_setting_' . $wxapp_id)) {
            $data = array_column(collection($self::all())->toArray(), null, 'key');
            Cache::tag('cache')->set('dealer_setting_' . $wxapp_id, $data);
        }
        return array_merge_multiple($self->defaultData(), $data);
    }

    /**
     * 获取设置项信息
     * @param $key
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($key)
    {
        return static::get(compact('key'));
    }

    /**
     * 是否开启分销功能
     * @param null $wxapp_id
     * @return mixed
     */
    public static function isOpen($wxapp_id = null)
    {
        return static::getItem('basic', $wxapp_id)['is_open'];
    }

    /**
     * 分销中心页面名称
     * @param null $wxapp_id
     * @return mixed
     */
    public static function getDealerTitle($wxapp_id = null)
    {
        return static::getItem('words', $wxapp_id)['index']['title']['value'];
    }

    /**
     * 默认配置
     * @return array
     */
    public function defaultData()
    {
        return [
            'basic' => [
                'key' => 'basic',
                'describe' => '基础设置',
                'values' => [
                    // 是否开启分销功能
                    'is_open' => '0',   // 参数值：1开启 0关闭
                    // 分销层级
                    'level' => '3', // 参数值：1一级 2二级 3三级
                    // 分销商内购
                    'self_buy' => '0'   // 参数值：1开启 0关闭
                ],
            ],
            'condition' => [
                'key' => 'condition',
                'describe' => '分销商条件',
                'values' => [
                    // 成为分销商条件
                    'become' => '10',   // 参数值：10填写申请信息(需后台审核) 20填写申请信息(无需审核)
                    // 购买指定商品成为分销商 0关闭 1开启
                    'become__buy_goods' => '0',
                    // 购买指定商品的id集
                    'become__buy_goods_ids' => [],
                    // 成为下线条件
                    'downline' => '10',  // 参数值：10首次点击分享链接 20首次下单 30首次付款
                ]
            ],
            'commission' => [
                'key' => 'commission',
                'describe' => '佣金设置',
                'values' => [
                    // 一级佣金
                    'first_money' => '0',
                    // 一级佣金
                    'second_money' => '0',
                    // 一级佣金
                    'third_money' => '0',
                ]
            ],
            'settlement' => [
                'key' => 'settlement',
                'describe' => '结算',
                'values' => [
                    // 提现方式
                    'pay_type' => [],   // 参数值：10微信支付 20支付宝支付 30银行卡支付
                    // 微信支付自动打款
                    'wechat_pay_auto' => '0',       // 微信支付自动打款：1开启 0关闭
                    // 最低提现额度
                    'min_money' => '10.00',
                    // 佣金结算天数
                    'settle_days' => '10',
                ]
            ],
            'words' => [
                'key' => 'words',
                'describe' => '自定义文字',
                'values' => [
                    'index' => [
                        'title' => [
                            'default' => '分销中心',
                            'value' => '分销中心'
                        ],
                        'words' => [
                            'not_dealer' => [
                                'default' => '您还不是分销商，请先提交申请',
                                'value' => '您还不是分销商，请先提交申请'
                            ],
                            'apply_now' => [
                                'default' => '立即加入',
                                'value' => '立即加入'
                            ],
                            'referee' => [
                                'default' => '推荐人',
                                'value' => '推荐人'
                            ],
                            'money' => [
                                'default' => '可提现佣金',
                                'value' => '可提现'
                            ],
                            'freeze_money' => [
                                'default' => '待提现佣金',
                                'value' => '待提现'
                            ],
                            'total_money' => [
                                'default' => '已提现金额',
                                'value' => '已提现金额'
                            ],
                            'withdraw' => [
                                'default' => '去提现',
                                'value' => '去提现'
                            ],
                        ]
                    ],
                    'apply' => [
                        'title' => [
                            'default' => '申请成为分销商',
                            'value' => '申请成为分销商'
                        ],
                        'words' => [
                            'title' => [
                                'default' => '请填写申请信息',
                                'value' => '请填写申请信息'
                            ],
                            'license' => [
                                'default' => '分销商申请协议',
                                'value' => '分销商申请协议'
                            ],
                            'submit' => [
                                'default' => '申请成为经销商',
                                'value' => '申请成为经销商'
                            ],
                            'wait_audit' => [
                                'default' => '您的申请已受理，正在进行信息核验，请耐心等待。',
                                'value' => '您的申请已受理，正在进行信息核验，请耐心等待。'
                            ],
                            'goto_mall' => [
                                'default' => '去商城逛逛',
                                'value' => '去商城逛逛'
                            ],
                        ]
                    ],
                    'order' => [
                        'title' => [
                            'default' => '分销订单',
                            'value' => '分销订单'
                        ],
                        'words' => [
                            'all' => [
                                'default' => '全部',
                                'value' => '全部'
                            ],
                            'unsettled' => [
                                'default' => '未结算',
                                'value' => '未结算'
                            ],
                            'settled' => [
                                'default' => '已结算',
                                'value' => '已结算'
                            ],
                        ]
                    ],
                    'team' => [
                        'title' => [
                            'default' => '我的团队',
                            'value' => '我的团队'
                        ],
                        'words' => [
                            'total_team' => [
                                'default' => '团队总人数',
                                'value' => '团队总人数'
                            ],
                            'first' => [
                                'default' => '一级团队',
                                'value' => '一级团队'
                            ],
                            'second' => [
                                'default' => '二级团队',
                                'value' => '二级团队'
                            ],
                            'third' => [
                                'default' => '三级团队',
                                'value' => '三级团队'
                            ],
                        ]
                    ],
                    'withdraw_list' => [
                        'title' => [
                            'default' => '提现明细',
                            'value' => '提现明细'
                        ],
                        'words' => [
                            'all' => [
                                'default' => '全部',
                                'value' => '全部'
                            ],
                            'apply_10' => [
                                'default' => '审核中',
                                'value' => '审核中'
                            ],
                            'apply_20' => [
                                'default' => '审核通过',
                                'value' => '审核通过'
                            ],
                            'apply_40' => [
                                'default' => '已打款',
                                'value' => '已打款'
                            ],
                            'apply_30' => [
                                'default' => '驳回',
                                'value' => '驳回'
                            ],
                        ]
                    ],
                    'withdraw_apply' => [
                        'title' => [
                            'default' => '申请提现',
                            'value' => '申请提现'
                        ],
                        'words' => [
                            'capital' => [
                                'default' => '可提现佣金',
                                'value' => '可提现佣金'
                            ],
                            'money' => [
                                'default' => '提现金额',
                                'value' => '提现金额'
                            ],
                            'money_placeholder' => [
                                'default' => '请输入要提取的金额',
                                'value' => '请输入要提取的金额'
                            ],
                            'min_money' => [
                                'default' => '最低提现佣金',
                                'value' => '最低提现佣金'
                            ],
                            'submit' => [
                                'default' => '提交申请',
                                'value' => '提交申请'
                            ],
                        ]
                    ],
                    'qrcode' => [
                        'title' => [
                            'default' => '推广二维码',
                            'value' => '推广二维码'
                        ]
                    ],
                ]
            ],
            'license' => [
                'key' => 'license',
                'describe' => '申请协议',
                'values' => [
                    'license' => ''
                ]
            ],
            'background' => [
                'key' => 'background',
                'describe' => '页面背景图',
                'values' => [
                    // 分销中心首页
                    'index' => self::$base_url . 'assets/api/dealer-bg.png',
                    // 申请成为分销商页
                    'apply' => self::$base_url . 'assets/api/dealer-bg.png',
                    // 申请提现页
                    'withdraw_apply' => self::$base_url . 'assets/api/dealer-bg.png',
                ],
            ],
            'template_msg' => [
                'key' => 'template_msg',
                'describe' => '模板消息',
                'values' => [
                    'apply_tpl' => '',    // 分销商审核通知
                    'withdraw_tpl' => '',    // 提现状态通知
                ]
            ],
            'qrcode' => [
                'key' => 'template_msg',
                'describe' => '分销海报',
                'values' => [
                    'backdrop' => [
                        'src' => self::$base_url . 'assets/store/img/dealer/backdrop.png',
                    ],
                    'nickName' => [
                        'fontSize' => 14,
                        'color' => '#000000',
                        'left' => 150,
                        'top' => 99
                    ],
                    'avatar' => [
                        'width' => 70,
                        'style' => 'circle',
                        'left' => 150,
                        'top' => 18
                    ],
                    'qrcode' => [
                        'width' => 100,
                        'style' => 'circle',
                        'left' => 136,
                        'top' => 128
                    ]
                ],
            ]
        ];
    }

}