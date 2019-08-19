<?php

namespace app\api\model\recharge;

use app\common\model\recharge\Order as OrderModel;

use app\api\model\Setting as SettingModel;
use app\api\model\recharge\Plan as PlanModel;
use app\api\model\recharge\OrderPlan as OrderPlanModel;

use app\common\service\Order as OrderService;
use app\common\enum\recharge\order\PayStatus as PayStatusEnum;
use app\common\enum\recharge\order\RechargeType as RechargeTypeEnum;
use app\common\exception\BaseException;

/**
 * 用户充值订单模型
 * Class Order
 * @package app\api\model\recharge
 */
class Order extends OrderModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
    ];

    /**
     * 获取订单列表
     * @param $userId
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($userId)
    {
        // 获取列表数据
        return $this->where('user_id', '=', $userId)
            ->where('pay_status', '=', PayStatusEnum::SUCCESS)
            ->order(['create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => request()->request()
            ]);
    }

    /**
     * 创建充值订单
     * @param \app\api\model\User $user 当前用户信息
     * @param int $planId 套餐id
     * @param double $customMoney 自定义充值金额
     * @return bool|false|int
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    public function createOrder($user, $planId = null, $customMoney = 0.00)
    {
        // 确定充值方式
        $rechargeType = $planId > 0 ? RechargeTypeEnum::PLAN : RechargeTypeEnum::CUSTOM;
        // 验证用户输入
        if (!$this->validateForm($rechargeType, $planId, $customMoney)) {
            $this->error = $this->error ?: '数据验证错误';
            return false;
        }
        // 获取订单数据
        $data = $this->getOrderData($user, $rechargeType, $planId, $customMoney);
        // 记录订单信息
        return $this->saveOrder($data);
    }

    /**
     * 保存订单记录
     * @param $data
     * @return bool|false|int
     */
    private function saveOrder($data)
    {
        // 写入订单记录
        $this->save($data['order']);
        // 记录订单套餐快照
        if (!empty($data['plan'])) {
            $PlanModel = new OrderPlanModel;
            return $PlanModel->add($this['order_id'], $data['plan']);
        }
        return true;
    }

    /**
     * 生成充值订单
     * @param $user
     * @param $rechargeType
     * @param $planId
     * @param $customMoney
     * @return array
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    private function getOrderData($user, $rechargeType, $planId, $customMoney)
    {
        // 订单信息
        $data = [
            'order' => [
                'user_id' => $user['user_id'],
                'order_no' => 'RC' . OrderService::createOrderNo(),
                'recharge_type' => $rechargeType,
                'gift_money' => 0.00,
                'wxapp_id' => self::$wxapp_id,
            ],
            'plan' => []    // 订单套餐快照
        ];
        // 自定义金额充值
        if ($rechargeType == RechargeTypeEnum::CUSTOM) {
            $this->createDataByCustom($data, $customMoney);
        }
        // 套餐充值
        if ($rechargeType == RechargeTypeEnum::PLAN) {
            $this->createDataByPlan($data, $planId);
        }
        // 实际到账金额
        $data['order']['actual_money'] = bcadd($data['order']['pay_price'], $data['order']['gift_money'], 2);
        return $data;
    }

    /**
     * 创建套餐充值订单数据
     * @param $order
     * @param $planId
     * @return bool
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    private function createDataByPlan(&$order, $planId)
    {
        // 获取套餐详情
        $planInfo = PlanModel::detail($planId);
        if (empty($planInfo)) {
            throw new BaseException(['msg' => '充值套餐不存在']);
        }
        $order['plan'] = $planInfo;
        $order['order']['plan_id'] = $planInfo['plan_id'];
        $order['order']['gift_money'] = $planInfo['gift_money'];
        $order['order']['pay_price'] = $planInfo['money'];
        return true;
    }

    /**
     * 创建自定义充值订单数据
     * @param $order
     * @param $customMoney
     * @return bool
     */
    private function createDataByCustom(&$order, $customMoney)
    {
        // 用户支付金额
        $order['order']['pay_price'] = $customMoney;
        // 充值设置
        $setting = SettingModel::getItem('recharge');
        if ($setting['is_custom'] == false) {
            return true;
        }
        // 根据自定义充值金额匹配满足的套餐
        $PlanModel = new PlanModel;
        $matchPlanInfo = $PlanModel->getMatchPlan($customMoney);
        if (!empty($matchPlanInfo)) {
            $order['plan'] = $matchPlanInfo;
            $order['order']['plan_id'] = $matchPlanInfo['plan_id'];
            $order['order']['gift_money'] = $matchPlanInfo['gift_money'];
        }
        return true;
    }

    /**
     * 表单验证
     * @param $rechargeType
     * @param $planId
     * @param $customMoney
     * @return bool
     */
    private function validateForm($rechargeType, $planId, $customMoney)
    {
        if (empty($planId) && $customMoney <= 0) {
            $this->error = '请选择充值套餐或输入充值金额';
            return false;
        }
        // 验证自定义的金额
        if ($rechargeType == RechargeTypeEnum::CUSTOM && $customMoney <= 0) {
            $this->error = '请选择充值套餐或输入充值金额';
            return false;
        }
        return true;
    }

}