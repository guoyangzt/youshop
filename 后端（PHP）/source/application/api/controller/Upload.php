<?php

namespace app\api\controller;

use app\api\model\UploadFile;
use app\api\model\Setting as SettingModel;
use app\common\library\storage\Driver as StorageDriver;

/**
 * 文件库管理
 * Class Upload
 * @package app\api\controller
 */
class Upload extends Controller
{
    private $config;
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        // 存储配置信息
        $this->config = SettingModel::getItem('storage');
        // 验证用户
        $this->user = $this->getUser();
    }

    /**
     * 图片上传接口
     * @return array
     * @throws \think\Exception
     */
    public function image()
    {
        // 实例化存储驱动
        $StorageDriver = new StorageDriver($this->config);
        // 设置上传文件的信息
        $StorageDriver->setUploadFile('iFile');
        // 上传图片
        if (!$StorageDriver->upload()) {
            return json(['code' => 0, 'msg' => '图片上传失败' . $StorageDriver->getError()]);
        }
        // 图片上传路径
        $fileName = $StorageDriver->getFileName();
        // 图片信息
        $fileInfo = $StorageDriver->getFileInfo();
        // 添加文件库记录
        $uploadFile = $this->addUploadFile($fileName, $fileInfo, 'image');
        // 图片上传成功
        return json(['code' => 1, 'msg' => '图片上传成功', 'data' => $uploadFile->visible(['file_id'])]);
    }

    /**
     * 添加文件库上传记录
     * @param $fileName
     * @param $fileInfo
     * @param $fileType
     * @return UploadFile
     */
    private function addUploadFile($fileName, $fileInfo, $fileType)
    {
        // 存储引擎
        $storage = $this->config['default'];
        // 存储域名
        $fileUrl = isset($this->config['engine'][$storage]['domain'])
            ? $this->config['engine'][$storage]['domain'] : '';
        // 添加文件库记录
        $model = new UploadFile;
        $model->add([
            'storage' => $storage,
            'file_url' => $fileUrl,
            'file_name' => $fileName,
            'file_size' => $fileInfo['size'],
            'file_type' => $fileType,
            'extension' => pathinfo($fileInfo['name'], PATHINFO_EXTENSION),
            'is_user' => 1
        ]);
        return $model;
    }

}