<?php

namespace app\api\controller\balance;

use app\api\controller\Controller;
use app\api\model\user\BalanceLog as BalanceLogModel;

/**
 * 余额账单明细
 * Class Log
 * @package app\api\controller\balance
 */
class Log extends Controller
{
    /**
     * 余额账单明细列表
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function lists()
    {
        $user = $this->getUser();
        $list = (new BalanceLogModel)->getList($user['user_id']);
        return $this->renderSuccess(compact('list'));
    }

}