<?php

namespace app\api\validate\sharing\order;

use think\Validate;

class Checkout extends Validate
{
    /**
     * 验证规则
     * @var array
     */
    protected $rule = [

        // 商品id
        'goods_id' => [
            'require',
            'number',
            'gt' => 0
        ],

        // 购买数量
        'goods_num' => [
            'require',
            'number',
            'gt' => 0
        ],

        // 商品sku_id
        'goods_sku_id' => [
            'require',
        ],

    ];

    /**
     * 验证场景
     * @var array
     */
    protected $scene = [
        'buyNow' => ['goods_id', 'goods_num', 'goods_sku_id'],
    ];

}
