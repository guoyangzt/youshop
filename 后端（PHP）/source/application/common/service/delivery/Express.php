<?php

namespace app\common\service\delivery;

use app\common\library\helper;
use app\common\model\Setting as SettingModel;
use app\common\enum\OrderType as OrderTypeEnum;

/**
 * 快递配送服务类
 * Class Delivery
 * @package app\common\service
 */
class Express
{
    private $wxappId;   // 小程序商城id
    private $cityId;    // 用户收货城市id
    private $goodsList;  // 订单商品列表
    private $orderType;  // 订单类型 (主商城、拼团)

    /**
     * 配送服务类构造方法
     * Delivery constructor.
     * @param int $wxappId 小程序商城id
     * @param int $cityId 用户收货城市id
     * @param array $goodsList 订单商品列表
     * @param int $orderType 订单商品列表
     */
    public function __construct(
        $wxappId,
        $cityId,
        &$goodsList,
        $orderType = OrderTypeEnum::MASTER
    )
    {
        $this->wxappId = $wxappId;
        $this->cityId = $cityId;
        $this->goodsList = $goodsList;
        $this->orderType = $orderType;
    }

    /**
     * 根据用户收货城市id 验证是否在商品配送规则中
     * 如果不存在则返回该商品，如果存在返回false
     * @return bool
     */
    public function getNotInRuleGoods()
    {
        if ($this->cityId) {
            foreach ($this->goodsList as &$goods) {
                $cityIds = [];
                foreach ($goods['delivery']['rule'] as $item)
                    $cityIds = array_merge($cityIds, $item['region_data']);
                if (!in_array($this->cityId, $cityIds))
                    return $goods;
            }
        }
        return false;
    }

    /**
     * 设置订单商品的运费
     * @return bool
     */
    public function setExpressPrice()
    {
        // 订单商品总金额
        $orderTotalPrice = helper::getArrayColumnSum($this->goodsList, 'total_price');
        foreach ($this->goodsList as &$goods) {
            $goods['express_price'] = $this->onCalcGoodsfreight($goods, $orderTotalPrice);
        }
        return true;
    }

    /**
     * 获取订单最终运费
     * @return double
     */
    public function getTotalFreight()
    {
        if (empty($this->goodsList)) {
            return 0.00;
        }
        // 所有商品的运费金额
        $expressPriceArr = array_column($this->goodsList, 'express_price');
        if (empty($expressPriceArr)) {
            return 0.00;
        }
        // 计算最终运费
        return $this->freightRule($expressPriceArr);
    }

    /**
     * 计算商品的配送费用
     * @param int $goods 商品id
     * @param double $orderTotalPrice 订单总金额
     * @return double
     */
    private function onCalcGoodsfreight(&$goods, $orderTotalPrice)
    {
        // 判断是否满足满额包邮条件
        if ($this->isFullFree($goods['goods_id'], $orderTotalPrice)) {
            return 0.00;
        }
        // 当前收货城市配送规则
        $rule = $this->getCityDeliveryRule($goods);
        // 商品总重量
        $totalWeight = helper::bcmul($goods['goods_sku']['goods_weight'], $goods['total_num']);
        // 商品总数量or总重量
        $total = $goods['delivery']['method']['value'] == 10 ? $goods['total_num'] : $totalWeight;
        if ($total <= $rule['first']) {
            return helper::number2($rule['first_fee']);
        }
        // 续件or续重 数量
        $additional = $total - $rule['first'];
        if ($additional <= $rule['additional']) {
            return helper::number2(helper::bcadd($rule['first_fee'], $rule['additional_fee']));
        }
        // 计算续重/件金额
        if ($rule['additional'] < 1) {
            // 配送规则中续件为0
            $additionalFee = 0.00;
        } else {
            $additionalFee = helper::bcdiv($rule['additional_fee'], $rule['additional']) * $additional;
        }
        return helper::number2(helper::bcadd($rule['first_fee'], $additionalFee));
    }

    /**
     * 判断是否满足满额包邮条件
     * @param int $goodsId 商品id
     * @param double $orderTotalPrice 订单总金额
     * @return bool
     */
    private function isFullFree($goodsId, $orderTotalPrice)
    {
        // 非商城主订单不参与满额包邮
        if ($this->orderType !== OrderTypeEnum::MASTER) {
            return false;
        }
        // 获取满额包邮设置
        $options = SettingModel::getItem('full_free', $this->wxappId);
        if (
            $options['is_open'] == false
            || $orderTotalPrice < $options['money']
            || in_array($goodsId, $options['notin_goods'])
            || in_array($this->cityId, $options['notin_region']['citys'])
        ) {
            return false;
        }
        return true;
    }

    /**
     * 根据城市id获取规则信息
     * @param
     * @return array|false
     */
    private function getCityDeliveryRule(&$goods)
    {
        foreach ($goods['delivery']['rule'] as $item) {
            if (in_array($this->cityId, $item['region_data'])) {
                return $item;
            }
        }
        return false;
    }

    /**
     * 根据运费组合策略 计算最终运费
     * @param array $expressPriceArr 全部商品运费
     * @return double
     */
    private function freightRule($expressPriceArr)
    {
        $expressPrice = 0.00;
        switch (SettingModel::getItem('trade', $this->wxappId)['freight_rule']) {
            case '10':    // 策略1: 叠加
                $expressPrice = array_sum($expressPriceArr);
                break;
            case '20':    // 策略2: 以最低运费结算
                $expressPrice = min($expressPriceArr);
                break;
            case '30':    // 策略3: 以最高运费结算
                $expressPrice = max($expressPriceArr);
                break;
        }
        return $expressPrice;
    }

}