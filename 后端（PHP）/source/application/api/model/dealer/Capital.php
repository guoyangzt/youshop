<?php

namespace app\api\model\dealer;

use app\common\model\dealer\Capital as CapitalModel;

/**
 * 分销商资金明细模型
 * Class Apply
 * @package app\api\model\dealer
 */
class Capital extends CapitalModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'create_time',
        'update_time',
    ];

}