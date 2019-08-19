<?php

namespace app\store\model\dealer;

use app\common\model\dealer\Order as OrderModel;
use app\common\service\Order as OrderService;

/**
 * 分销商订单模型
 * Class Apply
 * @package app\store\model\dealer
 */
class Order extends OrderModel
{
    /**
     * 获取分销商订单列表
     * @param null $user_id
     * @param int $is_settled
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($user_id = null, $is_settled = -1)
    {
        // 检索查询条件
        $user_id > 1 && $this->where('first_user_id|second_user_id|third_user_id', '=', $user_id);
        $is_settled > -1 && $this->where('is_settled', '=', !!$is_settled);
        !empty($search) && $this->where('user.nickName', 'like', "%{$search}%");
        // 获取分销商订单列表
        $data = $this->with([
            'dealer_first.user',
            'dealer_second.user',
            'dealer_third.user'
        ])
            ->order(['create_time' => 'desc'])
            ->paginate(10, false, [
                'query' => \request()->request()
            ]);
        if ($data->isEmpty()) {
            return $data;
        }
        // 获取订单的主信息
        $with = ['goods' => ['image', 'refund'], 'address', 'user'];
        return OrderService::getOrderList($data, 'order_master', $with);
    }

}