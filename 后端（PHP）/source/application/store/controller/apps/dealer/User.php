<?php

namespace app\store\controller\apps\dealer;

use app\store\controller\Controller;
use app\common\service\qrcode\Poster;
use app\store\model\dealer\User as UserModel;
use app\store\model\dealer\Referee as RefereeModel;
use app\store\model\dealer\Setting as SettingModel;

/**
 * 分销商管理
 * Class User
 * @package app\store\controller\apps\dealer
 */
class User extends Controller
{
    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 分销商用户列表
     * @param string $search
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index($search = '')
    {
        $model = new UserModel;
        return $this->fetch('index', [
            'list' => $model->getList($search),
            'basicSetting' => SettingModel::getItem('basic'),
        ]);
    }

    /**
     * 分销商用户列表
     * @param string $user_id
     * @param int $level
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function fans($user_id, $level = -1)
    {
        $model = new RefereeModel;
        return $this->fetch('fans', [
            'list' => $model->getList($user_id, $level),
            'basicSetting' => SettingModel::getItem('basic'),
        ]);
    }

    /**
     * 删除分销商
     * @param $dealer_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($dealer_id)
    {
        $model = UserModel::detail($dealer_id);
        if (!$model->setDelete()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

    /**
     * 分销商二维码
     * @param $dealer_id
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function qrcode($dealer_id)
    {
        $model = UserModel::detail($dealer_id);
        $Qrcode = new Poster($model);
        $this->redirect($Qrcode->getImage());
    }

}