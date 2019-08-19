<?php

namespace app\api\controller;

use app\api\model\Cart as CartModel;
use app\api\model\Order as OrderModel;
use app\api\validate\order\Checkout as CheckoutValidate;
use app\common\enum\order\PayType as PayTypeEnum;

/**
 * 订单控制器
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
     * 订单确认-立即购买
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function buyNow()
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
     * 订单确认-购物车结算
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function cart()
    {
        // 请求的参数
        $params = $this->getCheckoutParams([
            'cart_ids' => '',
        ]);
        // 商品结算信息
        $CartModel = new CartModel($this->user);
        // 购物车商品列表
        $goodsList = $CartModel->getList($params['cart_ids']);
        // 获取订单结算信息
        $orderInfo = (new OrderModel)->getCart($this->user, $params, $goodsList);
        if ($this->request->isGet()) {
            return $this->renderSuccess($orderInfo);
        }
        // 创建订单
        $model = new OrderModel;
        if (!$model->createOrder($this->user, $orderInfo, $params)) {
            return $this->renderError($model->getError() ?: '订单创建失败');
        }
        // 移出购物车中已下单的商品
        $CartModel->clearAll($params['cart_ids']);
        // 构建微信支付请求
        $payment = $model->onOrderPayment($this->user, $params['pay_type']);
        // 返回状态
        return $this->renderSuccess([
            'order_id' => $model['order_id'],   // 订单id
            'pay_type' => $params['pay_type'],  // 支付方式
            'payment' => $payment               // 微信支付参数
        ]);
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

}
