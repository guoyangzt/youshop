<?php

namespace app\store\model;

use app\common\model\WxappPage as WxappPageModel;

/**
 * 微信小程序diy页面模型
 * Class WxappPage
 * @package app\common\model
 */
class WxappPage extends WxappPageModel
{
    /**
     * 获取列表
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        return $this->where(['is_delete' => 0])->order(['create_time' => 'desc'])->select();
    }

    /**
     * 新增页面
     * @param $data
     * @return bool
     */
    public function add($data)
    {
        // 删除wxapp缓存
        Wxapp::deleteCache();
        return $this->save([
            'page_type' => 20,
            'page_name' => $data['page']['params']['name'],
            'page_data' => $data,
            'wxapp_id' => self::$wxapp_id
        ]);
    }

    /**
     * 更新页面
     * @param $data
     * @return bool
     */
    public function edit($data)
    {
        // 删除wxapp缓存
        Wxapp::deleteCache();
        // 保存数据
        return $this->save([
                'page_name' => $data['page']['params']['name'],
                'page_data' => $data
            ]) !== false;
    }

    /**
     * 删除记录
     * @return int
     */
    public function setDelete()
    {
        if ($this['page_type'] == 10) {
            $this->error = '默认首页不可以删除';
            return false;
        }
        // 删除wxapp缓存
        Wxapp::deleteCache();
        return $this->save(['is_delete' => 1]);
    }

    /**
     * 设为默认首页
     * @return int
     */
    public function setHome()
    {
        // 取消原默认首页
        $this->where(['page_type' => 10])->update(['page_type' => 20]);
        // 删除wxapp缓存
        Wxapp::deleteCache();
        return $this->save(['page_type' => 10]);
    }

}
