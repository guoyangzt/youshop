<?php

namespace app\common\service\order;

use app\common\model\Setting as SettingModel;
use app\common\model\Printer as PrinterModel;
use app\common\library\printer\Driver as PrinterDriver;

/**
 * 订单打印服务类
 * Class Printer
 * @package app\common\service\order
 */
class Printer
{
    /**
     * 执行订单打印
     * @param \app\common\model\BaseModel $order 订单信息
     * @param int $scene 场景
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function printTicket($order, $scene)
    {
        // 打印机设置
        $printerConfig = SettingModel::getItem('printer', $order['wxapp_id']);
        // 判断是否开启打印设置
        if (!$printerConfig['is_open']
            || !$printerConfig['printer_id']
            || !in_array($scene, $printerConfig['order_status'])) {
            return false;
        }
        // 获取当前的打印机
        $printer = PrinterModel::detail($printerConfig['printer_id']);
        if (empty($printer) || $printer['is_delete']) {
            return false;
        }
        // 实例化打印机驱动
        $PrinterDriver = new PrinterDriver($printer);
        // 获取订单打印内容
        $content = $this->getPrintContent($order);
        // 执行打印请求
        return $PrinterDriver->printTicket($content);
    }

    /**
     * 构建订单打印的内容
     * @param \app\common\model\BaseModel $order
     * @return string
     */
    private function getPrintContent($order)
    {
        // 商城名称
        $storeName = SettingModel::getItem('store', $order['wxapp_id'])['name'];

        // 收货地址
        /* @var \app\common\model\OrderAddress $address */
        $address = $order['address'];

        // 拼接模板内容
        $content = "<CB>{$storeName}</CB><BR>";
        $content .= '--------------------------------<BR>';

        $content .= "昵称：{$order['user']['nickName']} [{$order['user_id']}]<BR>";
        $content .= "订单号：{$order['order_no']}<BR>";
        $content .= '付款时间：' . date('Y-m-d H:i:s', $order['pay_time']) . '<BR>';
        $content .= "--------------------------------<BR>";

        // 收货人信息
        if ($address) {
            $content .= "收货人：{$address['name']}<BR>";
            $content .= "联系电话：{$address['phone']}<BR>";
            $content .= '收货地址：' . $address->getFullAddress() . '<BR>';
        }

        // 商品信息
        $content .= '=========== 商品信息 ===========<BR>';
        foreach ($order['goods'] as $key => $goods) {
            $content .= ($key + 1) . ".商品名称：{$goods['goods_name']}<BR>";
            !empty($goods['goods_attr']) && $content .= "　商品规格：{$goods['goods_attr']}<BR>";
            $content .= "　购买数量：{$goods['total_num']}<BR>";
            $content .= "　商品总价：{$goods['total_price']}元<BR>";
            $content .= '--------------------------------<BR>';
        }

        // 买家备注
        if (!empty($order['buyer_remark'])) {
            $content .= '============ 买家备注 ============<BR>';
            $content .= "<B>{$order['buyer_remark']}</B><BR>";
            $content .= '--------------------------------<BR>';
        }

        // 订单金额
        if ($order['coupon_money'] > 0) {
            $content .= "优惠券：-{$order['coupon_money']}元<BR>";
        }
        if ($order['update_price']['value'] != '0.00') {
            $content .= "后台改价：{$order['update_price']['symbol']}{$order['update_price']['value']}元<BR>";
        }
        $content .= "运费：{$order['express_price']}元<BR>";
        $content .= '------------------------------<BR>';
        $content .= "<RIGHT>实付款：<BOLD><B>{$order['pay_price']}</B></BOLD>元</RIGHT><BR>";

        return $content;
    }

}