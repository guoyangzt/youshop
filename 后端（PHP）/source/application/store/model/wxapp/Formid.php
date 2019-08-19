<?php

namespace app\store\model\wxapp;

use app\common\model\wxapp\Formid as FormidModel;

/**
 * form_id 模型
 * Class Formid
 * @package app\store\model\wxapp
 */
class Formid extends FormidModel
{
    /**
     * 获取活跃用户列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getUserList()
    {
        return $this->with(['user'])
            ->field(['user_id', 'count(id) AS total_formid'])
            ->where('is_used', '=', 0)
            ->where('expiry_time', '>', time())
            ->group('user_id')
            ->order(['total_formid' => 'desc'])
            ->paginate(15, false, [
                'query' => request()->request()
            ]);
    }

}