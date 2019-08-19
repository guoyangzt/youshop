<?php

namespace app\common\model\dealer;

use app\common\model\BaseModel;

/**
 * 分销商推荐关系模型
 * Class Referee
 * @package app\common\model\dealer
 */
class Referee extends BaseModel
{
    protected $name = 'dealer_referee';

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('app\api\model\User');
    }

    /**
     * 关联分销商用户表
     * @return \think\model\relation\BelongsTo
     */
    public function dealer()
    {
        return $this->belongsTo('User')->where('is_delete', '=', 0);
    }

    /**
     * 获取上级用户id
     * @param $user_id
     * @param $level
     * @param bool $is_dealer 必须是分销商
     * @return bool|mixed
     * @throws \think\exception\DbException
     */
    public static function getRefereeUserId($user_id, $level, $is_dealer = false)
    {
        $dealer_id = (new self)->where(compact('user_id', 'level'))
            ->value('dealer_id');
        if (!$dealer_id) return 0;
        return $is_dealer ? (User::isDealerUser($dealer_id) ? $dealer_id : 0) : $dealer_id;
    }

    /**
     * 获取我的团队列表
     * @param $user_id
     * @param int $level
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($user_id, $level = -1)
    {
        $level > -1 && $this->where('referee.level', '=', $level);
        return $this->with(['dealer', 'user'])
            ->alias('referee')
            ->field('referee.*')
            ->join('user', 'user.user_id = referee.user_id')
            ->where('referee.dealer_id', '=', $user_id)
            ->where('user.is_delete', '=', 0)
            ->order(['referee.create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => \request()->request()
            ]);
    }

}