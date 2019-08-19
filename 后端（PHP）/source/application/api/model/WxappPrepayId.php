<?php

namespace app\api\model;

use app\common\model\WxappPrepayId as WxappPrepayIdModel;
use app\common\enum\OrderType as OrderTypeEnum;

/**
 * 小程序prepay_id模型
 * Class WxappPrepayId
 * @package app\api\model
 */
class WxappPrepayId extends WxappPrepayIdModel
{
    /**
     * 新增记录
     * @param $prepayId
     * @param $orderId
     * @param $userId
     * @param int $orderType
     * @return false|int
     */
    public function add($prepayId, $orderId, $userId, $orderType = OrderTypeEnum::MASTER)
    {
        return $this->save([
            'prepay_id' => $prepayId,
            'order_id' => $orderId,
            'order_type' => $orderType,
            'user_id' => $userId,
            'can_use_times' => 0,
            'used_times' => 0,
            'expiry_time' => time() + (7 * 86400),
            'wxapp_id' => self::$wxapp_id,
        ]);
    }

}