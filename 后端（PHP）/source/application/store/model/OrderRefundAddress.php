<?php

namespace app\store\model;

use app\common\model\OrderRefundAddress as OrderRefundAddressModel;

/**
 * 售后单退货地址模型
 * Class OrderRefundAddress
 * @package app\store\model
 */
class OrderRefundAddress extends OrderRefundAddressModel
{
    /**
     * 新增售后单退货地址记录
     * @param $order_refund_id
     * @param $address_id
     * @return false|int
     * @throws \think\exception\DbException
     */
    public function add($order_refund_id, $address_id)
    {
        $detail = ReturnAddress::detail($address_id);
        return $this->save([
            'order_refund_id' => $order_refund_id,
            'name' => $detail['name'],
            'phone' => $detail['phone'],
            'detail' => $detail['detail'],
            'wxapp_id' => self::$wxapp_id
        ]);
    }

}