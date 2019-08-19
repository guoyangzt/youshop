<?php

namespace app\store\model\user;

use app\common\model\user\Grade as GradeModel;

use app\store\model\User as UserModel;

/**
 * 用户会员等级模型
 * Class Grade
 * @package app\store\model\user
 */
class Grade extends GradeModel
{
    /**
     * 获取列表记录
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        return $this->where('is_delete', '=', 0)
            ->order(['weight' => 'asc', 'create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => request()->request()
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
        if (!$this->validateForm($data)) {
            return false;
        }
        $data['wxapp_id'] = self::$wxapp_id;
        return $this->allowField(true)->save($data);
    }

    /**
     * 编辑记录
     * @param $data
     * @return false|int
     */
    public function edit($data)
    {
        if (!$this->validateForm($data, 'edit')) {
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
        // 判断该等级下是否存在会员
        if (UserModel::checkExistByGradeId($this['grade_id'])) {
            $this->error = '该会员等级下存在用户，不允许删除';
            return false;
        }
        return $this->save(['is_delete' => 1]);
    }

    /**
     * 表单验证
     * @param $data
     * @param string $scene
     * @return bool
     */
    private function validateForm($data, $scene = 'add')
    {
        if ($scene === 'add') {
            // 需要判断等级权重是否已存在
            if (self::checkExistByWeight($data['weight'])) {
                $this->error = '等级权重已存在';
                return false;
            }
        } elseif ($scene === 'edit') {
            // 需要判断等级权重是否已存在
            if (self::checkExistByWeight($data['weight'], $this['grade_id'])) {
                $this->error = '等级权重已存在';
                return false;
            }
        }
        return true;
    }


}