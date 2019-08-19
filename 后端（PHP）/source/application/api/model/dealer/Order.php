<?php

namespace app\api\model\dealer;

use app\common\model\dealer\Order as OrderModel;
use app\common\service\Order as OrderService;

/**
 * 分销商订单模型
 * Class Apply
 * @package app\api\model\dealer
 */
class Order extends OrderModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'update_time',
    ];

    /**
     * 获取分销商订单列表
     * @param $user_id
     * @param int $is_settled
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($user_id, $is_settled = -1)
    {
        $is_settled > -1 && $this->where('is_settled', '=', !!$is_settled);
        $data = $this->with(['user'])
            ->where('first_user_id|second_user_id|third_user_id', '=', $user_id)
            ->order(['create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => \request()->request()
            ]);
        if ($data->isEmpty()) {
            return $data;
        }
        // 整理订单信息
        $with = ['goods' => ['image', 'refund'], 'address', 'user'];
        return OrderService::getOrderList($data, 'order_master', $with);
    }

    /**
     * 创建分销商订单记录
     * @param $order
     * @param int $order_type 订单类型 (10商城订单 20拼团订单)
     * @return bool|false|int
     * @throws \think\exception\DbException
     */
    public static function createOrder(&$order, $order_type = 10)
    {
        // 分销订单模型
        $model = new self;
        // 分销商基本设置
        $setting = Setting::getItem('basic');
        // 是否开启分销功能
        if (!$setting['is_open']) {
            return false;
        }
        // 获取当前买家的所有上级分销商用户id
        $dealerUser = $model->getDealerUserId($order['user_id'], $setting['level'], $setting['self_buy']);
        // 非分销订单
        if (!$dealerUser['first_user_id']) {
            return false;
        }
        // 计算订单分销佣金
        $capital = $model->getCapitalByOrder($order);
        // 保存分销订单记录
        return $model->save([
            'user_id' => $order['user_id'],
            'order_id' => $order['order_id'],
            'order_type' => $order_type,
            // 'order_no' => $order['order_no'],  // 废弃
            'order_price' => $capital['orderPrice'],
            'first_money' => max($capital['first_money'], 0),
            'second_money' => max($capital['second_money'], 0),
            'third_money' => max($capital['third_money'], 0),
            'first_user_id' => $dealerUser['first_user_id'],
            'second_user_id' => $dealerUser['second_user_id'],
            'third_user_id' => $dealerUser['third_user_id'],
            'is_settled' => 0,
            'wxapp_id' => $model::$wxapp_id
        ]);
    }

    /**
     * 获取当前买家的所有上级分销商用户id
     * @param $user_id
     * @param $level
     * @param $self_buy
     * @return mixed
     * @throws \think\exception\DbException
     */
    private function getDealerUserId($user_id, $level, $self_buy)
    {
        $dealerUser = [
            'first_user_id' => $level >= 1 ? Referee::getRefereeUserId($user_id, 1, true) : 0,
            'second_user_id' => $level >= 2 ? Referee::getRefereeUserId($user_id, 2, true) : 0,
            'third_user_id' => $level == 3 ? Referee::getRefereeUserId($user_id, 3, true) : 0
        ];
        // 分销商自购
        if ($self_buy && User::isDealerUser($user_id)) {
            return [
                'first_user_id' => $user_id,
                'second_user_id' => $dealerUser['first_user_id'],
                'third_user_id' => $dealerUser['second_user_id'],
            ];
        }
        return $dealerUser;
    }

}
