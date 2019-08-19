<?php

namespace app\store\model\user;

use app\common\model\user\BalanceLog as BalanceLogModel;

/**
 * 用户余额变动明细模型
 * Class BalanceLog
 * @package app\store\model\user
 */
class BalanceLog extends BalanceLogModel
{
    /**
     * 获取订单列表
     * @param array $query
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($query = [])
    {
        // 设置查询条件
        !empty($query) && $this->setQueryWhere($query);
        // 获取列表数据
        return $this->with(['user'])
            ->alias('log')
            ->field('log.*')
            ->join('user', 'user.user_id = log.user_id')
            ->order(['log.create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => request()->request()
            ]);
    }

    /**
     * 设置查询条件
     * @param $query
     */
    private function setQueryWhere($query)
    {
        // 设置默认的检索数据
        $params = $this->setQueryDefaultValue($query, [
            'user_id' => 0,
            'scene' => -1,
            'recharge_type' => -1,
            'pay_status' => -1,
        ]);
        // 用户ID
        $params['user_id'] > 0 && $this->where('log.user_id', '=', $params['user_id']);
        // 用户昵称/订单号
        !empty($params['search']) && $this->where('user.nickName', 'like', "%{$params['search']}%");
        // 余额变动场景
        $params['scene'] > -1 && $this->where('log.scene', '=', (int)$params['scene']);
        // 充值方式
        $params['recharge_type'] > -1 && $this->where('log.recharge_type', '=', (int)$params['recharge_type']);
        // 支付状态
        $params['pay_status'] > -1 && $this->where('log.pay_status', '=', (int)$params['pay_status']);
        // 起始时间
        !empty($params['start_time']) && $this->where('log.create_time', '>=', strtotime($params['start_time']));
        // 截止时间
        !empty($params['end_time']) && $this->where('log.create_time', '<', strtotime($params['end_time']) + 86400);
    }

}