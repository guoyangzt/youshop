<?php

namespace app\common\model\sharing;

use app\common\model\BaseModel;

/**
 * 拼团商品图片模型
 * Class GoodsImage
 * @package app\common\model\sharing
 */
class GoodsImage extends BaseModel
{
    protected $name = 'sharing_goods_image';
    protected $updateTime = false;

    /**
     * 关联文件库
     * @return \think\model\relation\BelongsTo
     */
    public function file()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\UploadFile", 'image_id', 'file_id')
            ->bind(['file_path', 'file_name', 'file_url']);
    }

}
