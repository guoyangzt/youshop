<?php

namespace app\task\model;

use app\common\model\WxappPrepayId as WxappPrepayIdModel;
use app\common\enum\OrderType as OrderTypeEnum;

/**
 * 小程序prepay_id模型
 * Class WxappPrepayId
 * @package app\task\model
 */
class WxappPrepayId extends WxappPrepayIdModel
{
    /**
     * 更新prepay_id已付款状态
     * @param $orderId
     * @param $orderType
     * @return false|int
     */
    public static function updatePayStatus($orderId, $orderType = OrderTypeEnum::MASTER)
    {
        // 获取prepay_id记录
        $model = static::detail($orderId, $orderType);
        if (empty($model)) {
            return false;
        }
        // 更新记录
        return $model->save(['can_use_times' => 3, 'pay_status' => 1]);
    }

}