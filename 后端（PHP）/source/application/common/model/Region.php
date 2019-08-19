<?php

namespace app\common\model;

use think\Cache;

/**
 * 地区模型
 * Class Region
 * @package app\common\model
 */
class Region extends BaseModel
{
    protected $name = 'region';
    protected $createTime = false;
    protected $updateTime = false;

    // 当前数据版本号
    private static $version = '1.1';

    /**
     * 根据id获取地区名称
     * @param $id
     * @return string
     */
    public static function getNameById($id)
    {
        return $id > 0 ? self::getCacheAll()[$id]['name'] : '其他';
    }

    /**
     * 根据名称获取地区id
     * @param $name
     * @param int $level
     * @param int $pid
     * @return mixed
     */
    public static function getIdByName($name, $level = 0, $pid = 0)
    {
        $data = self::getCacheAll();
        foreach ($data as $item) {
            if ($item['name'] == $name && $item['level'] == $level && $item['pid'] == $pid)
                return $item['id'];
        }
        return 0;
    }

    /**
     * 获取所有地区(树状结构)
     * @return mixed
     */
    public static function getCacheTree()
    {
        return static::getCacheData('tree');
    }

    /**
     * 获取所有地区列表
     * @return mixed
     */
    public static function getCacheAll()
    {
        return static::getCacheData('all');
    }

    /**
     * 获取所有地区的总数
     * @return mixed
     */
    public static function getCacheCounts()
    {
        return static::getCacheData('counts');
    }

    /**
     * 获取缓存中的数据(存入静态变量)
     * @param null $item
     * @return array|mixed
     */
    private static function getCacheData($item = null)
    {
        static $cacheData = [];
        if (empty($cacheData)) {
            $static = new static;
            $cacheData = $static->regionCache();
        }
        if (is_null($item)) {
            return $cacheData;
        }
        return $cacheData[$item];
    }

    /**
     * 获取地区缓存
     * @return mixed
     */
    private function regionCache()
    {
        // 缓存的数据
        $complete = Cache::get('region');
        // 如果存在缓存则返回缓存的数据，否则从数据库中查询
        // 条件1: 获取缓存数据
        // 条件2: 数据版本号要与当前一致
        if (
            !empty($complete)
            && isset($complete['version'])
            && $complete['version'] == self::$version
        ) {
            return $complete;
        }
        // 所有地区
        $allList = $tempList = $this->getAllList();
        // 已完成的数据
        $complete = [
            'all' => $allList,
            'tree' => $this->getTreeList($allList),
            'counts' => $this->getCount($allList),
            'version' => self::$version,
        ];
        // 写入缓存
        Cache::tag('cache')->set('region', $complete);
        return $complete;
    }

    private static function getCount($allList)
    {
        $counts = [
            'total' => count($allList),
            'province' => 0,
            'city' => 0,
            'region' => 0,
        ];
        $level = [1 => 'province', 2 => 'city', 3 => 'region'];
        foreach ($allList as $item) {
            $counts[$level[$item['level']]]++;
        }
        return $counts;
    }

    /**
     * 格式化为树状格式
     * @param $allList
     * @return array
     */
    private function getTreeList($allList)
    {
        $treeList = [];
        foreach ($allList as $pKey => $province) {
            if ($province['level'] == 1) {    // 省份
                $treeList[$province['id']] = $province;
                unset($allList[$pKey]);
                foreach ($allList as $cKey => $city) {
                    if ($city['level'] == 2 && $city['pid'] == $province['id']) {    // 城市
                        $treeList[$province['id']]['city'][$city['id']] = $city;
                        unset($allList[$cKey]);
                        foreach ($allList as $rKey => $region) {
                            if ($region['level'] == 3 && $region['pid'] == $city['id']) {    // 地区
                                $treeList[$province['id']]['city'][$city['id']]['region'][$region['id']] = $region;
                                unset($allList[$rKey]);
                            }
                        }
                    }
                }
            }
        }
        return $treeList;
    }

    /**
     * 从数据库中获取所有地区
     * @return array
     */
    private function getAllList()
    {
        return self::useGlobalScope(false)->column('id, pid, name, level', 'id');
    }


}
