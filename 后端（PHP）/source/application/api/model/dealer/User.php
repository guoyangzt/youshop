<?php

namespace app\api\model\dealer;

use app\common\model\dealer\User as UserModel;

/**
 * 分销商用户模型
 * Class User
 * @package app\api\model\dealer
 */
class User extends UserModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'create_time',
        'update_time',
    ];

    /**
     * 资金冻结
     * @param $money
     * @return false|int
     */
    public function freezeMoney($money)
    {
        return $this->save([
            'money' => $this['money'] - $money,
            'freeze_money' => $this['freeze_money'] + $money,
        ]);
    }

    /**
     * 累计分销商成员数量
     * @param $dealer_id
     * @param $level
     * @return int|true
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public static function setMemberInc($dealer_id, $level)
    {
        $fields = [1 => 'first_num', 2 => 'second_num', 3 => 'third_num'];
        $model = static::detail($dealer_id);
        return $model->setInc($fields[$level]);
    }

}