<?php

namespace app\store\model;

use app\common\model\Wxapp as WxappModel;
use think\Cache;

/**
 * 微信小程序模型
 * Class Wxapp
 * @package app\store\model
 */
class Wxapp extends WxappModel
{
    /**
     * 更新小程序设置
     * @param $data
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function edit($data)
    {
        $this->startTrans();
        try {
            // 删除wxapp缓存
            self::deleteCache();
            // 写入微信支付证书文件
            $this->writeCertPemFiles($data['cert_pem'], $data['key_pem']);
            // 更新小程序设置
            $this->allowField(true)->save($data);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 写入cert证书文件
     * @param string $cert_pem
     * @param string $key_pem
     * @return bool
     */
    private function writeCertPemFiles($cert_pem = '', $key_pem = '')
    {
        if (empty($cert_pem) || empty($key_pem)) {
            return false;
        }
        // 证书目录
        $filePath = APP_PATH . 'common/library/wechat/cert/' . self::$wxapp_id . '/';
        // 目录不存在则自动创建
        if (!is_dir($filePath)) {
            mkdir($filePath, 0755, true);
        }
        // 写入cert.pem文件
        if (!empty($cert_pem)) {
            file_put_contents($filePath . 'cert.pem', $cert_pem);
        }
        // 写入key.pem文件
        if (!empty($key_pem)) {
            file_put_contents($filePath . 'key.pem', $key_pem);
        }
        return true;
    }

    /**
     * 记录图片信息
     * @param $wxapp_id
     * @param $oldFileId
     * @param $newFileName
     * @param $fromType
     * @return int|mixed
     */
    private function uploadImage($wxapp_id, $oldFileId, $newFileName, $fromType)
    {
//        $UploadFile = new UploadFile;
        $UploadFileUsed = new UploadFileUsed;
        if ($oldFileId > 0) {
            // 获取原图片path
            $oldFileName = UploadFile::getFileName($oldFileId);
            // 新文件与原来路径一致, 代表用户未修改, 不做更新
            if ($newFileName === $oldFileName)
                return $oldFileId;
            // 删除原文件使用记录
            $UploadFileUsed->remove('service', $oldFileId);
        }
        // 删除图片
        if (empty($newFileName)) return 0;
        // 查询新文件file_id
        $fileId = UploadFile::getFildIdByName($newFileName);
        // 添加文件使用记录
        $UploadFileUsed->add([
            'file_id' => $fileId,
            'wxapp_id' => $wxapp_id,
            'from_type' => $fromType
        ]);
        return $fileId;
    }

    /**
     * 删除wxapp缓存
     * @return bool
     */
    public static function deleteCache()
    {
        return Cache::rm('wxapp_' . self::$wxapp_id);
    }

}
