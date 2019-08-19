<?php

namespace app\api\model\sharing;

use app\common\model\sharing\Order as OrderModel;

use app\api\model\User as UserModel;
use app\api\model\Setting as SettingModel;
use app\api\model\store\Shop as ShopModel;
use app\api\model\UserCoupon as UserCouponModel;
use app\api\model\dealer\Order as DealerOrderModel;
use app\api\model\sharing\Goods as GoodsModel;
use app\api\model\sharing\GoodsSku as GoodsSkuModel;
use app\api\model\sharing\Setting as SharingSettingModel;
use app\api\model\sharing\OrderGoods as OrderGoodsModel;

use app\api\service\User as UserService;
use app\api\service\Payment as PaymentService;
use app\api\service\coupon\GoodsDeduct as GoodsDeductService;

use app\common\library\helper;
use app\common\service\delivery\Express as ExpressService;
use app\common\enum\order\PayStatus as PayStatusEnum;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\DeliveryType as DeliveryTypeEnum;
use app\common\enum\order\PayType as PayTypeEnum;
use app\common\exception\BaseException;

/**
 * 拼团订单模型
 * Class Order
 * @package app\api\model
 */
class Order extends OrderModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'update_time'
    ];

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
     * 订单确认-立即购买
     * @param $user
     * @param $param
     * @return array
     * @throws BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getBuyNow($user, $param)
    {
        // 获取订单商品列表
        $goodsList = $this->getOrderGoodsListByNow($param);
        // 订单确认-立即购买
        return $this->checkout($user, $param, $goodsList);
    }

    /**
     * 订单结算台信息
     * @param UserModel $user 用户信息
     * @param array $param 请求参数
     * @param array $goodsList 商品列表
     * @return array
     * @throws BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function checkout($user, $param, $goodsList)
    {
        // 整理提交的参数
        $params = array_merge($this->checkoutParam, $param);
        // 系统支持的配送方式 (后台设置)
        $deliverySetting = SettingModel::getItem('store')['delivery_type'];
        // 整理订单数据
        $orderData = [
            'order_type' => $param['order_type'],   // 订单类型
            'delivery' => $params['delivery'] > 0 ? $params['delivery'] : $deliverySetting[0], // 配送类型
            'address' => $user['address_default'],              // 默认地址
            'exist_address' => !$user['address']->isEmpty(),    // 是否存在收货地址
            'express_price' => 0.00,                // 配送费用
            'intra_region' => true,                 // 当前用户收货城市是否存在配送规则中
            'extract_shop' => [],                   // 自提门店信息
            'pay_type' => $params['pay_type'],      // 支付方式
            'deliverySetting' => $deliverySetting,  // 支持的配送方式
            'last_extract' => UserService::getLastExtract($user['user_id']),    // 记忆的自提联系方式
        ];
        // 验证商品状态, 是否允许购买
        $this->validateGoodsList($goodsList);
        // 订单商品总数量
        $orderTotalNum = helper::getArrayColumnSum($goodsList, 'total_num');
        // 设置订单商品会员折扣价
        $this->setOrderGoodsGradeMoney($user, $goodsList);
        // 设置订单商品总金额(不含优惠折扣)
        $this->setOrderTotalPrice($orderData, $goodsList);
        // 当前用户可用的优惠券列表
        $couponList = [];
        if (SharingSettingModel::getItem('basic')['is_coupon']) {
            $couponList = UserCouponModel::getUserCouponList($user['user_id'], $orderData['order_total_price']);
        }
        // 计算优惠券抵扣
        $this->setOrderCouponMoney($orderData, $goodsList, $couponList, $params['coupon_id']);
        // 计算订单商品的实际付款金额
        $this->setOrderGoodsPayPrice($goodsList);
        // 设置默认配送方式
        !$params['delivery'] && $params['delivery'] = current(SettingModel::getItem('store')['delivery_type']);
        // 处理配送方式
        if ($params['delivery'] == DeliveryTypeEnum::EXPRESS) {
            $this->setOrderExpress($orderData, $user, $goodsList);
        } elseif ($params['delivery'] == DeliveryTypeEnum::EXTRACT) {
            $params['shop_id'] > 0 && $orderData['extract_shop'] = ShopModel::detail($params['shop_id']);
        }
        // 计算订单商品总金额(不含运费)
        $this->setOrderPayPrice($orderData, $goodsList);
        // 返回订单数据
        return array_merge([
            'goods_list' => array_values($goodsList),   // 商品信息
            'order_total_num' => $orderTotalNum,        // 商品总数量
            'coupon_list' => array_values($couponList), // 优惠券列表
            'has_error' => $this->hasError(),
            'error_msg' => $this->getError(),
        ], $orderData);
    }

    /**
     * 验证订单商品的状态
     * @param $goodsList
     */
    private function validateGoodsList($goodsList)
    {
        foreach ($goodsList as $goods) {
            // 判断商品是否下架
            if ($goods['goods_status']['value'] != 10) {
                $this->setError("'很抱歉，商品 [{$goods['goods_name']}] 已下架'");
            }
            // 判断商品库存
            if ($goods['total_num'] > $goods['goods_sku']['stock_num']) {
                $this->setError("很抱歉，商品 [{$goods['goods_name']}] 库存不足");
            }
        }
    }

    /**
     * 设置订单的商品总金额(不含优惠折扣)
     * @param $orderData
     * @param $goodsList
     */
    private function setOrderTotalPrice(&$orderData, $goodsList)
    {
        // 订单商品的总金额(不含优惠券折扣)
        $orderData['order_total_price'] = helper::number2(helper::getArrayColumnSum($goodsList, 'total_price'));
    }

    /**
     * 设置订单的实际支付金额(含配送费)
     * @param $orderData
     * @param $goodsList
     */
    private function setOrderPayPrice(&$orderData, $goodsList)
    {
        // 订单金额(含优惠折扣)
        $orderData['order_price'] = helper::number2(helper::getArrayColumnSum($goodsList, 'total_pay_price'));
        // 订单实付款金额(订单金额 + 运费)
        $orderData['order_pay_price'] = helper::number2(helper::bcadd($orderData['order_price'], $orderData['express_price']));
    }

    /**
     * 计算订单商品的实际付款金额
     * @param $goodsList
     * @return bool
     */
    private function setOrderGoodsPayPrice(&$goodsList)
    {
        // 商品总价 - (优惠券抵扣)
        foreach ($goodsList as &$goods) {
            $totalPayPrice = helper::bcsub($goods['total_price'], $goods['coupon_money']);
            $goods['total_pay_price'] = helper::number2($totalPayPrice);
        }
        return true;
    }

    /**
     * 设置订单商品会员折扣价
     * @param $user
     * @param $goodsList
     * @return bool
     */
    private function setOrderGoodsGradeMoney($user, &$goodsList)
    {
        // 设置默认数据
        helper::setDataAttribute($goodsList, [
            // 标记参与会员折扣
            'is_user_grade' => false,
            // 会员等级抵扣的金额
            'grade_ratio' => 0,
            // 会员折扣的商品单价
            'grade_goods_price' => 0.00,
            // 会员折扣的总额差
            'grade_total_money' => 0.00,
        ], true);

        // 会员等级状态
        if (!($user['grade_id'] > 0 && !empty($user['grade']) && !$user['grade']['is_delete'] && $user['grade']['status'])) {
            return false;
        }
        // 计算抵扣金额
        foreach ($goodsList as &$goods) {
            // 判断商品是否参与会员折扣
            if (!$goods['is_enable_grade']) {
                continue;
            }
            // 商品单独设置了会员折扣
            if ($goods['is_alone_grade'] && isset($goods['alone_grade_equity'][$user['grade_id']])) {
                // 折扣比例
                $discountRatio = helper::bcdiv($goods['alone_grade_equity'][$user['grade_id']], 10);
            } else {
                // 折扣比例
                $discountRatio = helper::bcdiv($user['grade']['equity']['discount'], 10);
            }
            if ($discountRatio > 0) {
                // 会员折扣后的商品总金额
                $gradeTotalPrice = max(0.01, helper::bcmul($goods['total_price'], $discountRatio));
                helper::setDataAttribute($goods, [
                    'is_user_grade' => true,
                    'grade_ratio' => $discountRatio,
                    'grade_goods_price' => helper::number2(helper::bcmul($goods['goods_price'], $discountRatio), true),
                    'grade_total_money' => helper::number2(helper::bcsub($goods['total_price'], $gradeTotalPrice)),
                    'total_price' => $gradeTotalPrice,
                ], false);
            }
        }
        return true;
    }

    /**
     * 设置订单优惠券抵扣信息
     * @param array $orderData 订单信息
     * @param array $goodsList 订单商品列表
     * @param array $couponList 当前用户可用的优惠券列表
     * @param int $couponId 当前选择的优惠券id
     * @return bool
     * @throws BaseException
     */
    private function setOrderCouponMoney(&$orderData, &$goodsList, $couponList, $couponId)
    {
        // 设置默认数据：订单信息
        helper::setDataAttribute($orderData, [
            'coupon_id' => 0,       // 用户优惠券id
            'coupon_money' => 0,    // 优惠券抵扣金额
        ], false);
        // 设置默认数据：订单商品列表
        helper::setDataAttribute($goodsList, [
            'coupon_money' => 0,    // 优惠券抵扣金额
        ], true);
        // 如果没有可用的优惠券，直接返回
        if ($couponId <= 0 || empty($couponList)) {
            return true;
        }
        // 获取优惠券信息
        $couponInfo = helper::getArrayItemByColumn($couponList, 'user_coupon_id', $couponId);
        if ($couponInfo == false) {
            throw new BaseException(['msg' => '未找到优惠券信息']);
        }
        // 计算订单商品优惠券抵扣金额
        $CouponMoney = new GoodsDeductService;
        $completed = $CouponMoney->setGoodsCouponMoney($goodsList, $couponInfo['reduced_price']);
        // 分配订单商品优惠券抵扣金额
        foreach ($goodsList as $key => &$goods) {
            $goods['coupon_money'] = $completed[$key]['coupon_money'] / 100;
        }
        // 记录订单优惠券信息
        $orderData['coupon_id'] = $couponId;
        $orderData['coupon_money'] = $CouponMoney->getActualReducedMoney() / 100;
        return true;
    }

    /**
     * 获取订单商品列表
     * @param $params
     * @return array
     * @throws BaseException
     * @throws \think\Exception
     */
    private function getOrderGoodsListByNow($params)
    {
        // 商品详情
        /* @var GoodsModel $goods */
        $goods = GoodsModel::getDetails($params['goods_id']);
        // 商品sku信息
        $goods['goods_sku'] = GoodsModel::getGoodsSku($goods, $params['goods_sku_id']);
        // 商品列表
        $goodsList = [$goods->toArray()];
        foreach ($goodsList as &$item) {
            // 商品单价(根据order_type判断单买还是拼单)
            // order_type：下单类型 10 => 单独购买，20 => 拼团
            $item['goods_price'] = $params['order_type'] == 10 ? $item['goods_sku']['goods_price']
                : $item['goods_sku']['sharing_price'];
            // 商品购买数量
            $item['total_num'] = $params['goods_num'];
            // 商品购买总金额
            $item['total_price'] = helper::bcmul($item['goods_price'], $params['goods_num']);
        }
        return $goodsList;
    }

    /**
     * 订单配送-快递配送
     * @param $orderData
     * @param $user
     * @param $goodsList
     */
    private function setOrderExpress(&$orderData, $user, $goodsList)
    {
        // 当前用户收货城市id
        $cityId = $user['address_default'] ? $user['address_default']['city_id'] : null;
        // 初始化配送服务类
        $ExpressService = new ExpressService(
            static::$wxapp_id,
            $cityId,
            $goodsList,
            OrderTypeEnum::SHARING
        );
        // 获取不支持当前城市配送的商品
        $notInRuleGoods = $ExpressService->getNotInRuleGoods();
        // 验证商品是否在配送范围
        $intraRegion = $orderData['intra_region'] = $notInRuleGoods === false;
        if ($intraRegion == false) {
            $notInRuleGoodsName = $notInRuleGoods['goods_name'];
            $this->setError("很抱歉，您的收货地址不在商品 [{$notInRuleGoodsName}] 的配送范围内");
        } else {
            // 计算配送金额
            $ExpressService->setExpressPrice();
        }
        // 订单总运费金额
        $orderData['express_price'] = helper::number2($ExpressService->getTotalFreight());
    }

    /**
     * 创建新订单
     * @param $user
     * @param $order
     * @param $params
     * @return bool|mixed
     * @throws BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function createOrder($user, $order, $params)
    {
        // 如果是参与拼单，则记录拼单id
        $order['active_id'] = $params['active_id'];
        // 表单验证
        if (!$this->validateOrderForm($user, $order, $params['linkman'], $params['phone'])) {
            return false;
        }
        // 创建新的订单
        $status = $this->transaction(function () use ($order, $user, $params) {
            // 记录订单信息
            $status = $this->add($user['user_id'], $order, $params['remark']);
            // 记录收货地址
            $order['delivery'] == DeliveryTypeEnum::EXPRESS && $this->saveOrderAddress($user['user_id'], $order['address']);
            // 记录上门自提联系方式
            $order['delivery'] == DeliveryTypeEnum::EXTRACT && $this->saveOrderExtract($params['linkman'], $params['phone']);
            // 保存订单商品信息
            $this->saveOrderGoods($user['user_id'], $order);
            // 更新商品库存 (针对下单减库存的商品)
            $this->updateGoodsStockNum($order['goods_list']);
            // 获取订单详情
            $detail = self::getUserOrderDetail($this['order_id'], $user['user_id']);
            // 记录分销商订单
            if (SharingSettingModel::getItem('basic')['is_dealer']) {
                DealerOrderModel::createOrder($detail, OrderTypeEnum::SHARING);
            }
            return $status;
        });
        // 余额支付标记订单已支付
        if ($status && $order['pay_type'] == PayTypeEnum::BALANCE) {
            $this->onPaymentByBalance($this['order_no']);
        }
        return $status;
    }

    /**
     * 订单支付事件
     * @param int $payType
     * @return bool
     * @throws BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function onPay($payType = PayTypeEnum::WECHAT)
    {
        // 判断商品状态、库存
        if (!$this->checkGoodsStatusFromOrder($this['goods'])) {
            return false;
        }
        // 余额支付
        if ($payType == PayTypeEnum::BALANCE) {
            return $this->onPaymentByBalance($this['order_no']);
        }
        return true;
    }

    /**
     * 构建支付请求的参数
     * @param $user
     * @param $payType
     * @return array
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    public function onOrderPayment($user, $payType)
    {
        return ($payType == PayTypeEnum::WECHAT) ? $this->onPaymentByWechat($user) : [];
    }

    /**
     * 构建微信支付请求
     * @param UserModel $user
     * @return array
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    private function onPaymentByWechat($user)
    {
        return PaymentService::wechat(
            $user,
            $this['order_id'],
            $this['order_no'],
            $this['pay_price'],
            OrderTypeEnum::SHARING
        );
    }

    /**
     * 余额支付标记订单已支付
     * @param string $orderNo 订单号
     * @return bool
     * @throws BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function onPaymentByBalance($orderNo)
    {
        // 获取订单详情
        $model = new \app\task\model\sharing\Order;
        $order = $model->payDetail($orderNo);
        // 发起余额支付
        $status = $order->paySuccess(PayTypeEnum::BALANCE);
        if (!$status) {
            $this->error = $order->error;
        }
        return $status;
    }

    /**
     * 表单验证 (订单提交)
     * @param UserModel $user 用户信息
     * @param array $order 订单信息
     * @param string $linkman 联系人
     * @param string $phone 联系电话
     * @return bool
     * @throws \think\exception\DbException
     */
    private function validateOrderForm($user, &$order, $linkman, $phone)
    {
        if ($order['delivery'] == DeliveryTypeEnum::EXPRESS) {
            if (empty($order['address'])) {
                $this->error = '请先选择收货地址';
                return false;
            }
        }
        if ($order['delivery'] == DeliveryTypeEnum::EXTRACT) {
            if (empty($order['extract_shop'])) {
                $this->error = '请先选择自提门店';
                return false;
            }
            if (empty($linkman) || empty($phone)) {
                $this->error = '请填写联系人和电话';
                return false;
            }
        }
        // 余额支付时判断用户余额是否足够
        if ($order['pay_type'] == PayTypeEnum::BALANCE) {
            if ($user['balance'] < $order['order_pay_price']) {
                $this->error = '用户余额不足，无法使用余额支付';
                return false;
            }
        }
        // 验证拼单id是否合法
        if ($order['active_id'] > 0) {
            // 拼单详情
            $detail = Active::detail($order['active_id']);
            if (empty($detail)) {
                $this->error = '很抱歉，拼单不存在';
                return false;
            }
            // 验证当前拼单是否允许加入新成员
            if (!$detail->checkAllowJoin()) {
                $this->error = $detail->getError();
                return false;
            }
        }
        return true;
    }

    /**
     * 验证拼单是否允许加入
     * @param $active_id
     * @return bool
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    public function checkActiveIsAllowJoin($active_id)
    {
        // 拼单详情
        $detail = Active::detail($active_id);
        if (!$detail) {
            throw new BaseException('很抱歉，拼单不存在');
        }
        // 验证当前拼单是否允许加入新成员
        return $detail->checkAllowJoin();
    }

    /**
     * 新增订单记录
     * @param $user_id
     * @param $order
     * @param string $remark
     * @return false|int
     */
    private function add($user_id, &$order, $remark = '')
    {
        $data = [
            'user_id' => $user_id,
            'order_type' => $order['order_type'],
            'active_id' => $order['active_id'],
            'order_no' => $this->orderNo(),
            'total_price' => $order['order_total_price'],
            'order_price' => $order['order_price'],
            'coupon_id' => $order['coupon_id'],
            'coupon_money' => $order['coupon_money'],
            'pay_price' => $order['order_pay_price'],
            'delivery_type' => $order['delivery'],
            'pay_type' => $order['pay_type'],
            'buyer_remark' => trim($remark),
            'wxapp_id' => self::$wxapp_id,
        ];
        if ($order['delivery'] == DeliveryTypeEnum::EXPRESS) {
            $data['express_price'] = $order['express_price'];
        } elseif ($order['delivery'] == DeliveryTypeEnum::EXTRACT) {
            $data['extract_shop_id'] = $order['extract_shop']['shop_id'];
        }
        return $this->save($data);
    }

    /**
     * 保存订单商品信息
     * @param $userId
     * @param $order
     * @return int
     */
    private function saveOrderGoods($userId, &$order)
    {
        // 订单商品列表
        $goodsList = [];
        // 订单商品实付款金额 (不包含运费)
        $realTotalPrice = bcsub($order['order_pay_price'], $order['express_price'], 2);
        foreach ($order['goods_list'] as $goods) {
            /* @var GoodsModel $goods */
            $goodsList[] = [
                'user_id' => $userId,
                'wxapp_id' => self::$wxapp_id,
                'goods_id' => $goods['goods_id'],
                'goods_name' => $goods['goods_name'],
                'image_id' => $goods['image'][0]['image_id'],
                'selling_point' => $goods['selling_point'],
                'people' => $goods['people'],
                'group_time' => $goods['group_time'],
                'is_alone' => $goods['is_alone'],
                'deduct_stock_type' => $goods['deduct_stock_type'],
                'spec_type' => $goods['spec_type'],
                'spec_sku_id' => $goods['goods_sku']['spec_sku_id'],
                'goods_sku_id' => $goods['goods_sku']['goods_sku_id'],
                'goods_attr' => $goods['goods_sku']['goods_attr'],
                'content' => $goods['content'],
                'goods_no' => $goods['goods_sku']['goods_no'],
                'goods_price' => $goods['goods_sku']['goods_price'],
                'line_price' => $goods['goods_sku']['line_price'],
                'goods_weight' => $goods['goods_sku']['goods_weight'],
                'is_user_grade' => (int)$goods['is_user_grade'],
                'grade_ratio' => $goods['grade_ratio'],
                'grade_goods_price' => $goods['grade_goods_price'],
                'grade_total_money' => $goods['grade_total_money'],
                'coupon_money' => $goods['coupon_money'],
                'total_num' => $goods['total_num'],
                'total_price' => $goods['total_price'],
                'total_pay_price' => $goods['total_pay_price'],
                'is_ind_dealer' => $goods['is_ind_dealer'],
                'dealer_money_type' => $goods['dealer_money_type'],
                'first_money' => $goods['first_money'],
                'second_money' => $goods['second_money'],
                'third_money' => $goods['third_money'],
            ];
        }
        return $this->goods()->saveAll($goodsList);
    }

    /**
     * 更新商品库存 (针对下单减库存的商品)
     * @param $goods_list
     * @throws \Exception
     */
    private function updateGoodsStockNum($goods_list)
    {
        $deductStockData = [];
        foreach ($goods_list as $goods) {
            // 下单减库存
            $goods['deduct_stock_type'] == 10 && $deductStockData[] = [
                'goods_sku_id' => $goods['goods_sku']['goods_sku_id'],
                'stock_num' => ['dec', $goods['total_num']]
            ];
        }
        !empty($deductStockData) && (new GoodsSkuModel)->isUpdate()->saveAll($deductStockData);
    }

    /**
     * 记录收货地址
     * @param $user_id
     * @param $address
     * @return false|\think\Model
     */
    private function saveOrderAddress($user_id, $address)
    {
        if ($address['region_id'] == 0 && !empty($address['district'])) {
            $address['detail'] = $address['district'] . ' ' . $address['detail'];
        }
        return $this->address()->save([
            'user_id' => $user_id,
            'wxapp_id' => self::$wxapp_id,
            'name' => $address['name'],
            'phone' => $address['phone'],
            'province_id' => $address['province_id'],
            'city_id' => $address['city_id'],
            'region_id' => $address['region_id'],
            'detail' => $address['detail'],
        ]);
    }

    /**
     * 保存上门自提联系人
     * @param $linkman
     * @param $phone
     * @return false|\think\Model
     */
    public function saveOrderExtract($linkman, $phone)
    {
        // 记忆上门自提联系人(缓存)，用于下次自动填写
        UserService::setLastExtract($this['user_id'], trim($linkman), trim($phone));
        // 保存上门自提联系人(数据库)
        return $this->extract()->save([
            'linkman' => trim($linkman),
            'phone' => trim($phone),
            'user_id' => $this['user_id'],
            'wxapp_id' => self::$wxapp_id,
        ]);
    }

    /**
     * 用户拼团订单列表
     * @param $user_id
     * @param string $type
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($user_id, $type = 'all')
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                // 全部
                break;
            case 'payment';
                // 待支付
                $filter['pay_status'] = PayStatusEnum::PENDING;
                break;
            case 'sharing';
                // 拼团中
                $filter['active.status'] = 10;
                break;
            case 'delivery';
                // 待发货
                $this->where('IF ( (`order`.`order_type` = 20), (`active`.`status` = 20), TRUE)');
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 10;
                break;
            case 'received';
                // 待收货
                $filter['pay_status'] = 20;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 10;
                break;
            case 'comment';
                $filter['order_status'] = 30;
                $filter['is_comment'] = 0;
                break;
        }
        return $this->with(['goods.image', 'active'])
            ->alias('order')
            ->field('order.*, active.status as active_status')
            ->join('sharing_active active', 'order.active_id = active.active_id', 'LEFT')
            ->where('user_id', '=', $user_id)
            ->where($filter)
            ->where('order.is_delete', '=', 0)
            ->order(['create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => \request()->request()
            ]);
    }

    /**
     * 取消订单
     * @return bool|false|int
     */
    public function cancel()
    {
        if ($this['delivery_status']['value'] == 20) {
            $this->error = '已发货订单不可取消';
            return false;
        }
        if ($this['order_type']['value'] == 20) {
            $this->error = '拼团订单不允许取消';
            return false;
        }
        // 订单取消事件
        $this->transaction(function () {
            // 回退商品库存
            (new OrderGoodsModel)->backGoodsStock($this['goods']);
            // 未付款的订单
            if ($this['pay_status']['value'] != PayStatusEnum::SUCCESS) {
                // 回退用户优惠券
                $this['coupon_id'] > 0 && UserCouponModel::setIsUse($this['coupon_id'], false);
            }
            // 更新订单状态
            $this->save(['order_status' => $this['pay_status']['value'] == PayStatusEnum::SUCCESS ? 21 : 20]);
        });
        return true;
    }

    /**
     * 确认收货
     * @return bool|mixed
     */
    public function receipt()
    {
        // 验证订单是否合法
        // 条件1: 订单必须已发货
        // 条件2: 订单必须未收货
        if ($this['delivery_status']['value'] != 20 || $this['receipt_status']['value'] != 10) {
            $this->error = '该订单不合法';
            return false;
        }
        return $this->transaction(function () {
            $orderData = [];
            // 累积用户实际消费金额
            // 条件：后台订单流程设置 - 已完成订单设置0天不允许申请售后
            if (SettingModel::getItem('trade')['order']['refund_days'] == 0) {
                (new UserModel)->setIncUserExpend($this['user_id'], $this['pay_price']);
                $orderData['is_user_expend'] = 1;
            }
            // 更新订单状态
            $status = $this->save(array_merge($orderData, [
                'receipt_status' => 20,
                'receipt_time' => time(),
                'order_status' => 30
            ]));
            // 发放分销商佣金
            DealerOrderModel::grantMoney($this, OrderTypeEnum::SHARING);
            return $status;
        });
    }

    /**
     * 获取订单总数
     * @param $user_id
     * @param string $type
     * @return int|string
     * @throws \think\Exception
     */
    public function getCount($user_id, $type = 'all')
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment';
                $filter['pay_status'] = PayStatusEnum::PENDING;
                break;
            case 'received';
                $filter['pay_status'] = PayStatusEnum::SUCCESS;
                $filter['delivery_status'] = 20;
                $filter['receipt_status'] = 10;
                break;
            case 'comment';
                $filter['order_status'] = 30;
                $filter['is_comment'] = 0;
                break;
        }
        return $this->where('user_id', '=', $user_id)
            ->where('order_status', '<>', 20)
            ->where($filter)
            ->where('is_delete', '=', 0)
            ->count();
    }

    /**
     * 订单详情
     * @param $order_id
     * @param $user_id
     * @return array|false|\PDOStatement|string|\think\Model|static
     * @throws BaseException
     */
    public static function getUserOrderDetail($order_id, $user_id)
    {
        $order = (new static)->with(['goods' => ['image', 'refund'], 'address', 'express', 'extract_shop'])
            ->alias('order')
            ->field('order.*, active.status as active_status')
            ->join('sharing_active active', 'order.active_id = active.active_id', 'LEFT')
            ->where([
                'order_id' => $order_id,
                'user_id' => $user_id,
//                'order_status' => ['<>', 20]
            ])->find();
        if (!$order) {
            throw new BaseException(['msg' => '订单不存在']);
        }
        return $order;
    }

    /**
     * 判断商品库存不足 (未付款订单)
     * @param $goodsList
     * @return bool
     */
    public function checkGoodsStatusFromOrder(&$goodsList)
    {
        foreach ($goodsList as $goods) {
            // 判断商品是否下架
            if (!$goods['goods'] || $goods['goods']['goods_status']['value'] != 10) {
                $this->setError('很抱歉，商品 [' . $goods['goods_name'] . '] 已下架');
                return false;
            }
            // 付款减库存
            if ($goods['deduct_stock_type'] == 20 && $goods['sku']['stock_num'] < 1) {
                $this->setError('很抱歉，商品 [' . $goods['goods_name'] . '] 库存不足');
                return false;
            }
        }
        return true;
    }

    /**
     * 当前订单是否允许申请售后
     * @return bool
     */
    public function isAllowRefund()
    {
        // 允许申请售后期限
        $refund_days = SettingModel::getItem('trade')['order']['refund_days'];
        if ($refund_days == 0) {
            return false;
        }
        if (time() > $this['receipt_time'] + ((int)$refund_days * 86400)) {
            return false;
        }
        if ($this['receipt_status']['value'] != 20) {
            return false;
        }
        return true;
    }

    /**
     * 判断当前订单是否允许核销
     * @param static $order
     * @return bool
     */
    public function checkExtractOrder(&$order)
    {
        if (
            $order['pay_status']['value'] == PayStatusEnum::SUCCESS
            && $order['delivery_type']['value'] == DeliveryTypeEnum::EXTRACT
            && $order['delivery_status']['value'] == 10
            // 拼团订单验证拼单状态
            && ($order['order_type']['value'] == 20 ? $order['active']['status']['value'] == 20 : true)
        ) {
            return true;
        }
        $this->setError('该订单不能被核销');
        return false;
    }

    /**
     * 设置错误信息
     * @param $error
     */
    private function setError($error)
    {
        empty($this->error) && $this->error = $error;
    }

    /**
     * 是否存在错误
     * @return bool
     */
    public function hasError()
    {
        return !empty($this->error);
    }

}
