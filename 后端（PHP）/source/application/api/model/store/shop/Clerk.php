<?php

namespace app\api\model\store\shop;

use app\common\exception\BaseException;
use app\common\model\store\shop\Clerk as ClerkModel;

/**
 * 商家门店店员模型
 * Class Clerk
 * @package app\api\model\store\shop
 */
class Clerk extends ClerkModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'is_delete',
        'wxapp_id',
        'create_time',
        'update_time'
    ];

    /**
     * 店员详情
     * @param $where
     * @return static
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    public static function detail($where)
    {
        /* @var static $model */
        $model = parent::detail($where);
        if (!$model) {
            throw new BaseException(['msg' => '未找到店员信息']);
        }
        return $model;
    }

    /**
     * 验证用户是否为核销员
     * @param $shop_id
     * @return bool
     */
    public function checkUser($shop_id)
    {
        if ($this['is_delete']) {
            $this->error = '未找到店员信息';
            return false;
        }
        if ($this['shop_id'] != $shop_id) {
            $this->error = '当前店员不属于该门店，没有核销权限';
            return false;
        }
        if (!$this['status']) {
            $this->error = '当前店员状态已被禁用';
            return false;
        }
        return true;
    }

}