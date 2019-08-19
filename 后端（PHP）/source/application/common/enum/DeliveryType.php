<?php

namespace app\common\enum;

/**
 * 配送方式枚举类
 * Class DeliveryType
 * @package app\common\enum
 */
class DeliveryType extends EnumBasics
{
    // 快递配送
    const EXPRESS = 10;

    // 上门自提
    const EXTRACT = 20;

    /**
     * 获取枚举数据
     * @return array
     */
    public static function data()
    {
        return [
            self::EXPRESS => [
                'name' => '快递配送',
                'value' => self::EXPRESS,
            ],
            self::EXTRACT => [
                'name' => '上门自提',
                'value' => self::EXTRACT,
            ],
        ];
    }

}