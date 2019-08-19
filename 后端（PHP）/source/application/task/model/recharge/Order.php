<?php

namespace app\task\model\recharge;

use app\common\model\recharge\Order as OrderModel;

use app\task\model\User as UserModel;
use app\task\model\user\BalanceLog as BalanceLogModel;
use app\task\model\WxappPrepayId as WxappPrepayIdModel;

use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\order\PayType as PayTypeEnum;
use app\common\enum\user\balanceLog\Scene as SceneEnum;
use app\common\enum\recharge\order\PayStatus as PayStatusEnum;

/**
 * 用户充值订单模型
 * Class Order
 * @package app\task\model\recharge
 */
class Order extends OrderModel
{
    /**
     * 获取订单详情(待付款状态)
     * @param $orderNo
     * @return OrderModel|null
     * @throws \think\exception\DbException
     */
    public function payDetail($orderNo)
    {
        return self::detail(['order_no' => $orderNo, 'pay_status' => PayStatusEnum::PENDING]);
    }

    /**
     * 订单支付成功业务处理
     * @param int $payType 支付类型
     * @param array $payData 支付回调数据
     * @return bool
     */
    public function paySuccess($payType, $payData)
    {
        $this->transaction(function () use ($payType, $payData) {
            // 更新订单状态
            $this->save([
                'pay_status' => PayStatusEnum::SUCCESS,
                'pay_time' => time(),
                'transaction_id' => $payData['transaction_id']
            ]);
            // 累积用户余额
            $User = UserModel::detail($this['user_id']);
            $User->setInc('balance', $this['actual_money']);
            // 用户余额变动明细
            BalanceLogModel::add(SceneEnum::RECHARGE, [
                'user_id' => $this['user_id'],
                'money' => $this['actual_money'],
                'wxapp_id' => $this['wxapp_id'],
            ], ['order_no' => $this['order_no']]);
            // 更新prepay_id记录
            if ($payType == PayTypeEnum::WECHAT) {
                WxappPrepayIdModel::updatePayStatus($this['order_id'], OrderTypeEnum::RECHARGE);
            }
        });
        return true;
    }

}