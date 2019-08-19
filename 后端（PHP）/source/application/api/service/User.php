<?php

namespace app\api\service;

use think\Cache;

class User
{
    /**
     * 记忆上门自提联系人
     * @param $userId
     * @param $linkman
     * @param $phone
     * @return bool
     */
    public static function setLastExtract($userId, $linkman, $phone)
    {
        // 缓存时间30天
        $expire = 86400 * 30;
        return Cache::set("{$userId}_LastExtract", compact('linkman', 'phone'), $expire);
    }

    /**
     * 记忆上门自提联系人
     * @param $userId
     * @return mixed
     */
    public static function getLastExtract($userId)
    {
        if ($lastExtract = Cache::get("{$userId}_LastExtract")) {
            return $lastExtract;
        }
        return ['linkman' => '1', 'phone' => '2'];
    }

}