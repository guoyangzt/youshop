<?php

namespace app\common\model;

use app\common\model\GoodsSku as GoodsSkuModel;
use app\common\enum\goods\DeductStockType as DeductStockTypeEnum;

/**
 * 订单商品模型
 * Class OrderGoods
 * @package app\common\model
 */
class OrderGoods extends BaseModel
{
    protected $name = 'order_goods';
    protected $updateTime = false;

    /**
     * 订单商品列表
     * @return \think\model\relation\BelongsTo
     */
    public function image()
    {
        $model = "app\\common\\model\\UploadFile";
        return $this->belongsTo($model, 'image_id', 'file_id');
    }

    /**
     * 关联商品表
     * @return \think\model\relation\BelongsTo
     */
    public function goods()
    {
        return $this->belongsTo('Goods');
    }

    /**
     * 关联商品sku表
     * @return \think\model\relation\BelongsTo
     */
    public function sku()
    {
        return $this->belongsTo('GoodsSku', 'spec_sku_id', 'spec_sku_id');
    }

    /**
     * 关联订单主表
     * @return \think\model\relation\BelongsTo
     */
    public function orderM()
    {
        return $this->belongsTo('Order');
    }

    /**
     * 售后单记录表
     * @return \think\model\relation\HasOne
     */
    public function refund()
    {
        return $this->hasOne('OrderRefund');
    }

    /**
     * 订单商品详情
     * @param $where
     * @return OrderGoods|null
     * @throws \think\exception\DbException
     */
    public static function detail($where)
    {
        return static::get($where, ['image', 'refund']);
    }

    /**
     * 回退商品库存
     * @param $goodsList
     * @param $isPayOrder
     * @return array|false
     * @throws \Exception
     */
    public function backGoodsStock($goodsList, $isPayOrder = false)
    {
        $data = [];
        foreach ($goodsList as $goods) {
            $item = [
                'goods_sku_id' => $goods['goods_sku_id'],
                'stock_num' => ['inc', $goods['total_num']]
            ];
            if ($isPayOrder == true) {
                // 付款订单全部库存
                $data[] = $item;
            } else {
                // 未付款订单，判断必须为下单减库存时才回退
                $goods['deduct_stock_type'] == DeductStockTypeEnum::CREATE && $data[] = $item;
            }
        }
        if (empty($data)) return true;
        // 更新商品规格库存
        $model = new GoodsSkuModel;
        return $model->isUpdate()->saveAll($data);
    }

}
