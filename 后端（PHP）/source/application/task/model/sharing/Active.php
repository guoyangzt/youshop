<?php

namespace app\task\model\sharing;

use app\common\service\Message;
use app\common\model\sharing\Active as ActiveModel;

/**
 * 拼团拼单模型
 * Class Active
 * @package app\task\model\sharing
 */
class Active extends ActiveModel
{
    /**
     * 新增拼单记录
     * @param $creator_id
     * @param $order_id
     * @param OrderGoods $goods
     * @return false|int
     */
    public function onCreate($creator_id, $order_id, $goods)
    {
        // 新增拼单记录
        $this->save([
            'goods_id' => $goods['goods_id'],
            'people' => $goods['people'],
            'actual_people' => 1,
            'creator_id' => $creator_id,
            'end_time' => time() + ($goods['group_time'] * 60 * 60),
            'status' => 10,
            'wxapp_id' => $goods['wxapp_id']
        ]);
        // 新增拼单成员记录
        ActiveUsers::add([
            'active_id' => $this['active_id'],
            'order_id' => $order_id,
            'user_id' => $creator_id,
            'is_creator' => 1,
            'wxapp_id' => $goods['wxapp_id']
        ]);
        return true;
    }

    /**
     * 更新拼单记录
     * @param $user_id
     * @param $order_id
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function onUpdate($user_id, $order_id)
    {
        // 验证当前拼单是否允许加入新成员
        if (!$this->checkAllowJoin()) {
            return false;
        }
        // 新增拼单成员记录
        ActiveUsers::add([
            'active_id' => $this['active_id'],
            'order_id' => $order_id,
            'user_id' => $user_id,
            'is_creator' => 0,
            'wxapp_id' => $this['wxapp_id']
        ]);
        // 累计已拼人数
        $actual_people = $this['actual_people'] + 1;
        // 更新拼单记录：当前已拼人数、拼单状态
        $status = $actual_people >= $this['people'] ? 20 : 10;
        $this->save([
            'actual_people' => $actual_people,
            'status' => $status
        ]);
        // 拼单成功, 发送模板消息
        if ($status == 20) {
            $model = static::detail($this['active_id']);
            (new Message)->sharingActive($model, '拼团成功');
        }
        return true;
    }

    /**
     * 获取已过期的拼单列表
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getEndedList()
    {
        return $this->with(['goods', 'users' => ['user', 'sharingOrder']])
            ->where('end_time', '<=', time())
            ->where('status', '=', 10)
            ->select();
    }

    /**
     * 设置拼单失败状态
     * @param $activeIds
     * @return false|int
     */
    public function updateEndedStatus($activeIds)
    {
        if (empty($activeIds)) {
            return false;
        }
        return $this->save(['status' => 30], ['active_id' => ['in', $activeIds]]);
    }

}
