<?php

namespace app\api\controller;

use app\api\model\Setting as SettingModel;
use app\api\model\recharge\Plan as PlanModel;
use app\api\model\recharge\Order as OrderModel;
use app\api\service\Payment as PaymentService;
use app\common\enum\OrderType as OrderTypeEnum;

/**
 * 用户充值管理
 * Class Recharge
 * @package app\api\controller
 */
class Recharge extends Controller
{
    /**
     * 充值中心
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        // 用户信息
        $userInfo = $this->getUser();
        // 充值套餐列表
        $planList = (new PlanModel)->getList();
        // 充值设置
        $setting = SettingModel::getItem('recharge');
        return $this->renderSuccess(compact('userInfo', 'planList', 'setting'));
    }

    /**
     * 确认充值
     * @param null $planId
     * @param int $customMoney
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function submit($planId = null, $customMoney = 0)
    {
        // 用户信息
        $userInfo = $this->getUser();
        // 生成充值订单
        $model = new OrderModel;
        if (!$model->createOrder($userInfo, $planId, $customMoney)) {
            return $this->renderError($model->getError() ?: '充值失败');
        }
        // 构建微信支付
        $payment = PaymentService::wechat(
            $userInfo,
            $model['order_id'],
            $model['order_no'],
            $model['pay_price'],
            OrderTypeEnum::RECHARGE
        );
        // 充值状态提醒
        $message = ['success' => '充值成功', 'error' => '订单未支付'];
        return $this->renderSuccess(compact('payment', 'message'), $message);
    }

}