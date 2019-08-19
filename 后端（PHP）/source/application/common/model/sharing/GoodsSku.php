<?php

namespace app\common\model\sharing;

use app\common\library\helper;
use app\common\model\BaseModel;

/**
 * 拼团商品SKU模型
 * Class GoodsSku
 * @package app\common\model\sharing
 */
class GoodsSku extends BaseModel
{
    protected $name = 'sharing_goods_sku';
    protected $append = ['diff_price'];

    /**
     * 规格图片
     * @return \think\model\relation\HasOne
     */
    public function image()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->hasOne("app\\{$module}\\model\\UploadFile", 'file_id', 'image_id');
    }

    /**
     * 获取器：拼团价与划线价差额
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getDiffPriceAttr($value, $data)
    {
        return max(0, helper::bcsub($data['line_price'], $data['sharing_price']));
    }

}
