<?php

namespace app\api\controller\sharing;

use app\api\controller\Controller;
use app\api\model\sharing\Order as OrderModel;
use app\api\validate\sharing\order\Checkout as CheckoutValidate;

use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\order\PayType as PayTypeEnum;
use app\common\service\qrcode\Extract as ExtractQRcode;

/**
 * 拼团订单控制器
 * Class Order
 * @package app\api\controller
 */
class Order extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /* @var CheckoutValidate $validate */
    private $validate;

    /**
     * 订单结算api参数
     * @var array
     */
    private $checkoutParam = [
        'active_id' => 0,   // 参与的拼单id
        'delivery' => null, // 配送方式
        'shop_id' => 0,     // 自提门店id
        'linkman' => '',    // 自提联系人
        'phone' => '',    // 自提联系电话
        'coupon_id' => 0,    // 优惠券id
        'remark' => '',    // 买家留言
        'pay_type' => PayTypeEnum::WECHAT,  // 支付方式
    ];

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        // 用户信息
        $this->user = $this->getUser();
        // 验证类
        $this->validate = new CheckoutValidate;
    }

    /**
     * 订单确认
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkout()
    {
        // 请求的参数
        $params = $this->getCheckoutParams([
            'goods_id' => 0,
            'goods_num' => 0,
            'goods_sku_id' => '',
        ]);
        // 表单验证
        if (!$this->validate->scene('buyNow')->check($params)) {
            return $this->renderError($this->validate->getError());
        }
        // 获取商品结算信息
        $model = new OrderModel;
        $orderInfo = $model->getBuyNow($this->user, $params);
        if ($this->request->isGet()) {
            return $this->renderSuccess($orderInfo);
        }
        // submit：订单结算提交
        if ($model->hasError()) {
            return $this->renderError($model->getError());
        }
        // 创建订单
        if (!$model->createOrder($this->user, $orderInfo, $params)) {
            return $this->renderError($model->getError() ?: '订单创建失败');
        }
        // 构建微信支付请求
        $payment = $model->onOrderPayment($this->user, $params['pay_type']);
        // 支付状态提醒
        $message = ['success' => '支付成功', 'error' => '订单未支付'];
        return $this->renderSuccess([
            'order_id' => $model['order_id'],   // 订单id
            'pay_type' => $params['pay_type'],  // 支付方式
            'payment' => $payment               // 微信支付参数
        ], $message);
    }

    /**
     * 订单结算提交的参数
     * @param $define
     * @return array
     */
    private function getCheckoutParams($define)
    {
        return array_merge($this->checkoutParam, $define, $this->request->param());
    }

    /**
     * 我的拼团订单列表
     * @param $dataType
     * @return array
     * @throws \think\exception\DbException
     */
    public function lists($dataType)
    {
        $model = new OrderModel;
        $list = $model->getList($this->user['user_id'], $dataType);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 拼团订单详情信息
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     */
    public function detail($order_id)
    {
        // 订单详情
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        // 该订单是否允许申请售后
        $order['isAllowRefund'] = $order->isAllowRefund();
        return $this->renderSuccess(compact('order'));
    }

    /**
     * 获取物流信息
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     */
    public function express($order_id)
    {
        // 订单信息
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if (!$order['express_no']) {
            return $this->renderError('没有物流信息');
        }
        // 获取物流信息
        /* @var \app\store\model\Express $model */
        $model = $order['express'];
        $express = $model->dynamic($model['express_name'], $model['express_code'], $order['express_no']);
        if ($express === false) {
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess(compact('express'));
    }

    /**
     * 取消订单
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     */
    public function cancel($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->cancel()) {
            return $this->renderSuccess($model->getError() ?: '订单取消成功');
        }
        return $this->renderError($model->getError() ?: '订单取消失败');
    }

    /**
     * 确认收货
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     */
    public function receipt($order_id)
    {
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        if ($model->receipt()) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError());
    }

    /**
     * 立即支付
     * @param int $order_id 订单id
     * @param int $payType 支付方式
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function pay($order_id, $payType = PayTypeEnum::WECHAT)
    {
        // 订单详情
        $model = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        // 订单支付事件
        if (!$model->onPay($payType)) {
            return $this->renderError($model->getError() ?: '订单支付失败');
        }
        // 构建微信支付请求
        $payment = $model->onOrderPayment($this->user, $payType);
        // 支付状态提醒
        $message = ['success' => '支付成功', 'error' => '订单未支付'];
        return $this->renderSuccess([
            'order_id' => $model['order_id'],   // 订单id
            'pay_type' => $payType,             // 支付方式
            'payment' => $payment               // 微信支付参数
        ], $message);
    }

    /**
     * 获取订单核销二维码
     * @param $order_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function extractQrcode($order_id)
    {
        // 订单详情
        $order = OrderModel::getUserOrderDetail($order_id, $this->user['user_id']);
        // 判断是否为待核销订单
        if (!$order->checkExtractOrder($order)) {
            return $this->renderError($order->getError());
        }
        $Qrcode = new ExtractQRcode(
            $this->wxapp_id,
            $this->user,
            $order_id,
            OrderTypeEnum::SHARING
        );
        return $this->renderSuccess([
            'qrcode' => $Qrcode->getImage(),
        ]);
    }

}
