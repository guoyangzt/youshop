<?php

namespace app\store\model;

use app\common\model\Printer as PrinterModel;

class Printer extends PrinterModel
{
    /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        $data['printer_config'] = $data[$data['printer_type']];
        $data['wxapp_id'] = self::$wxapp_id;
        return $this->allowField(true)->save($data);
    }

    /**
     * 编辑记录
     * @param $data
     * @return bool|int
     */
    public function edit($data)
    {
        $data['printer_config'] = $data[$data['printer_type']];
        return $this->allowField(true)->save($data);
    }

    /**
     * 删除记录
     * @return bool|int
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }

}