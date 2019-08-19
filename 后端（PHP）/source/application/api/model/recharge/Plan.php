<?php

namespace app\api\model\recharge;

use app\common\model\recharge\Plan as PlanModel;

/**
 * 用户充值订单模型
 * Class Plan
 * @package app\api\model\recharge
 */
class Plan extends PlanModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'is_delete',
        'wxapp_id',
        'create_time',
        'update_time',
    ];

    /**
     * 获取器：充值金额
     * @param $value
     * @return int
     */
    public function getMoneyAttr($value)
    {
        return ($value == $intValue = (int)$value) ? $intValue : $value;
    }

    /**
     * 获取器：赠送金额
     * @param $value
     * @return int
     */
    public function getGiftMoneyAttr($value)
    {
        return ($value == $intValue = (int)$value) ? $intValue : $value;
    }

    /**
     * 获取可用的充值套餐列表
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        // 获取列表数据
        return $this->where('is_delete', '=', 0)
            ->order(['sort' => 'asc', 'money' => 'desc', 'create_time' => 'desc'])
            ->select();
    }

    /**
     * 根据自定义充值金额匹配满足的套餐
     * @param $payPrice
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getMatchPlan($payPrice)
    {
        return (new static)->where('money', '<=', $payPrice)
            ->where('is_delete', '=', 0)
            ->order(['money' => 'desc'])
            ->find();
    }

}