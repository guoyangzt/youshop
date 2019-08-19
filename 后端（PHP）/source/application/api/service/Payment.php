<?php

namespace app\api\service;

use app\api\model\Wxapp as WxappModel;
use app\api\model\WxappPrepayId as WxappPrepayIdModel;
use app\common\library\wechat\WxPay;
use app\common\enum\OrderType as OrderTypeEnum;

class Payment
{
    /**
     * 构建微信支付
     * @param \app\api\model\User $user
     * @param $orderId
     * @param $orderNo
     * @param $payPrice
     * @param int $orderType
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public static function wechat(
        $user,
        $orderId,
        $orderNo,
        $payPrice,
        $orderType = OrderTypeEnum::MASTER
    )
    {
        // 统一下单API
        $wxConfig = WxappModel::getWxappCache($user['wxapp_id']);
        $WxPay = new WxPay($wxConfig);
        $payment = $WxPay->unifiedorder($orderNo, $user['open_id'], $payPrice, $orderType);
        // 记录prepay_id
        $model = new WxappPrepayIdModel;
        $model->add($payment['prepay_id'], $orderId, $user['user_id'], $orderType);
        return $payment;
    }

}