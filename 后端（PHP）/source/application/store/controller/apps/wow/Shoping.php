<?php

namespace app\store\controller\apps\wow;

use app\store\controller\Controller;
use app\store\model\wow\Shoping as ShopingModel;
use app\common\service\wechat\wow\Shoping as WowService;

/**
 * 好物圈-商品收藏
 * Class Shoping
 * @package app\store\controller\apps\wow
 */
class Shoping extends Controller
{
    /**
     * 商品收藏记录
     * @param string $search
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index($search = '')
    {
        $model = new ShopingModel;
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
        $WechatWow = new WowService($this->getWxappId());
        $WechatWow->delete($id);
        return $this->renderSuccess('操作成功');
    }

}