<?php

namespace app\store\model;

use app\common\model\UploadGroup as UploadGroupModel;

/**
 * 文件库分组模型
 * Class UploadGroup
 * @package app\store\model
 */
class UploadGroup extends UploadGroupModel
{
    /**
     * 获取列表记录
     * @param string $groupType
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($groupType = '')
    {
        !empty($groupType) && $this->where('group_type', '=', trim($groupType));
        return $this->where('is_delete', '=', 0)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->select();
    }

    /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        return $this->save(array_merge([
            'wxapp_id' => self::$wxapp_id,
            'sort' => 100
        ], $data));
    }

    /**
     * 更新记录
     * @param $data
     * @return bool|int
     */
    public function edit($data)
    {
        return $this->allowField(true)->save($data) !== false;
    }

    /**
     * 删除记录
     * @return int
     */
    public function remove()
    {
        // 更新该分组下的所有文件
        (new UploadFile)->where('group_id', '=', $this['group_id'])
            ->update(['group_id' => 0]);
        // 删除分组
        return $this->save(['is_delete' => 1]);
    }

}
