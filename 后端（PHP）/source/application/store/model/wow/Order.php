<?php

namespace app\store\model\wow;

use app\common\model\wow\Order as OrderModel;

/**
 * 好物圈订单同步记录模型
 * Class Order
 * @package app\store\model\wow
 */
class Order extends OrderModel
{
    /**
     * 获取列表
     * @param string $search
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($search = '')
    {
        $this->setBaseQuery($this->alias, [
            ['order', 'order_id'],
            ['user', 'user_id'],
        ]);
        // 检索查询条件
        if (!empty($search)) {
            $this->where(function ($query) use ($search) {
                $query->whereOr('order.order_no', 'like', "%{$search}%")
                    ->whereOr('user.nickName', 'like', "%{$search}%");
            });
        }
        // 返回列表数据
        return $this->with(['user'])
            ->field(['wow_order.*', 'order.order_no', 'order.pay_price'])
            ->where("{$this->alias}.is_delete", '=', 0)
            ->order(["{$this->alias}.create_time" => 'desc'])
            ->paginate(15, false, [
                'query' => request()->request()
            ]);
    }

}