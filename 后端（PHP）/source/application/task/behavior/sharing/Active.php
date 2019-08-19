<?php

namespace app\task\behavior\sharing;

use think\Cache;
use app\common\service\Message;
use app\task\model\sharing\Setting;
use app\task\model\sharing\Active as ActiveModel;
use app\task\model\sharing\Order as OrderModel;

/**
 * 拼团订单行为管理
 * Class Active
 * @package app\task\behavior
 */
class Active
{
    /* @var ActiveModel $model */
    private $model;

    /**
     * 执行函数
     * @param $model
     * @return bool
     */
    public function run($model)
    {
        if (!$model instanceof ActiveModel) {
            return new ActiveModel and false;
        }
        $this->model = $model;
        if (!$model::$wxapp_id) {
            return false;
        }
        if (!Cache::has('__task_space__sharing_active__' . $model::$wxapp_id)) {
            try {
                // 拼团设置
                $config = Setting::getItem('basic');
                // 已过期的拼单更新状态(拼单失败)
                $this->onUpdateActiveEnd();
                // 更新拼团失败的订单并退款
                if ($config['auto_refund'] == true) {
                    $this->onOrderRefund();
                }
            } catch (\Exception $e) {
            }
            Cache::set('__task_space__sharing_active__' . $model::$wxapp_id, time(), 10);
        }
        return true;
    }

    /**
     * 已过期的拼单更新状态
     * @return false|int
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function onUpdateActiveEnd()
    {
        // 获取已过期的拼单列表
        $list = $this->model->getEndedList();
        // 拼单ID集
        $activeIds = [];
        foreach ($list as $item) {
            $activeIds[] = $item['active_id'];
        }
        // 记录日志
        $this->dologs('onSetActiveEnd', [
            'activeIds' => json_encode($activeIds),
        ]);
        // 发送拼团失败模板消息
        $Message = new Message;
        foreach ($list as $item) {
            $Message->sharingActive($item, '拼团失败');
        }
        // 更新已过期状态
        return $this->model->updateEndedStatus($activeIds);
    }

    /**
     * 更新拼团失败的订单并退款
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function onOrderRefund()
    {
        // 实例化拼单订单模型
        $model = new OrderModel;
        // 每次最多处理的个数，防止运行太久
        // 及微信申请退款API请求频率限制：150qps
        $maxLimit = 100;
        // 获取拼团失败的订单集
        $orderList = $model->getFailedOrderList($maxLimit);
        // 整理拼团订单id
        $orderIds = [];
        foreach ($orderList as $order) {
            $orderIds[] = $order['order_id'];
        }
        // 记录日志
        $this->dologs('onOrderRefund', [
            'orderIds' => json_encode($orderIds),
        ]);
        if (empty($orderIds)) {
            return false;
        }
        // 更新拼团失败的订单并退款
        if ($model->updateFailedStatus($orderList)) {
            return true;
        }
        // 存在退款出错的订单记录日志
        $this->dologs('onOrderRefund', [
            'error: ' => $model->getError()
        ]);
        return false;
    }

    /**
     * 记录日志
     * @param $method
     * @param array $params
     * @return bool|int
     */
    private function dologs($method, $params = [])
    {
        $value = 'behavior sharing Active --' . $method;
        foreach ($params as $key => $val)
            $value .= ' --' . $key . ' ' . $val;
        return log_write($value);
    }

}
