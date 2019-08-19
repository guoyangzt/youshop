<?php

namespace app\task\model\dealer;

use app\common\model\dealer\Order as OrderModel;
use app\common\service\Order as OrderService;

/**
 * 分销商订单模型
 * Class Apply
 * @package app\task\model\dealer
 */
class Order extends OrderModel
{
    /**
     * 获取未结算的分销订单
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUnSettledList()
    {
        $list = $this->where('is_invalid', '=', 0)
            ->where('is_settled', '=', 0)
            ->select();
        if ($list->isEmpty()) {
            return $list;
        }
        // 整理订单信息
        $with = ['goods' => ['refund']];
        return OrderService::getOrderList($list, 'order_master', $with);
    }

    /**
     * 标记订单已失效(批量)
     * @param $ids
     * @return false|int
     */
    public function setInvalid($ids)
    {
        return $this->isUpdate(true)
            ->save(['is_invalid' => 1], ['id' => ['in', $ids]]);
    }

}