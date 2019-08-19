<?php

namespace app\store\controller\setting;

use app\store\controller\Controller;
use app\store\model\Region as RegionModel;
use app\store\model\Delivery as DeliveryModel;

/**
 * 配送设置
 * Class Delivery
 * @package app\store\controller\setting
 */
class Delivery extends Controller
{
    /**
     * 配送模板列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new DeliveryModel;
        $list = $model->getList();
        return $this->fetch('index', compact('list'));
    }

    /**
     * 删除模板
     * @param $delivery_id
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function delete($delivery_id)
    {
        $model = DeliveryModel::detail($delivery_id);
        if (!$model->remove()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

    /**
     * 添加配送模板
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function add()
    {
        if (!$this->request->isAjax()) {
            // 获取所有地区
            $regionData = json_encode(RegionModel::getCacheTree());
            // 地区总数
            $cityCount = RegionModel::getCacheCounts()['city'];
            return $this->fetch('add', compact('regionData', 'cityCount'));
        }
        // 新增记录
        $model = new DeliveryModel;
        if ($model->add($this->postData('delivery'))) {
            return $this->renderSuccess('添加成功', url('setting.delivery/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑配送模板
     * @param $delivery_id
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function edit($delivery_id)
    {
        // 模板详情
        $model = DeliveryModel::detail($delivery_id);
        if (!$this->request->isAjax()) {
            // 获取所有地区
            $regionData = json_encode(RegionModel::getCacheTree());
            // 地区总数
            $cityCount = RegionModel::getCacheCounts()['city'];
            // 获取配送区域及运费设置项
            $formData = json_encode($model->getFormList());
            return $this->fetch('add', compact('model', 'regionData', 'cityCount', 'formData'));
        }
        // 更新记录
        if ($model->edit($this->postData('delivery'))) {
            return $this->renderSuccess('更新成功', url('setting.delivery/index'));
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

}
