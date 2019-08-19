<?php

namespace app\task\model\sharing;

use app\common\model\sharing\Order as OrderModel;

use app\task\model\User as UserModel;
use app\task\model\sharing\Goods as GoodsModel;
use app\task\model\user\BalanceLog as BalanceLogModel;
use app\task\model\WxappPrepayId as WxappPrepayIdModel;

use app\common\service\Message as MessageService;
use app\common\service\order\Printer as Printerservice;
use app\common\service\order\Refund as RefundService;

use app\common\enum\order\PayType as PayTypeEnum;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\OrderStatus as OrderStatusEnum;
use app\common\enum\user\balanceLog\Scene as SceneEnum;

/**
 * 拼团订单模型
 * Class Order
 * @package app\common\model\sharing
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
            (new MessageService)->payment($this, OrderTypeEnum::SHARING);
            // 小票打印
            (new Printerservice)->printTicket($this, OrderStatusEnum::ORDER_PAYMENT);
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
     * @param $payType
     * @param $payData
     * @return bool
     * @throws \think\exception\DbException
     */
    private function updatePayStatus($payType, $payData)
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
            // 更新拼单记录
            $this->saveSharingActive($this['goods'][0]);
            // 更新订单状态
            $order = ['pay_type' => $payType, 'pay_status' => 20, 'pay_time' => time()];
            if ($payType == PayTypeEnum::WECHAT) {
                $order['transaction_id'] = $payData['transaction_id'];
            }
            $this->save($order);
            // 累积用户总消费金额
            $user->setIncPayMoney($this['pay_price']);
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
                WxappPrepayIdModel::updatePayStatus($this['order_id'], OrderTypeEnum::SHARING);
            }
        });
        return true;
    }

    /**
     * 更新拼单记录
     * @param $goods
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    private function saveSharingActive($goods)
    {
        // 新增/更新拼单记录
        if ($this['order_type']['value'] != 20) {
            return false;
        }
        // 参与他人的拼单, 更新拼单记录
        if ($this['active_id'] > 0) {
            $ActiveModel = Active::detail($this['active_id']);
            return $ActiveModel->onUpdate($this['user_id'], $this['order_id']);
        }
        // 自己发起的拼单, 新增拼单记录
        $ActiveModel = new Active;
        $ActiveModel->onCreate($this['user_id'], $this['order_id'], $goods);
        // 记录拼单id
        $this['active_id'] = $ActiveModel['active_id'];
        return true;
    }

    /**
     * 获取拼团失败的订单
     * @param int $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getFailedOrderList($limit = 100)
    {
        return $this->alias('order')
            ->join('sharing_active active', 'order.active_id = active.active_id', 'INNER')
            ->where('order_type', '=', 20)
            ->where('pay_status', '=', 20)
            ->where('order_status', '=', 10)
            ->where('active.status', '=', 30)
            ->where('is_refund', '=', 0)
            ->where('order.is_delete', '=', 0)
            ->limit($limit)
            ->select();
    }

    /**
     * 更新拼团失败的订单并退款
     * @param $orderList
     * @return bool
     */
    public function updateFailedStatus($orderList)
    {
        // 批量更新订单状态
        foreach ($orderList as $order) {
            /* @var static $order */
            try {
                // 执行退款操作
                (new RefundService)->execute($order);
                // 更新订单状态
                $order->save([
                    'is_refund' => 1,
                    'order_status' => '20'
                ]);
            } catch (\Exception $e) {
                $this->error = '订单ID：' . $order['order_id'] . ' 退款失败，错误信息：' . $e->getMessage();
                return false;
            }
        }
        return true;
    }

}
