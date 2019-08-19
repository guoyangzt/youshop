<?php

namespace app\store\model\store\shop;

use app\common\model\store\shop\Clerk as ClerkModel;

/**
 * 商家门店店员模型
 * Class Clerk
 * @package app\store\model\store\shop
 */
class Clerk extends ClerkModel
{
    const FORM_SCENE_ADD = 'add';
    const FORM_SCENE_EDIT = 'edit';

    /**
     * 获取列表数据
     * @param int $status 状态
     * @param int $shop_id 门店id
     * @param string $search 店员姓名/手机号
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($status = -1, $shop_id = 0, $search = '')
    {
        // 检索查询条件
        $status > -1 && $this->where('status', '=', (int)$status);
        $shop_id > 0 && $this->where('shop_id', '=', (int)$shop_id);
        !empty($search) && $this->where('real_name|mobile', 'like', "%{$search}%");
        // 查询列表数据
        return $this->with(['user', 'shop'])
            ->where('is_delete', '=', '0')
            ->order(['create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => \request()->request()
            ]);
    }

    /**
     * 新增记录
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function add($data)
    {
        // 表单验证
        if (!$this->validateForm($data, self::FORM_SCENE_ADD)) {
            return false;
        }
        $data['wxapp_id'] = self::$wxapp_id;
        return $this->allowField(true)->save($data);
    }

    /**
     * 编辑记录
     * @param $data
     * @return bool|false|int
     * @throws \think\exception\DbException
     */
    public function edit($data)
    {
        // 表单验证
        if (!$this->validateForm($data, self::FORM_SCENE_EDIT)) {
            return false;
        }
        return $this->allowField(true)->save($data) !== false;
    }

    /**
     * 软删除
     * @return false|int
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }

    /**
     * 表单验证
     * @param $data
     * @param string $scene
     * @return bool
     * @throws \think\exception\DbException
     */
    private function validateForm($data, $scene = self::FORM_SCENE_ADD)
    {
        if ($scene === self::FORM_SCENE_ADD) {
            if (!isset($data['user_id']) || empty($data['user_id'])) {
                $this->error = '请选择用户';
                return false;
            }
            if (self::detail(['user_id' => $data['user_id'], 'is_delete' => 0])) {
                $this->error = '该用户已经是店员，无需重复添加';
                return false;
            }
        }
        return true;
    }

}