<?php

namespace app\common\model\dealer;

use app\common\model\BaseModel;

/**
 * 分销商用户模型
 * Class Apply
 * @package app\common\model\dealer
 */
class User extends BaseModel
{
    protected $name = 'dealer_user';

    /**
     * 关联会员记录表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('app\common\model\User');
    }

    /**
     * 关联推荐人表
     * @return \think\model\relation\BelongsTo
     */
    public function referee()
    {
        return $this->belongsTo('app\common\model\User', 'referee_id')
            ->field(['user_id', 'nickName']);
    }

    /**
     * 获取分销商用户信息
     * @param $user_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($user_id)
    {
        return self::get($user_id, ['user', 'referee']);
    }

    /**
     * 是否为分销商
     * @param $user_id
     * @return bool
     * @throws \think\exception\DbException
     */
    public static function isDealerUser($user_id)
    {
        $dealer = self::detail($user_id);
        return !!$dealer && !$dealer['is_delete'];
    }

    /**
     * 新增分销商用户记录
     * @param $user_id
     * @param $data
     * @return false|int
     * @throws \think\exception\DbException
     */
    public static function add($user_id, $data)
    {
        $model = static::detail($user_id) ?: new static;
        return $model->save(array_merge([
            'user_id' => $user_id,
            'is_delete' => 0,
            'wxapp_id' => $model::$wxapp_id
        ], $data));
    }

    /**
     * 发放分销商佣金
     * @param $user_id
     * @param $money
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public static function grantMoney($user_id, $money)
    {
        // 分销商详情
        $model = static::detail($user_id);
        if (!$model || $model['is_delete']) {
            return false;
        }
        // 累积分销商可提现佣金
        $model->setInc('money', $money);
        // 记录分销商资金明细
        Capital::add([
            'user_id' => $user_id,
            'flow_type' => 10,
            'money' => $money,
            'describe' => '订单佣金结算',
            'wxapp_id' => $model['wxapp_id'],
        ]);
        return true;
    }

}