<?php

namespace app\task\behavior\user;

use think\Cache;
use app\task\model\User as UserModel;
use app\task\model\user\Grade as GradeModel;

class Grade
{
    /* @var GradeModel $model */
    private $model;

    /**
     * 执行函数
     * @param $model
     * @return bool
     * @throws \Exception
     */
    public function run($model)
    {
        if (!$model instanceof GradeModel) {
            return new GradeModel and false;
        }
        $this->model = $model;
        if (!$model::$wxapp_id) {
            return false;
        }
        $cacheKey = "__task_space__[user/Grade]__{$model::$wxapp_id}";
        if (!Cache::has($cacheKey)) {
            // 设置用户的会员等级
            $this->setUserGrade();
            Cache::set($cacheKey, time(), 60 * 10);
        }
        return true;
    }

    /**
     * 设置用户的会员等级
     * @return array|bool|false
     * @throws \Exception
     */
    private function setUserGrade()
    {
        // 用户模型
        $UserModel = new UserModel;
        // 获取所有等级
        $list = GradeModel::getUsableList(null, ['weight' => 'desc']);
        if ($list->isEmpty()) {
            return false;
        }
        // 遍历等级，根据升级条件 查询满足消费金额的用户列表，并且他的等级小于该等级
        $data = [];
        foreach ($list as $grade) {
            $userList = $UserModel->getUpgradeUserList($grade, array_keys($data));
            foreach ($userList as $user) {
                if (!isset($data[$user['user_id']])) {
                    $data[$user['user_id']] = [
                        'user_id' => $user['user_id'],
                        'old_grade_id' => $user['grade_id'],
                        'new_grade_id' => $grade['grade_id'],
                    ];
                }
            }
        }
        // 批量修改会员的等级
        return $UserModel->setBatchGrade($data);
    }

}