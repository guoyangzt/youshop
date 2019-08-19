<?php

namespace app\common\behavior;

use think\Log;
use think\Request;

/**
 * 应用行为管理
 * Class App
 * @package app\common\behavior
 */
class App
{
    /**
     * 应用开始
     * @param $dispatch
     */
    public function appBegin($dispatch)
    {
        // 记录访问日志
        if (!config('app_debug')) {
            $request = Request::instance();
            Log::record('[ ROUTE ] ' . var_export($dispatch, true), 'begin');
            Log::record('[ HEADER ] ' . var_export($request->header(), true), 'begin');
            Log::record('[ PARAM ] ' . var_export($request->param(), true), 'begin');
        }
    }
}