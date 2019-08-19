<?php

namespace app\store\model\store\shop;

use app\common\model\store\shop\Order as OrderModel;
use app\common\service\Order as OrderService;

/**
 * 商家门店核销订单记录模型
 * Class Order
 * @package app\store\model\store\shop
 */
class Order extends OrderModel
{
    /**
     * 获取列表数据
     * @param int $shop_id 门店id
     * @param string $search 店员姓名/手机号
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($shop_id = 0, $search = '')
    {
        // 检索查询条件
        $shop_id > 0 && $this->where('clerk.shop_id', '=', (int)$shop_id);
        !empty($search) && $this->where('clerk.real_name', 'like', "%{$search}%");
        // 查询列表数据
        $data = $this->with(['shop', 'clerk'])
            ->alias('order')
            ->field(['order.*'])
            ->join('store_shop_clerk clerk', 'clerk.clerk_id = order.clerk_id', 'INNER')
            ->order(['order.create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => \request()->request()
            ]);
        if ($data->isEmpty()) {
            return $data;
        }
        // 整理订单信息
        return OrderService::getOrderList($data);
    }

}