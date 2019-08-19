<?php

namespace app\store\controller\market;

use app\store\controller\Controller;
use app\store\model\Goods;
use app\store\model\Region as RegionModel;
use app\store\model\Setting as SettingModel;

/**
 * 营销设置-基本功能
 * Class Basic
 * @package app\store\controller
 */
class Basic extends Controller
{
    /**
     * 满额包邮设置
     * @return array|bool|mixed
     * @throws \think\exception\DbException
     */
    public function full_free()
    {
        if (!$this->request->isAjax()) {
            $values = SettingModel::getItem('full_free');
            return $this->fetch('full_free', [
                'goodsList' => (new Goods)->getListByIds($values['notin_goods']),
                'regionData' => RegionModel::getCacheTree(),   // 所有地区
                'values' => $values
            ]);
        }
        $model = new SettingModel;
        if ($model->edit('full_free', $this->postData('model'))) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

}