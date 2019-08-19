<?php

namespace app\store\controller\market;

use app\store\controller\Controller;
use app\store\model\wxapp\Formid as FormidModel;
use app\store\service\wxapp\Message as MessageService;

/**
 * 消息推送
 * Class Push
 * @package app\store\controller\market
 */
class Push extends Controller
{
    /**
     * 发送消息
     * @return array|mixed
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function send()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('send');
        }
        // 执行发送
        $MessageService = new MessageService;
        $MessageService->send($this->postData('send'));
        return $this->renderSuccess('', '', [
            'stateSet' => $MessageService->getStateSet()
        ]);
    }

    /**
     * 活跃用户列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function user()
    {
        $list = (new FormidModel)->getUserList();
        return $this->fetch('user', compact('list'));
    }

}