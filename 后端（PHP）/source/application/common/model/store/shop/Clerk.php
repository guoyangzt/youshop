<?php

namespace app\common\model\store\shop;

use app\common\model\BaseModel;

/**
 * 商家门店店员模型
 * Class Clerk
 * @package app\common\model\store
 */
class Clerk extends BaseModel
{
    protected $name = 'store_shop_clerk';

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        $module = static::getCalledModule() ?: 'common';
        return $this->BelongsTo("app\\{$module}\\model\\User");
    }

    /**
     * 关联门店表
     * @return \think\model\relation\BelongsTo
     */
    public function shop()
    {
        $module = static::getCalledModule() ?: 'common';
        return $this->BelongsTo("app\\{$module}\\model\\store\\Shop");
    }

    /**
     * 店员详情
     * @param $where
     * @return static|null
     * @throws \think\exception\DbException
     */
    public static function detail($where)
    {
        $filter = is_array($where) ? $where : ['clerk_id' => $where];
        return static::get(array_merge(['is_delete' => 0], $filter));
    }

}