<?php

namespace app\store\model;

use think\Request;
use app\common\model\UploadFile as UploadFileModel;
use app\store\model\Setting as SettingModel;
use app\common\library\storage\Driver as StorageDriver;

/**
 * 文件库模型
 * Class UploadFile
 * @package app\store\model
 */
class UploadFile extends UploadFileModel
{
    /**
     * 获取列表记录
     * @param int $groupId 分组id
     * @param string $fileType 文件类型
     * @param bool|int $isRecycle
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($groupId = -1, $fileType = '', $isRecycle = -1)
    {
        // 文件分组
        $groupId != -1 && $this->where('group_id', '=', (int)$groupId);
        // 文件类型
        !empty($fileType) && $this->where('file_type', '=', trim($fileType));
        // 是否在回收站
        $isRecycle > -1 && $this->where('is_recycle', '=', (int)$isRecycle);
        // 查询列表数据
        return $this->with(['upload_group'])
            ->where(['is_user' => 0, 'is_delete' => 0])
            ->order(['file_id' => 'desc'])
            ->paginate(32, false, [
                'query' => Request::instance()->request()
            ]);
    }

    /**
     * 移入|移出回收站
     * @param bool $isRecycle
     * @return false|int
     */
    public function setRecycle($isRecycle = true)
    {
        return $this->save(['is_recycle' => (int)$isRecycle]);
    }

    /**
     * 删除文件
     * @return false|int
     * @throws \think\Exception
     */
    public function setDelete()
    {
        // 存储配置信息
        $config = SettingModel::getItem('storage');
        // 实例化存储驱动
        $StorageDriver = new StorageDriver($config, $this['storage']);
        // 删除文件
        if (!$StorageDriver->delete($this['file_name'])) {
            $this->error = '文件删除失败：' . $StorageDriver->getError();
            return false;
        }
        return $this->save(['is_delete' => 1]);
    }

    /**
     * 批量软删除
     * @param $fileIds
     * @return $this
     */
    public function softDelete($fileIds)
    {
        return $this->where('file_id', 'in', $fileIds)->update(['is_delete' => 1]);
    }

    /**
     * 批量移动文件分组
     * @param $group_id
     * @param $fileIds
     * @return $this
     */
    public function moveGroup($group_id, $fileIds)
    {
        return $this->where('file_id', 'in', $fileIds)->update(compact('group_id'));
    }

}
