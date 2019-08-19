<?php

namespace app\store\model\user;

use app\common\model\user\GradeLog as GradeLogModel;

/**
 * 用户会员等级变更记录模型
 * Class GradeLog
 * @package app\store\model\user
 */
class GradeLog extends GradeLogModel
{

    /**
     * 新增变更记录
     * @param $data
     * @return array|false
     * @throws \Exception
     */
    public function record($data)
    {
        return $this->records([$data]);
    }

}