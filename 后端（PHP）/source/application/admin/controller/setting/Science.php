<?php

namespace app\admin\controller\setting;

use app\admin\controller\Controller;

/**
 * 环境检测
 * Class Science
 * @package app\admin\controller\setting
 */
class Science extends Controller
{
    /**
     * 状态class
     * @var array
     */
    private $statusClass = [
        'normal' => '',
        'warning' => 'am-active',
        'danger' => 'am-danger'
    ];

    /**
     * 环境检测
     */
    public function index()
    {
        return $this->fetch('index', [
            'statusClass' => $this->statusClass,
            'phpinfo' => $this->phpinfo(),  // PHP环境要求
            'server' => $this->server(), // 服务器信息
            'writeable' => $this->writeable(), // 目录权限监测
        ]);
    }

    /**
     * 服务器信息
     * @return array
     */
    private function server()
    {
        return [
            'system' => [
                'name' => '服务器操作系统',
                'value' => PHP_OS,
                'status' => PHP_SHLIB_SUFFIX === 'dll' ? 'warning' : 'normal',
                'remark' => '建议使用 Linux 系统以提升程序性能'
            ],
            'webserver' => [
                'name' => 'Web服务器环境',
                'value' => $this->request->server('SERVER_SOFTWARE'),
                'status' => PHP_SAPI === 'isapi' ? 'warning' : 'normal',
                'remark' => '建议使用 Apache 或 Nginx 以提升程序性能'
            ],
            'php' => [
                'name' => 'PHP版本',
                'value' => PHP_VERSION,
                'status' => version_compare(PHP_VERSION, '5.4.0') === -1 ? 'danger' : 'normal',
                'remark' => 'PHP版本必须为 5.4.0 以上'
            ],
            'upload_max' => [
                'name' => '文件上传限制',
                'value' => @ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'unknow',
                'status' => 'normal',
                'remark' => ''
            ],
            'web_path' => [
                'name' => '程序运行目录',
                'value' => str_replace('\\', '/', WEB_PATH),
                'status' => 'normal',
                'remark' => ''
            ],
        ];
    }

    /**
     * PHP环境要求
     * @return array
     */
    private function phpinfo()
    {
//        pre(  get_loaded_extensions() );
        return [
            'php_version' => [
                'name' => 'PHP版本',
                'value' => '5.4.0及以上',
                'status' => version_compare(PHP_VERSION, '5.4.0') === -1 ? 'danger' : 'normal',
                'remark' => 'PHP版本必须为 5.4.0及以上'
            ],
            'curl' => [
                'name' => 'CURL',
                'value' => '支持',
                'status' => extension_loaded('curl') && function_exists('curl_init') ? 'normal' : 'danger',
                'remark' => '您的PHP环境不支持CURL, 系统无法正常运行'
            ],
            'openssl' => [
                'name' => 'OpenSSL',
                'value' => '支持',
                'status' => extension_loaded('openssl') ? 'normal' : 'danger',
                'remark' => '没有启用OpenSSL, 将无法访问微信平台的接口'
            ],
            'pdo' => [
                'name' => 'PDO',
                'value' => '支持',
                'status' => extension_loaded('PDO') && extension_loaded('pdo_mysql') ? 'normal' : 'danger',
                'remark' => '您的PHP环境不支持PDO, 系统无法正常运行'
            ],
            'gd' => [
                'name' => 'GD',
                'value' => '支持',
                'status' => extension_loaded('gd') ? 'normal' : 'danger',
                'remark' => '您的PHP环境不支持GD, 系统无法正常生成图片'
            ],
            'bcmath' => [
                'name' => 'BCMath',
                'value' => '支持',
                'status' => extension_loaded('bcmath') ? 'normal' : 'danger',
                'remark' => '您的PHP环境不支持BCMath, 系统无法正常运行'
            ],
            'mbstring' => [
                'name' => 'mbstring',
                'value' => '支持',
                'status' => extension_loaded('mbstring') ? 'normal' : 'danger',
                'remark' => '您的PHP环境不支持mbstring, 系统无法正常运行'
            ],
            'SimpleXML' => [
                'name' => 'SimpleXML',
                'value' => '支持',
                'status' => extension_loaded('SimpleXML') ? 'normal' : 'danger',
                'remark' => '您的PHP环境不支持SimpleXML, 系统无法解析xml 无法使用微信支付'
            ],
        ];

    }

    /**
     * 目录权限监测
     */
    private function writeable()
    {
        $paths = [
            'uploads' => realpath(WEB_PATH) . '/uploads/',
            'temp' => realpath(WEB_PATH) . '/temp/',
            'wxpay_log' => realpath(APP_PATH) . '/common/library/wechat/logs/',
            'wxpay_cert' => realpath(APP_PATH) . '/common/library/wechat/cert/',
            'behavior_log' => realpath(APP_PATH) . '/task/behavior/logs/',
        ];
        return [
            'uploads' => [
                'name' => '文件上传目录',
                'value' => str_replace('\\', '/', $paths['uploads']),
                'status' => $this->checkWriteable($paths['uploads']) ? 'normal' : 'danger',
                'remark' => '目录不可写，系统将无法正常上传文件'
            ],
            'temp' => [
                'name' => '临时文件目录',
                'value' => str_replace('\\', '/', $paths['temp']),
                'status' => $this->checkWriteable($paths['temp']) ? 'normal' : 'danger',
                'remark' => '目录不可写，系统将无法正常写入文件'
            ],
            'wxpay_log' => [
                'name' => '微信支付日志目录',
                'value' => str_replace('\\', '/', $paths['wxpay_log']),
                'status' => $this->checkWriteable($paths['wxpay_log']) ? 'normal' : 'danger',
                'remark' => '目录不可写，系统将无法正常写入文件'
            ],
            'wxpay_cert' => [
                'name' => '微信支付证书目录',
                'value' => str_replace('\\', '/', $paths['wxpay_cert']),
                'status' => $this->checkWriteable($paths['wxpay_cert']) ? 'normal' : 'danger',
                'remark' => '目录不可写，系统将无法正常写入文件'
            ],
//            'behavior_log' => [
//                'name' => '自动任务日志目录',
//                'value' => str_replace('\\', '/', $paths['behavior_log']),
//                'status' => $this->checkWriteable($paths['behavior_log']) ? 'normal' : 'danger',
//                'remark' => '目录不可写，系统将无法正常上传文件'
//            ],
        ];

    }

    /**
     * 检查目录是否可写
     * @param $path
     * @return bool
     */
    private function checkWriteable($path)
    {
        try {
            !is_dir($path) && mkdir($path, 0755);
            if (!is_dir($path))
                return false;
            $fileName = $path . '/_test_write.txt';
            if ($fp = fopen($fileName, 'w')) {
                return fclose($fp) && unlink($fileName);
            }
        } catch (\Exception $e) {
        }
        return false;
    }

}
