<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\dealer\Setting;
use app\api\model\dealer\User as DealerUserModel;
use app\api\model\dealer\Apply as DealerApplyModel;

/**
 * 分销中心
 * Class Dealer
 * @package app\api\controller\user
 */
class Dealer extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    private $dealer;
    private $setting;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        // 用户信息
        $this->user = $this->getUser();
        // 分销商用户信息
        $this->dealer = DealerUserModel::detail($this->user['user_id']);
        // 分销商设置
        $this->setting = Setting::getAll();
    }

    /**
     * 分销商中心
     * @return array
     */
    public function center()
    {
        return $this->renderSuccess([
            // 当前是否为分销商
            'is_dealer' => $this->isDealerUser(),
            // 当前用户信息
            'user' => $this->user,
            // 分销商用户信息
            'dealer' => $this->dealer,
            // 背景图
            'background' => $this->setting['background']['values']['index'],
            // 页面文字
            'words' => $this->setting['words']['values'],
        ]);
    }

    /**
     * 分销商申请状态
     * @param null $referee_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function apply($referee_id = null)
    {
        // 推荐人昵称
        $referee_name = '平台';
        if ($referee_id > 0 && ($referee = DealerUserModel::detail($referee_id))) {
            $referee_name = $referee['user']['nickName'];
        }
        return $this->renderSuccess([
            // 当前是否为分销商
            'is_dealer' => $this->isDealerUser(),
            // 当前是否在申请中
            'is_applying' => DealerApplyModel::isApplying($this->user['user_id']),
            // 推荐人昵称
            'referee_name' => $referee_name,
            // 背景图
            'background' => $this->setting['background']['values']['apply'],
            // 页面文字
            'words' => $this->setting['words']['values'],
            // 申请协议
            'license' => $this->setting['license']['values']['license'],
        ]);
    }

    /**
     * 分销商提现信息
     * @return array
     */
    public function withdraw()
    {
        return $this->renderSuccess([
            // 分销商用户信息
            'dealer' => $this->dealer,
            // 结算设置
            'settlement' => $this->setting['settlement']['values'],
            // 背景图
            'background' => $this->setting['background']['values']['withdraw_apply'],
            // 页面文字
            'words' => $this->setting['words']['values'],
        ]);
    }

    /**
     * 当前用户是否为分销商
     * @return bool
     */
    private function isDealerUser()
    {
        return !!$this->dealer && !$this->dealer['is_delete'];
    }

}