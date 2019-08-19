<?php

namespace app\common\model\sharing;

use app\common\model\BaseModel;

/**
 * 拼团商品规格关系模型
 * Class GoodsSpecRel
 * @package app\common\model\sharing
 */
class GoodsSpecRel extends BaseModel
{
    protected $name = 'sharing_goods_spec_rel';
    protected $updateTime = false;

    /**
     * 关联规格组
     * @return \think\model\relation\BelongsTo
     */
    public function spec()
    {
        return $this->belongsTo('Spec');
    }

}
