<?php

namespace app\store\model\sharing;

use app\common\model\sharing\ActiveUsers as ActiveUsersModel;

/**
 * 拼团拼单成员模型
 * Class ActiveUsers
 * @package app\store\model\sharing
 */
class ActiveUsers extends ActiveUsersModel
{
    /**
     * 获取拼单成员列表
     * @param $active_id
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($active_id)
    {
        return $this->with(['sharingOrder.address', 'user'])
            ->where('active_id', '=', $active_id)
            ->order(['create_time' => 'asc'])
            ->paginate(15, false, [
                'query' => request()->request()
            ]);
    }

}
