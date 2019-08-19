<?php

namespace app\store\model\recharge;

use app\common\model\recharge\Order as OrderModel;

/**
 * 用户充值订单模型
 * Class Order
 * @package app\store\model\recharge
 */
class Order extends OrderModel
{
    /**
     * 获取订单列表
     * @param array $query
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($query = [])
    {
        // 设置查询条件
        !empty($query) && $this->setQueryWhere($query);
        // 获取列表数据
        return $this->with(['user', 'order_plan'])
            ->alias('order')
            ->field('order.*')
            ->join('user', 'user.user_id = order.user_id')
            ->order(['order.create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => request()->request()
            ]);
    }

    /**
     * 设置查询条件
     * @param $query
     */
    private function setQueryWhere($query)
    {
        // 设置默认的检索数据
        $params = $this->setQueryDefaultValue($query, [
            'user_id' => 0,
            'recharge_type' => '-1',
            'pay_status' => '-1',
        ]);
        // 用户ID
        $params['user_id'] > 0 && $this->where('order.user_id', '=', $params['user_id']);
        // 用户昵称/订单号
        !empty($params['search']) && $this->where('order.order_no|user.nickName', 'like', "%{$params['search']}%");
        // 充值方式
        $params['recharge_type'] > -1 && $this->where('order.recharge_type', '=', (int)$params['recharge_type']);
        // 支付状态
        $params['pay_status'] > -1 && $this->where('order.pay_status', '=', (int)$params['pay_status']);
        // 起始时间
        !empty($params['start_time']) && $this->where('order.create_time', '>=', strtotime($params['start_time']));
        // 截止时间
        !empty($params['end_time']) && $this->where('order.create_time', '<', strtotime($params['end_time']) + 86400);
    }

}