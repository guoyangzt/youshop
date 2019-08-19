<?php

namespace app\api\model\wxapp;

use app\common\model\wxapp\Formid as FormidModel;

/**
 * form_id 模型
 * Class Formid
 * @package app\api\model\wxapp
 */
class Formid extends FormidModel
{
    /**
     * 新增form_id
     * @param $user_id
     * @param $form_id
     * @return false|int
     */
    public static function add($user_id, $form_id)
    {
        $model = new self;
        return $model->save([
            'user_id' => $user_id,
            'form_id' => $form_id,
            'expiry_time' => time() + (7 * 86400) - 10,
            'wxapp_id' => self::$wxapp_id
        ]);
    }
}