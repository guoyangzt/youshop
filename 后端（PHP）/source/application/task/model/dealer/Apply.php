<?php

namespace app\task\model\dealer;

use app\common\model\dealer\Apply as ApplyModel;

/**
 * 分销商入驻申请模型
 * Class Apply
 * @package app\task\model\dealer
 */
class Apply extends ApplyModel
{
    /**
     * 购买指定商品成为分销商
     * @param $user_id
     * @param $goodsIds
     * @param $wxapp_id
     * @return bool
     * @throws \think\exception\DbException
     */
    public function becomeDealerUser($user_id, $goodsIds, $wxapp_id)
    {
        // 验证是否设置
        $config = Setting::getItem('condition', $wxapp_id);
        if ($config['become__buy_goods'] != '1' || empty($config['become__buy_goods_ids'])) {
            return false;
        }
        // 判断商品是否在设置范围内
        $intersect = array_intersect($goodsIds, $config['become__buy_goods_ids']);
        if (empty($intersect)) {
            return false;
        }
        // 新增分销商用户
        User::add($user_id, [
            'referee_id' => Referee::getRefereeUserId($user_id, 1),
            'wxapp_id' => $wxapp_id,
        ]);
        return true;
    }

}