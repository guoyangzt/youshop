<?php

namespace app\common\service\order;

use app\common\model\User as UserModel;
use app\common\model\Wxapp as WxappModel;
use app\common\model\user\BalanceLog as BalanceLogModel;
use app\common\enum\order\PayType as PayTypeEnum;
use app\common\enum\user\balanceLog\Scene as SceneEnum;
use app\common\library\wechat\WxPay;

/**
 * 订单退款服务类
 * Class Refund
 * @package app\common\service\order
 */
class Refund
{
    /**
     * 执行订单退款
     * @param \app\common\model\BaseModel $order 订单信息
     * @param double|null $money 指定退款金额
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \app\common\exception\BaseException
     */
    public function execute(&$order, $money = null)
    {
        // 退款金额，如不指定则默认为订单实付款金额
        is_null($money) && $money = $order['pay_price'];
        // 1.微信支付退款
        if ($order['pay_type']['value'] == PayTypeEnum::WECHAT) {
            return $this->wxpay($order, $money);
        }
        // 2.余额支付退款
        if ($order['pay_type']['value'] == PayTypeEnum::BALANCE) {
            return $this->balance($order, $money);
        }
        return false;
    }

    /**
     * 余额支付退款
     * @param $order
     * @param $money
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    private function balance(&$order, $money)
    {
        // 回退用户余额
        $user = UserModel::detail($order['user_id']);
        $user->setInc('balance', $money);
        // 记录余额明细
        BalanceLogModel::add(SceneEnum::REFUND, [
            'user_id' => $user['user_id'],
            'money' => $money,
        ], ['order_no' => $order['order_no']]);
        return true;
    }

    /**
     * 微信支付退款
     * @param $order
     * @param double $money
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    private function wxpay(&$order, $money)
    {
        $wxConfig = WxappModel::getWxappCache($order['wxapp_id']);
        $WxPay = new WxPay($wxConfig);
        return $WxPay->refund($order['transaction_id'], $order['pay_price'], $money);
    }

}