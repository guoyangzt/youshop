<?php

namespace app\admin\model;

use app\common\model\WxappHelp as WxappHelpModel;

/**
 * 小程序帮助中心
 * Class WxappHelp
 * @package app\admin\model
 */
class WxappHelp extends WxappHelpModel
{
    /**
     * 新增默认帮助
     * @param $wxapp_id
     * @return false|int
     */
    public function insertDefault($wxapp_id)
    {
        return $this->save([
            'title' => '关于小程序',
            'content' => '小程序本身无需下载，无需注册，不占用手机内存，可以跨平台使用，响应迅速，体验接近原生APP。',
            'sort' => 100,
            'wxapp_id' => $wxapp_id
        ]);
    }

}
