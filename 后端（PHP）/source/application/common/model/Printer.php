<?php

namespace app\common\model;

use think\Request;
use app\common\enum\PrinterType as PrinterTypeEnum;

/**
 * 物流公司模型
 * Class Printer
 * @package app\common\model
 */
class Printer extends BaseModel
{
    protected $name = 'printer';

    /**
     * 获取打印机类型列表
     * @return array
     */
    public static function getPrinterTypeList()
    {
        static $printerTypeEnum = [];
        if (empty($printerTypeEnum)) {
            $printerTypeEnum = PrinterTypeEnum::getTypeName();
        }
        return $printerTypeEnum;
    }

    /**
     * 获取器：打印机类型名称
     * @param $value
     * @return array
     */
    public function getPrinterTypeAttr($value)
    {
        $printerType = self::getPrinterTypeList();
        return ['value' => $value, 'text' => $printerType[$value]];
    }

    /**
     * 自动转换printer_config为array格式
     * @param $value
     * @return string
     */
    public function getPrinterConfigAttr($value)
    {
        return json_decode($value, true);
    }

    /**
     * 自动转换printer_config为json格式
     * @param $value
     * @return string
     */
    public function setPrinterConfigAttr($value)
    {
        return json_encode($value);
    }

    /**
     * 获取全部
     * @return mixed
     */
    public static function getAll()
    {
        return (new static)->where('is_delete', '=', 0)
            ->order(['sort' => 'asc'])->select();
    }

    /**
     * 获取列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        return $this->where('is_delete', '=', 0)
            ->order(['sort' => 'asc'])
            ->paginate(15, false, [
                'query' => Request::instance()->request()
            ]);
    }

    /**
     * 物流公司详情
     * @param $printer_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($printer_id)
    {
        return self::get($printer_id);
    }

}
