<?php

namespace app\task\controller;

use app\common\library\wechat\WxPay;

/**
 * 支付成功异步通知接口
 * Class Notify
 * @package app\task\controller
 */
class Notify
{
    /**
     * 支付成功异步通知
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function order()
    {
        // 微信支付组件：验证异步通知
        $WxPay = new WxPay(false);
        $WxPay->notify();
    }
}
