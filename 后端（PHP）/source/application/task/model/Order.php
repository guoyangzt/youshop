<?php

namespace app\task\model;

use app\common\model\Order as OrderModel;

use app\task\model\User as UserModel;
use app\task\model\Goods as GoodsModel;
use app\task\model\dealer\Apply as DealerApplyModel;
use app\task\model\user\BalanceLog as BalanceLogModel;
use app\task\model\WxappPrepayId as WxappPrepayIdModel;

use app\common\service\Message as MessageService;
use app\common\service\order\Printer as PrinterService;
use app\common\service\wechat\wow\Order as WowOrder;

use app\common\enum\OrderStatus as OrderStatusEnum;
use app\common\enum\order\PayType as PayTypeEnum;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\user\balanceLog\Scene as SceneEnum;

/**
 * 订单模型
 * Class Order
 * @package app\common\model
 */
class Order extends OrderModel
{
    /**
     * 获取订单列表
     * @param array $filter
     * @param array $with
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($filter = [], $with = [])
    {
        return $this->with($with)
            ->where($filter)
            ->where('is_delete', '=', 0)
            ->select();
    }

    /**
     * 待支付订单详情
     * @param $order_no
     * @return null|static
     * @throws \think\exception\DbException
     */
    public function payDetail($order_no)
    {
        return self::get(['order_no' => $order_no, 'pay_status' => 10, 'is_delete' => 0], ['goods', 'user']);
    }

    /**
     * 订单支付成功业务处理
     * @param int $payType 支付方式
     * @param array $payData 支付回调数据
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function paySuccess($payType, $payData = [])
    {
        // 更新付款状态
        $status = $this->updatePayStatus($payType, $payData);
        if ($status == true) {
            // 发送消息通知
            (new MessageService)->payment($this, OrderTypeEnum::MASTER);
            // 小票打印
            (new PrinterService)->printTicket($this, OrderStatusEnum::ORDER_PAYMENT);
            // 同步好物圈
            (new WowOrder($this['wxapp_id']))->import([$this], true);
        }
        return $status;
    }

    /**
     * 批量更新订单
     * @param $orderIds
     * @param $data
     * @return false|int
     */
    public function onBatchUpdate($orderIds, $data)
    {
        return $this->isUpdate(true)->save($data, ['order_id' => ['in', $orderIds]]);
    }

    /**
     * 更新付款状态
     * @param int $payType 支付方式
     * @param array $payData 支付回调数据
     * @return bool
     * @throws \think\exception\DbException
     */
    private function updatePayStatus($payType, $payData = [])
    {
        // 获取用户信息
        $user = UserModel::detail($this['user_id']);
        // 验证余额支付时用户余额是否满足
        if ($payType == PayTypeEnum::BALANCE) {
            if ($user['balance'] < $this['pay_price']) {
                $this->error = '用户余额不足，无法使用余额支付';
                return false;
            }
        }
        $this->transaction(function () use ($user, $payType, $payData) {
            // 更新商品库存、销量
            (new GoodsModel)->updateStockSales($this['goods']);
            // 更新订单状态
            $order = ['pay_type' => $payType, 'pay_status' => 20, 'pay_time' => time()];
            if ($payType == PayTypeEnum::WECHAT) {
                $order['transaction_id'] = $payData['transaction_id'];
            }
            $this->save($order);
            // 累积用户总消费金额
            $user->setIncPayMoney($this['pay_price']);
            // 购买指定商品成为分销商
            $this->becomeDealerUser($this['user_id'], $this['goods'], $this['wxapp_id']);
            // 余额支付
            if ($payType == PayTypeEnum::BALANCE) {
                // 更新用户余额
                $user->setDec('balance', $this['pay_price']);
                BalanceLogModel::add(SceneEnum::CONSUME, [
                    'user_id' => $user['user_id'],
                    'money' => -$this['pay_price'],
                ], ['order_no' => $this['order_no']]);
            }
            // 微信支付
            if ($payType == PayTypeEnum::WECHAT) {
                // 更新prepay_id记录
                WxappPrepayIdModel::updatePayStatus($this['order_id'], OrderTypeEnum::MASTER);
            }
        });
        return true;
    }

    /**
     * 购买指定商品成为分销商
     * @param $user_id
     * @param $goodsList
     * @param $wxapp_id
     * @return bool
     * @throws \think\exception\DbException
     */
    private function becomeDealerUser($user_id, $goodsList, $wxapp_id)
    {
        // 整理商品id集
        $goodsIds = [];
        foreach ($goodsList as $item) {
            $goodsIds[] = $item['goods_id'];
        }
        $model = new DealerApplyModel;
        return $model->becomeDealerUser($user_id, $goodsIds, $wxapp_id);
    }

}
