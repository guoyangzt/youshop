<?php

namespace app\common\model;

use think\Request;
use app\common\library\express\Kuaidi100;

/**
 * 物流公司模型
 * Class Express
 * @package app\common\model
 */
class Express extends BaseModel
{
    protected $name = 'express';

    /**
     * 获取全部
     * @return mixed
     */
    public static function getAll()
    {
        $model = new static;
        return $model->order(['sort' => 'asc'])->select();
    }

    /**
     * 获取列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        return $this->order(['sort' => 'asc'])
            ->paginate(15, false, [
                'query' => Request::instance()->request()
            ]);
    }

    /**
     * 物流公司详情
     * @param $express_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($express_id)
    {
        return self::get($express_id);
    }

    /**
     * 获取物流动态信息
     * @param $express_name
     * @param $express_code
     * @param $express_no
     * @return array|bool
     */
    public function dynamic($express_name, $express_code, $express_no)
    {
        $data = [
            'express_name' => $express_name,
            'express_no' => $express_no
        ];
        // 实例化快递100类
        $config = Setting::getItem('store');
        $Kuaidi100 = new Kuaidi100($config['kuaidi100']);
        // 请求查询接口
        $data['list'] = $Kuaidi100->query($express_code, $express_no);
        if ($data['list'] === false) {
            $this->error = $Kuaidi100->getError();
            return false;
        }
        return $data;
    }

}
