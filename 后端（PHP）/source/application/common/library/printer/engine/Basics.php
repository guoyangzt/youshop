<?php

namespace app\common\library\printer\engine;

/**
 * 小票打印机驱动基类
 * Class Basics
 * @package app\common\library\printer\engine
 */
abstract class Basics
{
    protected $config;  // 打印机配置
    protected $times;   // 打印联数(次数)

    protected $error;   // 错误信息

    /**
     * 构造函数
     * Basics constructor.
     * @param array $config 打印机配置
     * @param int $times 打印联数(次数)
     */
    public function __construct($config, $times)
    {
        $this->config = $config;
        $this->times = $times;
    }

    /**
     * 执行打印请求
     * @param $content
     * @return mixed
     */
    abstract protected function printTicket($content);

    /**
     * 返回错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 创建打印的内容
     * @return string
     */
    private function setContentText()
    {
        return '';
    }


}