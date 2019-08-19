<?php

namespace app\api\model;

use app\common\model\WxappPage as WxappPageModel;

use app\api\model\Goods as GoodsModel;
use app\api\model\sharing\Goods as SharingGoodsModel;
use app\api\model\store\Shop as ShopModel;

/**
 * 微信小程序diy页面模型
 * Class WxappPage
 * @package app\api\model
 */
class WxappPage extends WxappPageModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'create_time',
        'update_time'
    ];

    /**
     * DIY页面详情
     * @param User $user
     * @param int $page_id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getPageData($user, $page_id = null)
    {
        // 页面详情
        $detail = $page_id > 0 ? parent::detail($page_id) : parent::getHomePage();
        // 页面diy元素
        $items = $detail['page_data']['items'];
        // 页面顶部导航
        isset($detail['page_data']['page']) && $items['page'] = $detail['page_data']['page'];
        // 获取动态数据
        $model = new self;
        foreach ($items as $key => $item) {
            if ($item['type'] === 'window') {
                $items[$key]['data'] = array_values($item['data']);
            } else if ($item['type'] === 'goods') {
                $items[$key]['data'] = $model->getGoodsList($user, $item);
            } else if ($item['type'] === 'sharingGoods') {
                $items[$key]['data'] = $model->getSharingGoodsList($user, $item);
            } else if ($item['type'] === 'coupon') {
                $items[$key]['data'] = $model->getCouponList($user, $item);
            } else if ($item['type'] === 'article') {
                $items[$key]['data'] = $model->getArticleList($item);
            } else if ($item['type'] === 'special') {
                $items[$key]['data'] = $model->getSpecialList($item);
            } else if ($item['type'] === 'shop') {
                $items[$key]['data'] = $model->getShopList($item);
            }
        }
        return ['page' => $items['page'], 'items' => $items];
    }

    /**
     * 商品组件：获取商品列表
     * @param $user
     * @param $item
     * @return array
     * @throws \think\exception\DbException
     */
    private function getGoodsList($user, $item)
    {
        // 获取商品数据
        $model = new GoodsModel;
        if ($item['params']['source'] === 'choice') {
            // 数据来源：手动
            $goodsIds = array_column($item['data'], 'goods_id');
            $goodsList = $model->getListByIdsFromApi($goodsIds, $user);
        } else {
            // 数据来源：自动
            $goodsList = $model->getList([
                'status' => 10,
                'category_id' => $item['params']['auto']['category'],
                'sortType' => $item['params']['auto']['goodsSort'],
                'listRows' => $item['params']['auto']['showNum']
            ], $user);
        }
        if ($goodsList->isEmpty()) return [];
        // 格式化商品列表
        $data = [];
        foreach ($goodsList as $goods) {
            $data[] = [
                'goods_id' => $goods['goods_id'],
                'goods_name' => $goods['goods_name'],
                'selling_point' => $goods['selling_point'],
                'image' => $goods['image'][0]['file_path'],
                'goods_image' => $goods['image'][0]['file_path'],
                'goods_price' => $goods['sku'][0]['goods_price'],
                'line_price' => $goods['sku'][0]['line_price'],
                'goods_sales' => $goods['goods_sales'],
            ];
        }
        return $data;
    }

    /**
     * 商品组件：获取拼团商品列表
     * @param $user
     * @param $item
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getSharingGoodsList($user, $item)
    {
        // 获取商品数据
        $model = new SharingGoodsModel;
        if ($item['params']['source'] === 'choice') {
            // 数据来源：手动
            $goodsIds = array_column($item['data'], 'goods_id');
            $goodsList = $model->getListByIdsFromApi($goodsIds, $user);
        } else {
            // 数据来源：自动
            $goodsList = $model->getList([
                'status' => 10,
                'category_id' => $item['params']['auto']['category'],
                'sortType' => $item['params']['auto']['goodsSort'],
                'listRows' => $item['params']['auto']['showNum']
            ], $user);
        }
        if ($goodsList->isEmpty()) return [];
        // 格式化商品列表
        $data = [];
        foreach ($goodsList as $goods) {
            $data[] = [
                'goods_id' => $goods['goods_id'],
                'goods_name' => $goods['goods_name'],
                'selling_point' => $goods['selling_point'],
                'people' => $goods['people'],
                'goods_sales' => $goods['goods_sales'],
                'image' => $goods['image'][0]['file_path'],
                'goods_image' => $goods['image'][0]['file_path'],
                'sharing_price' => $goods['sku'][0]['sharing_price'],
                'goods_price' => $goods['sku'][0]['goods_price'],
                'line_price' => $goods['sku'][0]['line_price'],
            ];
        }
        return $data;
    }

    /**
     * 优惠券组件：获取优惠券列表
     * @param $user
     * @param $item
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getCouponList($user, $item)
    {
        // 获取优惠券数据
        return (new Coupon)->getList($user, $item['params']['limit'], true);
    }

    /**
     * 文章组件：获取文章列表
     * @param $item
     * @return array
     * @throws \think\exception\DbException
     */
    private function getArticleList($item)
    {
        // 获取文章数据
        $model = new Article;
        $articleList = $model->getList($item['params']['auto']['category'], $item['params']['auto']['showNum']);
        return $articleList->isEmpty() ? [] : $articleList->toArray()['data'];
    }

    /**
     * 头条快报：获取头条列表
     * @param $item
     * @return array
     * @throws \think\exception\DbException
     */
    private function getSpecialList($item)
    {
        // 获取头条数据
        $model = new Article;
        $articleList = $model->getList($item['params']['auto']['category'], $item['params']['auto']['showNum']);
        return $articleList->isEmpty() ? [] : $articleList->toArray()['data'];
    }

    /**
     * 线下门店组件：获取门店列表
     * @param $item
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getShopList($item)
    {
        // 获取商品数据
        $model = new ShopModel;
        if ($item['params']['source'] === 'choice') {
            // 数据来源：手动
            $shopIds = array_column($item['data'], 'shop_id');
            $shopList = $model->getListByIds($shopIds);
        } else {
            // 数据来源：自动
            $shopList = $model->getList(null, false, false, $item['params']['auto']['showNum']);
        }
        if ($shopList->isEmpty()) return [];
        // 格式化商品列表
        $data = [];
        foreach ($shopList as $shop) {
            $data[] = [
                'shop_id' => $shop['shop_id'],
                'shop_name' => $shop['shop_name'],
                'logo_image' => $shop['logo']['file_path'],
                'phone' => $shop['phone'],
                'region' => $shop['region'],
                'address' => $shop['address'],
            ];
        }
        return $data;
    }

}
