<?php

namespace app\common\library\printer;

use app\common\exception\BaseException;
use app\common\enum\PrinterType as PrinterTypeEnum;

/**
 * 小票打印机驱动
 * Class driver
 * @package app\common\library\printer
 */
class Driver
{
    private $printer;    // 当前打印机
    private $engine;     // 当前打印机引擎类

    /** @var array $engineList 打印机引擎列表 */
    private static $engineList = [
        PrinterTypeEnum::FEI_E_YUN => 'Feie',
        PrinterTypeEnum::PRINT_CENTER => 'PrintCenter',
    ];

    /**
     * 构造方法
     * Driver constructor.
     * @param $printer
     * @throws BaseException
     */
    public function __construct($printer)
    {
        // 当前打印机
        $this->printer = $printer;
        // 实例化当前打印机引擎
        $this->engine = $this->getEngineClass();
    }

    /**
     * 执行打印请求
     * @param $content
     * @return bool
     */
    public function printTicket($content)
    {
        return $this->engine->printTicket($content);
    }

    /**
     * 获取错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->engine->getError();
    }

    /**
     * 获取当前的打印机引擎类
     * @return mixed
     * @throws BaseException
     */
    private function getEngineClass()
    {
        $engineName = self::$engineList[$this->printer['printer_type']['value']];
        $classSpace = __NAMESPACE__ . "\\engine\\{$engineName}";
        if (!class_exists($classSpace)) {
            throw new BaseException("未找到打印机引擎类: {$engineName}");
        }
        return new $classSpace($this->printer['printer_config'], $this->printer['print_times']);
    }

}
