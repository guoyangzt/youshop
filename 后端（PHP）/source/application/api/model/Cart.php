<?php

namespace app\api\model;

use think\Cache;
use app\api\model\Goods as GoodsModel;

use app\common\library\helper;
use app\common\service\wechat\wow\Shoping as WowService;

/**
 * 购物车管理
 * Class Cart
 * @package app\api\model
 */
class Cart
{
    /* @var string $error 错误信息 */
    public $error = '';

    /* @var \think\Model|\think\Collection $user 用户信息 */
    private $user;

    /* @var int $user_id 用户id */
    private $user_id;

    /* @var int $wxapp_id 小程序商城id */
    private $wxapp_id;

    /* @var array $cart 购物车列表 */
    private $cart = [];

    /* @var bool $clear 是否清空购物车 */
    private $clear = false;

    /**
     * 构造方法
     * Cart constructor.
     * @param \think\Model|\think\Collection $user
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->user_id = $this->user['user_id'];
        $this->wxapp_id = $this->user['wxapp_id'];
        $this->cart = Cache::get('cart_' . $this->user_id) ?: [];
    }

    /**
     * 购物车列表 (含商品信息)
     * @return array
     * @param string $cartIds 请求参数
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($cartIds)
    {
        // 获取购物车商品列表
        return $this->getOrderGoodsList($cartIds);
    }

    /**
     * 获取购物车列表
     * @param string|null $cartIds 购物车索引集 (为null时则获取全部)
     * @return array
     */
    public function getCartList($cartIds = null)
    {
        if (empty($cartIds)) return $this->cart;
        $cartList = [];
        $indexArr = (strpos($cartIds, ',') !== false) ? explode(',', $cartIds) : [$cartIds];
        foreach ($indexArr as $index) {
            isset($this->cart[$index]) && $cartList[$index] = $this->cart[$index];
        }
        return $cartList;
    }

    /**
     * 获取购物车中的商品列表
     * @param $cartIds
     * @return array|bool
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getOrderGoodsList($cartIds)
    {
        // 购物车商品列表
        $goodsList = [];
        // 获取购物车列表
        $cartList = $this->getCartList($cartIds);
        if (empty($cartList)) {
            $this->setError('当前购物车没有商品');
            return $goodsList;
        }
        // 购物车中所有商品id集
        $goodsIds = array_unique(helper::getArrayColumn($cartList, 'goods_id'));
        // 获取并格式化商品数据
        $sourceData = (new GoodsModel)->getListByIds($goodsIds);
        $sourceData = helper::arrayColumn2Key($sourceData, 'goods_id');
        // 格式化购物车数据列表
        foreach ($cartList as $key => $item) {
            // 判断商品不存在则自动删除
            if (!isset($sourceData[$item['goods_id']])) {
                $this->delete($key);
                continue;
            }
            /* @var GoodsModel $goods 商品信息 */
            $goods = &$sourceData[$item['goods_id']];
            // 判断商品是否已删除
            if ($goods['is_delete']) {
                $this->delete($key);
                continue;
            }
            // 商品sku信息
            $goods['goods_sku'] = GoodsModel::getGoodsSku($goods, $item['goods_sku_id']);
            $goods['goods_sku_id'] = $item['goods_sku_id'];
            // 商品sku不存在则自动删除
            if (empty($goods['goods_sku'])) {
                $this->delete($key);
                continue;
            }
            // 商品单价
            $goods['goods_price'] = $goods['goods_sku']['goods_price'];
            // 购买数量
            $goods['total_num'] = $item['goods_num'];
            // 商品总价
            $goods['total_price'] = bcmul($goods['goods_price'], $item['goods_num'], 2);
            $goodsList[] = $goods->toArray();
        }
        return $goodsList;
    }

    /**
     * 加入购物车
     * @param int $goodsId 商品id
     * @param int $goodsNum 加入购物车的数量
     * @param string $goodsSkuId 商品sku索引
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function add($goodsId, $goodsNum, $goodsSkuId)
    {
        // 购物车商品索引
        $index = "{$goodsId}_{$goodsSkuId}";
        // 加入购物车后的商品数量
        $cartGoodsNum = $goodsNum + (isset($this->cart[$index]) ? $this->cart[$index]['goods_num'] : 0);
        // 获取商品信息
        $goods = GoodsModel::detail($goodsId);
        // 验证商品能否加入
        if (!$this->checkGoods($goods, $goodsSkuId, $cartGoodsNum)) {
            return false;
        }
        // 将商品同步到好物圈
        if (!$this->isExistGoodsId($goodsId)) {
            (new WowService($this->wxapp_id))->add($this->user, [$goods]);
        }
        // 记录到购物车列表
        $this->cart[$index] = [
            'goods_id' => $goodsId,
            'goods_num' => $cartGoodsNum,
            'goods_sku_id' => $goodsSkuId,
            'create_time' => time()
        ];
        return true;
    }

    /**
     * 验证购物车中是否存在某商品
     * @param $goodsId
     * @return bool
     */
    private function isExistGoodsId($goodsId)
    {
        foreach ($this->cart as $item) {
            if ($item['goods_id'] == $goodsId) return true;
        }
        return false;
    }

    /**
     * 验证商品是否可以购买
     * @param GoodsModel $goods 商品信息
     * @param string $goodsSkuId 商品sku索引
     * @param $cartGoodsNum
     * @return bool
     */
    private function checkGoods($goods, $goodsSkuId, $cartGoodsNum)
    {
        // 判断商品是否下架
        if (!$goods || $goods['is_delete'] || $goods['goods_status']['value'] != 10) {
            $this->setError('很抱歉，商品信息不存在或已下架');
            return false;
        }
        // 商品sku信息
        $goods['goods_sku'] = GoodsModel::getGoodsSku($goods, $goodsSkuId);
        // 判断商品库存
        if ($cartGoodsNum > $goods['goods_sku']['stock_num']) {
            $this->setError('很抱歉，商品库存不足');
            return false;
        }
        return true;
    }

    /**
     * 减少购物车中某商品数量
     * @param int $goodsId
     * @param string $goodsSkuId
     */
    public function sub($goodsId, $goodsSkuId)
    {
        $index = "{$goodsId}_{$goodsSkuId}";
        $this->cart[$index]['goods_num'] > 1 && $this->cart[$index]['goods_num']--;
    }

    /**
     * 删除购物车中指定商品
     * @param string $cartIds (支持字符串ID集)
     */
    public function delete($cartIds)
    {
        $indexArr = strpos($cartIds, ',') !== false ? explode(',', $cartIds) : [$cartIds];
        foreach ($indexArr as $index) {
            if (isset($this->cart[$index])) unset($this->cart[$index]);
        }
    }

    /**
     * 获取当前用户购物车商品总数量(含件数)
     * @return int
     */
    public function getTotalNum()
    {
        return helper::getArrayColumnSum($this->cart, 'goods_num');
    }

    /**
     * 获取当前用户购物车商品总数量(不含件数)
     * @return int
     */
    public function getGoodsNum()
    {
        return count($this->cart);
    }

    /**
     * 析构方法
     * 将cart数据保存到缓存文件
     */
    public function __destruct()
    {
        $this->clear !== true && Cache::set('cart_' . $this->user_id, $this->cart, 86400 * 15);
    }

    /**
     * 清空当前用户购物车
     * @param null $cartIds
     */
    public function clearAll($cartIds = null)
    {
        if (empty($cartIds)) {
            $this->clear = true;
            Cache::rm('cart_' . $this->user_id);
        } else {
            $this->delete($cartIds);
        }
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
     * 获取错误信息
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

}
