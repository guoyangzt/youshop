<?php

namespace app\common\model\wxapp;

use app\common\model\BaseModel;

/**
 * form_id 模型
 * Class Apply
 * @package app\common\model\dealer
 */
class Formid extends BaseModel
{
    protected $name = 'wxapp_formid';

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\User");
    }

    /**
     * 获取一个可用的formid
     * @param $user_id
     * @return array|false|\PDOStatement|string|\think\Model|static
     */
    public static function getAvailable($user_id)
    {
        return (new static)->where([
            'user_id' => $user_id,
            'is_used' => 0,
            'expiry_time' => ['>=', time()]
        ])->order(['create_time' => 'asc'])->find();
    }

    /**
     * 设置为已使用
     * @return false|int
     */
    public function setIsUsed()
    {
        return $this->save(['is_used' => 1]);
    }

}