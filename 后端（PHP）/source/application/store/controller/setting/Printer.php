<?php

namespace app\store\controller\setting;

use app\store\controller\Controller;
use app\store\model\Printer as PrinterModel;

/**
 * 小票打印机管理
 * Class Printer
 * @package app\store\controller\setting
 */
class Printer extends Controller
{
    /**
     * 打印机列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new PrinterModel;
        $list = $model->getList();
        return $this->fetch('index', compact('list'));
    }

    /**
     * 添加打印机
     * @return array|mixed
     */
    public function add()
    {
        $model = new PrinterModel;
        if (!$this->request->isAjax()) {
            // 打印机类型列表
            $printerType = $model::getPrinterTypeList();
            return $this->fetch('add', compact('printerType'));
        }
        // 新增记录
        if ($model->add($this->postData('printer'))) {
            return $this->renderSuccess('添加成功', url('setting.printer/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑打印机
     * @param $printer_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($printer_id)
    {
        // 模板详情
        $model = PrinterModel::detail($printer_id);
        if (!$this->request->isAjax()) {
            // 打印机类型列表
            $printerType = $model::getPrinterTypeList();
            return $this->fetch('edit', compact('model', 'printerType'));
        }
        // 更新记录
        if ($model->edit($this->postData('printer'))) {
            return $this->renderSuccess('更新成功', url('setting.printer/index'));
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除打印机
     * @param $printer_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($printer_id)
    {
        $model = PrinterModel::detail($printer_id);
        if (!$model->setDelete()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

    /**
     * 测试打印接口
     * @param int $order_id
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function test($order_id = 180)
    {
        // 订单信息
        $order = \app\store\model\Order::detail($order_id);
        // 实例化打印机驱动
        $Printer = new \app\common\service\order\Printer();
        $Printer->printTicket($order, \app\common\enum\OrderStatus::ORDER_PAYMENT);
    }


}