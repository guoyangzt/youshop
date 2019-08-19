<?php

namespace app\store\controller\market\recharge;

use app\store\controller\Controller;
use app\store\model\recharge\Plan as PlanModel;

/**
 * 充值套餐管理
 * Class Coupon
 * @package app\store\controller\market
 */
class Plan extends Controller
{
    /* @var PlanModel $model */
    private $model;

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
        $this->model = new PlanModel;
    }

    /**
     * 充值套餐列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $list = $this->model->getList();
        return $this->fetch('index', compact('list'));
    }

    /**
     * 添加充值套餐
     * @return array|mixed
     */
    public function add()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('add');
        }
        // 新增记录
        if ($this->model->add($this->postData('plan'))) {
            return $this->renderSuccess('添加成功', url('market.recharge.plan/index'));
        }
        return $this->renderError($this->model->getError() ?: '添加失败');
    }

    /**
     * 更新充值套餐
     * @param $plan_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($plan_id)
    {
        // 充值套餐详情
        $model = PlanModel::detail($plan_id);
        if (!$this->request->isAjax()) {
            return $this->fetch('edit', compact('model'));
        }
        // 更新记录
        if ($model->edit($this->postData('plan'))) {
            return $this->renderSuccess('更新成功', url('market.recharge.plan/index'));
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除充值套餐
     * @param $plan_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function delete($plan_id)
    {
        // 套餐详情
        $model = PlanModel::detail($plan_id);
        // 更新记录
        if ($model->setDelete()) {
            return $this->renderSuccess('删除成功', url('market.recharge.plan/index'));
        }
        return $this->renderError($model->getError() ?: '删除成功');
    }

}