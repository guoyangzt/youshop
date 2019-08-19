<?php

namespace app\store\controller\apps\dealer;

use app\store\controller\Controller;
use app\store\model\dealer\Setting as SettingModel;
use app\store\model\Goods as GoodsModel;

/**
 * 分销设置
 * Class Setting
 * @package app\store\controller\apps\dealer
 */
class Setting extends Controller
{
    /**
     * 分销设置
     * @return array|bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            $data = SettingModel::getAll();
            // 购买指定商品成为分销商：商品列表
            $goodsList = (new GoodsModel)->getListByIds($data['condition']['values']['become__buy_goods_ids']);
            return $this->fetch('index', compact('data', 'goodsList'));
        }
        $model = new SettingModel;
        if ($model->edit($this->postData('setting'))) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 分销海报
     * @return array|mixed
     * @throws \think\exception\PDOException
     */
    public function qrcode()
    {
        if (!$this->request->isAjax()) {
            $data = SettingModel::getItem('qrcode');
            return $this->fetch('qrcode', [
                'data' => json_encode($data, JSON_UNESCAPED_UNICODE)
            ]);
        }
        $model = new SettingModel;
        if ($model->edit(['qrcode' => $this->postData('qrcode')])) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

}