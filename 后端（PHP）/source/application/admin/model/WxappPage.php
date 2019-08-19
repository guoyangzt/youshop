<?php

namespace app\admin\model;

use app\common\model\WxappPage as WxappPageModel;

/**
 * 微信小程序diy页面模型
 * Class WxappPage
 * @package app\admin\model
 */
class WxappPage extends WxappPageModel
{
    /**
     * 新增小程序首页diy默认设置
     * @param $wxapp_id
     * @return false|int
     */
    public function insertDefault($wxapp_id)
    {
        return $this->save([
            'page_type' => 10,
            'page_name' => '小程序首页',
            'page_data' => [
                'page' => [
                    'type' => 'page',
                    'name' => '页面设置',
                    'params' => [
                        'name' => '页面标题',
                        'title' => '页面标题',
                        'share_title' => '分享标题'
                    ],
                    'style' => [
                        'titleTextColor' => 'black',
                        'titleBackgroundColor' => '#ffffff',
                    ]
                ],
                'items' => [
                    [
                        'type' => 'search',
                        'name' => '搜索框',
                        'params' => ['placeholder' => '搜索商品'],
                        'style' => [
                            'textAlign' => 'center',
                            'searchStyle' => 'radius',
                        ],
                    ],
                    [
                        'type' => 'banner',
                        'name' => '图片轮播',
                        'style' => [
                            'btnColor' => '#ffffff',
                            'btnShape' => 'round',
                        ],
                        'params' => [
                            'interval' => '2800'
                        ],
                        'data' => [
                            [
                                'imgUrl' => self::$base_url . 'assets/store/img/diy/banner/01.png',
                                'linkUrl' => '',
                            ],
                            [
                                'imgUrl' => self::$base_url . 'assets/store/img/diy/banner/01.png',
                                'linkUrl' => '',
                            ],
                        ],
                    ]
                ],
            ],
            'wxapp_id' => $wxapp_id
        ]);
    }

}
