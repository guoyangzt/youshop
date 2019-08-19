<?php

namespace app\store\controller\apps\wow;

use app\store\controller\Controller;
use app\store\model\wow\Order as OrderModel;
use app\common\service\wechat\wow\Order as WowOrderService;

/**
 * 好物圈-订单信息
 * Class Order
 * @package app\store\controller\apps\wow
 */
class Order extends Controller
{
    /**
     * 订单记录
     * @param string $search
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index($search = '')
    {
        $model = new OrderModel;
        $list = $model->getList($search);
        return $this->fetch('index', compact('list'));
    }

    /**
     * 取消同步
     * @param $id
     * @return array|bool
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function delete($id)
    {
        // 删除微信好物圈收藏
        $WechatWow = new WowOrderService($this->getWxappId());
        $WechatWow->delete($id);
        return $this->renderSuccess('操作成功');
    }

}