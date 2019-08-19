<?php

namespace app\store\model;

use app\common\model\OrderRefund as OrderRefundModel;

use app\store\model\User as UserModel;
use app\common\service\Message as MessageService;
use app\common\service\order\Refund as RefundService;
use app\common\enum\OrderType as OrderTypeEnum;

/**
 * 售后单模型
 * Class OrderRefund
 * @package app\api\model
 */
class OrderRefund extends OrderRefundModel
{
    /**
     * 获取售后单列表
     * @param array $query
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($query = [])
    {
        // 查询条件：订单号
        if (isset($query['order_no']) && !empty($query['order_no'])) {
            $this->where('order.order_no', 'like', "%{$query['order_no']}%");
        }
        // 查询条件：起始日期
        if (isset($query['start_time']) && !empty($query['start_time'])) {
            $this->where('m.create_time', '>=', strtotime($query['start_time']));
        }
        // 查询条件：截止日期
        if (isset($query['end_time']) && !empty($query['end_time'])) {
            $this->where('m.create_time', '<', strtotime($query['end_time']) + 86400);
        }
        // 售后类型
        if (isset($query['type']) && $query['type'] > 0) {
            $this->where('m.type', '=', $query['type']);
        }
        // 处理状态
        if (isset($query['state']) && is_numeric($query['state'])) {
            $this->where('m.status', '=', $query['state']);
        }
        // 获取列表数据
        return $this->alias('m')
            ->field('m.*, order.order_no')
            ->with(['order_goods.image', 'orderMaster', 'user'])
            ->join('order', 'order.order_id = m.order_id')
            ->order(['m.create_time' => 'desc'])
            ->paginate(10, false, [
                'query' => \request()->request()
            ]);
    }

    /**
     * 商家审核
     * @param $data
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function audit($data)
    {
        if ($data['is_agree'] == 20 && empty($data['refuse_desc'])) {
            $this->error = '请输入拒绝原因';
            return false;
        }
        if ($data['is_agree'] == 10 && empty($data['address_id'])) {
            $this->error = '请选择退货地址';
            return false;
        }
        $this->startTrans();
        try {
            // 拒绝申请, 标记售后单状态为已拒绝
            $data['is_agree'] == 20 && $data['status'] = 10;
            // 同意换货申请, 标记售后单状态为已完成
            $data['is_agree'] == 10 && $this['type']['value'] == 20 && $data['status'] = 20;
            // 更新退款单状态
            $this->allowField(true)->save($data);
            // 同意售后申请, 记录退货地址
            if ($data['is_agree'] == 10) {
                $model = new OrderRefundAddress;
                $model->add($this['order_refund_id'], $data['address_id']);
            }
            // 订单详情
            $order = Order::detail($this['order_id']);
            // 发送模板消息
            (new MessageService)->refund(self::detail($this['order_refund_id']), $order['order_no'], OrderTypeEnum::MASTER);
            // 事务提交
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 确认收货并退款
     * @param $data
     * @return bool
     * @throws \think\exception\DbException
     */
    public function receipt($data)
    {
        // 订单详情
        $order = Order::detail($this['order_id']);
        if ($data['refund_money'] > min($order['pay_price'], $this['order_goods']['total_pay_price'])) {
            $this->error = '退款金额不能大于商品实付款金额';
            return false;
        }
        $this->transaction(function () use ($order, $data) {
            // 更新售后单状态
            $this->allowField(true)->save([
                'refund_money' => $data['refund_money'],
                'is_receipt' => 1,
                'status' => 20
            ]);
            // 消减用户的实际消费金额
            // 条件：判断订单是否已统计
            if ($order['is_user_expend'] == true) {
                (new UserModel)->setDecUserExpend($order['user_id'], $data['refund_money']);
            }
            // 执行原路退款
            (new RefundService)->execute($order, $data['refund_money']);
            // 发送模板消息
            (new MessageService)->refund(self::detail($this['order_refund_id']), $order['order_no'], OrderTypeEnum::MASTER);
        });
        return true;
    }

}