<?php

namespace app\common\model;

/**
 * 售后单图片模型
 * Class OrderRefundImage
 * @package app\common\model
 */
class OrderRefundImage extends BaseModel
{
    protected $name = 'order_refund_image';
    protected $updateTime = false;

    /**
     * 关联文件库
     * @return \think\model\relation\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo('UploadFile', 'image_id', 'file_id')
            ->bind(['file_path', 'file_name', 'file_url']);
    }

}
