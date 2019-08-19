<?php

namespace app\api\controller\user\dealer;

use app\api\controller\Controller;
use app\api\model\dealer\Apply as DealerApplyModel;

/**
 * 分销商申请
 * Class Apply
 * @package app\api\controller\user\dealer
 */
class Apply extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();   // 用户信息
    }

    /**
     * 提交分销商申请
     * @param string $name
     * @param string $mobile
     * @return array
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function submit($name = '', $mobile = '')
    {
        $model = new DealerApplyModel;
        if ($model->submit($this->user, $name, $mobile)) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError() ?: '提交失败');
    }

}