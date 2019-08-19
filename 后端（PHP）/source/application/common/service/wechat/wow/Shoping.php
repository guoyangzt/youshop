<?php

namespace app\common\service\wechat\wow;

use app\common\model\Wxapp as WxappModel;
use app\common\model\wow\Shoping as ShopingModel;
use app\common\model\wow\Setting as SettingModel;
use app\common\library\wechat\wow\Shoping as WowShoping;
use app\common\library\helper;

/**
 * 好物圈-商品收藏 服务类
 * Class Shoping
 * @package app\common\service\wechat\wow
 */
class Shoping
{
    /* @var int $wxapp_id 小程序商城id */
    private $wxappId;

    /* @var WowShoping $ApiDriver 微信api驱动 */
    private $ApiDriver;

    protected $error;

    /**
     * 构造方法
     * Shoping constructor.
     * @param $wxappId
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function __construct($wxappId)
    {
        $this->wxappId = $wxappId;
        $this->initApiDriver();
    }

    /**
     * 添加好物圈商品收藏
     * @param \think\Collection $user 用户信息
     * @param array $goodsList 商品列表
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function add($user, $goodsList)
    {
        // 判断是否开启同步设置
        $setting = SettingModel::getItem('basic', $this->wxappId);
        if ($setting['is_shopping'] == false) {
            return false;
        }
        // 整理商品列表
        $productList = $this->getProductListToAdd($goodsList);
        // 执行api请求
        $status = $this->ApiDriver->addList($user['open_id'], $productList);
        if ($status == false) {
            $this->error = $this->ApiDriver->getError();
            return $status;
        }
        // 写入商品收藏记录
        $goodsIds = helper::getArrayColumn($goodsList, 'goods_id');
        $this->model()->add($user['user_id'], $goodsIds);
        return $status;
    }

    /**
     * 删除好物圈商品收藏
     * @param $id
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function delete($id)
    {
        // 实例化模型
        $model = $this->model($id, ['user']);
        // 执行api请求
        $status = $this->ApiDriver->delete($model['user']['open_id'], [[
            'item_code' => $model['goods_id'],
            'sku_id' => $model['goods_id'],
        ]]);
        if ($status == false) {
            $this->error = $this->ApiDriver->getError();
        }
        // 删除商品收藏记录
        $model->setDelete();
        return true;
    }

    /**
     * 返回错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 实例化微信api驱动
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    private function initApiDriver()
    {
        $config = WxappModel::getWxappCache($this->wxappId);
        $this->ApiDriver = new WowShoping($config['app_id'], $config['app_secret']);
    }

    /**
     * 获取好物圈订单记录模型
     * @param int|null $id
     * @param array $with
     * @return ShopingModel|null
     * @throws \think\exception\DbException
     */
    private function model($id = null, $with = ['user'])
    {
        static $model;
        if (!$model instanceof ShopingModel) {
            $model = $id > 0 ? ShopingModel::detail($id, $with) : (new ShopingModel);
        }
        return $model;
    }

    /**
     * 整理商品列表 (用于添加收藏接口)
     * @param $goodsList
     * @return array
     */
    private function getProductListToAdd(&$goodsList)
    {
        // 整理api参数
        $productList = [];
        foreach ($goodsList as $goods) {
            $imageList = [];    // 商品图片
            foreach ($goods['image'] as $image) {
                $imageList[] = $image['file_path'];
            }
            // sku信息
            $skuInfo = &$goods['sku'][0];
            $productList[] = [
                'item_code' => $goods['goods_id'],
                'title' => $goods['goods_name'],
                'category_list' => [$goods['category']['name']],
                'image_list' => $imageList,
                'src_wxapp_path' => "/pages/goods/index?goods_id={$goods['goods_id']}", // 商品页面路径
                'sku_info' => [     // 商品sku
//                    'sku_id' => "{$goods['goods_id']}_{$skuInfo['spec_sku_id']}",
                    'sku_id' => $goods['goods_id'],
                    'price' => $skuInfo['goods_price'] * 100,
                    'original_price' => $skuInfo['line_price'] * 100,   // 划线价
                    'status' => 1,
                ],
            ];
        }
        return $productList;
    }

}