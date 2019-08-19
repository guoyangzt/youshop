<?php

namespace app\api\service\coupon;

use app\common\library\helper;

class GoodsDeduct
{
    private $actualReducedMoney;

    public function setGoodsCouponMoney($goodsList, $reducedMoney)
    {
        // 统计订单商品总金额,(单位分)
        $orderTotalPrice = 0;
        foreach ($goodsList as &$goods) {
            $goods['total_price'] *= 100;
            $orderTotalPrice += $goods['total_price'];
        }
        // 计算实际抵扣金额
        $this->setActualReducedMoney($reducedMoney, $orderTotalPrice);
        // 实际抵扣金额为0，
        if ($this->actualReducedMoney > 0) {
            $goodsList = $this->getGoodsListWeight($goodsList, $orderTotalPrice);
            $this->setGoodsListCouponMoney($goodsList);
            $totalCouponMoney = helper::getArrayColumnSum($goodsList, 'coupon_money');
            $this->setGoodsListCouponMoneyFill($goodsList, $totalCouponMoney);
            $this->setGoodsListCouponMoneyDiff($goodsList, $totalCouponMoney);
        }
        return $goodsList;
    }

    public function getActualReducedMoney()
    {
        return $this->actualReducedMoney;
    }

    private function setActualReducedMoney($reducedMoney, $orderTotalPrice)
    {
        $reducedMoney *= 100;
        $this->actualReducedMoney = ($reducedMoney >= $orderTotalPrice) ? $orderTotalPrice - 1 : $reducedMoney;
    }

    private function arraySortByWeight($goodsList)
    {
        return array_sort($goodsList, 'weight', true);
    }

    private function getGoodsListWeight($goodsList, $orderTotalPrice)
    {
        foreach ($goodsList as &$goods) {
            $goods['weight'] = $goods['total_price'] / $orderTotalPrice;
        }
        return $this->arraySortByWeight($goodsList);
    }


    private function setGoodsListCouponMoney(&$goodsList)
    {
        foreach ($goodsList as &$goods) {
            $goods['coupon_money'] = bcmul($this->actualReducedMoney, $goods['weight']);
        }
        return true;
    }

    private function setGoodsListCouponMoneyFill(&$goodsList, $totalCouponMoney)
    {
        if ($totalCouponMoney === 0) {
            $temReducedMoney = $this->actualReducedMoney;
            foreach ($goodsList as &$goods) {
                if ($temReducedMoney === 0) break;
                $goods['coupon_money'] = 1;
                $temReducedMoney--;
            }
        }
        return true;
    }

    private function setGoodsListCouponMoneyDiff(&$goodsList, $totalCouponMoney)
    {
        $tempDiff = $this->actualReducedMoney - $totalCouponMoney;
        foreach ($goodsList as &$goods) {
            if ($tempDiff < 1) break;
            $goods['coupon_money']++ && $tempDiff--;
        }
        return true;
    }

}