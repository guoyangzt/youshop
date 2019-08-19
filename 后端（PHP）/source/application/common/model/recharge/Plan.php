<?php

namespace app\common\model\recharge;

use app\common\model\BaseModel;

/**
 * 用户充值订单模型
 * Class Plan
 * @package app\common\model\recharge
 */
class Plan extends BaseModel
{
    protected $name = 'recharge_plan';

    /**
     * 充值套餐详情
     * @param $plan_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($plan_id)
    {
        return self::get($plan_id);
    }

}