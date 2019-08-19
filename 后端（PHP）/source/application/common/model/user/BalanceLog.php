<?php

namespace app\common\model\user;

use app\common\model\BaseModel;
use app\common\enum\user\balanceLog\Scene as SceneEnum;

/**
 * 用户余额变动明细模型
 * Class BalanceLog
 * @package app\common\model\user
 */
class BalanceLog extends BaseModel
{
    protected $name = 'user_balance_log';
    protected $updateTime = false;

    /**
     * 获取当前模型属性
     * @return array
     */
    public static function getAttributes()
    {
        return [
            // 充值方式
            'scene' => SceneEnum::data(),
        ];
    }

    /**
     * 关联会员记录表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('app\common\model\User');
    }

    /**
     * 余额变动场景
     * @param $value
     * @return array
     */
    public function getSceneAttr($value)
    {
        return ['text' => SceneEnum::data()[$value]['name'], 'value' => $value];
    }

    /**
     * 新增记录
     * @param $scene
     * @param $data
     * @param $describeParam
     */
    public static function add($scene, $data, $describeParam)
    {
        $model = new static;
        $model->save(array_merge([
            'scene' => $scene,
            'describe' => vsprintf(SceneEnum::data()[$scene]['describe'], $describeParam),
            'wxapp_id' => $model::$wxapp_id
        ], $data));
    }

}