<?php

namespace app\api\model\recharge;

use app\common\model\recharge\OrderPlan as OrderPlanModel;

/**
 * 用户充值订单套餐快照模型
 * Class OrderPlan
 * @package app\api\model\recharge
 */
class OrderPlan extends OrderPlanModel
{
    /**
     * 新增记录
     * @param $orderId
     * @param $data
     * @return false|int
     */
    public function add($orderId, $data)
    {
        return $this->save([
            'order_id' => $orderId,
            'plan_id' => $data['plan_id'],
            'plan_name' => $data['plan_name'],
            'money' => $data['money'],
            'gift_money' => $data['gift_money'],
            'wxapp_id' => self::$wxapp_id
        ]);
    }

}