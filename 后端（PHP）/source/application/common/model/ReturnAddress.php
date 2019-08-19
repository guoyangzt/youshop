<?php

namespace app\common\model;

/**
 * 退货地址模型
 * Class ReturnAddress
 * @package app\common\model
 */
class ReturnAddress extends BaseModel
{
    protected $name = 'return_address';

    /**
     * 退货地址详情
     * @param $address_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($address_id)
    {
        return self::get($address_id);
    }

}