<?php

namespace app\admin\controller\setting;

use app\admin\controller\Controller;
use think\Cache as CacheDriver;

/**
 * 清理缓存
 * Class Index
 * @package app\admin\controller
 */
class Cache extends Controller
{
    /**
     * 清理缓存
     * @param bool $isForce
     * @return mixed
     */
    public function clear($isForce = false)
    {
        if ($this->request->isAjax()) {
            $this->rmCache($this->postData('cache'));
            return $this->renderSuccess('操作成功');
        }
        return $this->fetch('clear', [
            'isForce' => !!$isForce ?: config('app_debug'),
        ]);
    }

    /**
     * 删除缓存
     * @param $data
     */
    private function rmCache($data)
    {
        // 数据缓存
        if (in_array('data', $data['item'])) {
            // 强制模式
            $isForce = isset($data['isForce']) ? !!$data['isForce'] : false;
            // 清除缓存
            CacheDriver::clear($isForce ? null : 'cache');
        }
        // 临时文件
        if (in_array('temp', $data['item'])) {
            $paths = [
                'temp' => WEB_PATH . 'temp/',
                'runtime' => RUNTIME_PATH . 'image/'
            ];
            foreach ($paths as $path) {
                $this->deleteFolder($path);
            }
        }
    }

    /**
     * 递归删除指定目录下所有文件
     * @param $path
     * @return bool
     */
    private function deleteFolder($path)
    {
        if (!is_dir($path))
            return false;
        // 扫描一个文件夹内的所有文件夹和文件
        foreach (scandir($path) as $val) {
            // 排除目录中的.和..
            if (!in_array($val, ['.', '..', '.gitignore'])) {
                // 如果是目录则递归子目录，继续操作
                if (is_dir($path . $val)) {
                    // 子目录中操作删除文件夹和文件
                    $this->deleteFolder($path . $val . '/');
                    // 目录清空后删除空文件夹
                    rmdir($path . $val . '/');
                } else {
                    // 如果是文件直接删除
                    unlink($path . $val);
                }
            }
        }
        return true;
    }

}
