<?php

namespace app\admin\model;

use app\common\model\Setting as SettingModel;

/**
 * 商城设置模型
 * Class Setting
 * @package app\admin\model
 */
class Setting extends SettingModel
{
    /**
     * 新增默认配置
     * @param $wxapp_id
     * @param $store_name
     * @return array|false
     * @throws \Exception
     */
    public function insertDefault($wxapp_id, $store_name)
    {
        // 添加商城默认设置记录
        $data = [];
        foreach ($this->defaultData($store_name) as $key => $item) {
            $data[] = array_merge($item, ['wxapp_id' => $wxapp_id]);
        }
        return $this->saveAll($data);
    }

}
