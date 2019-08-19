<?php

namespace app\store\model\dealer;

use think\Cache;
use app\common\exception\BaseException;
use app\common\model\dealer\Setting as SettingModel;

/**
 * 分销商设置模型
 * Class Setting
 * @package app\store\model\dealer
 */
class Setting extends SettingModel
{
    /**
     * 设置项描述
     * @var array
     */
    private $describe = [
        'basic' => '基础设置',
        'condition' => '分销商条件',
        'commission' => '佣金设置',
        'settlement' => '结算',
        'words' => '自定义文字',
        'license' => '申请协议',
        'background' => '页面背景图',
        'template_msg' => '模板消息',
        'qrcode' => '分销海报',
    ];

    /**
     * 更新系统设置
     * @param $data
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function edit($data)
    {
        $this->startTrans();
        try {
            foreach ($data as $key => $values)
                $this->saveValues($key, $values);
            $this->commit();
            // 删除系统设置缓存
            Cache::rm('dealer_setting_' . self::$wxapp_id);
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 保存设置项
     * @param $key
     * @param $values
     * @return false|int
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    private function saveValues($key, $values)
    {
        $model = self::detail($key) ?: new self;
        // 数据验证
        if (!$this->validValues($key, $values)) {
            throw new BaseException(['msg' => $this->error]);
        }
        return $model->save([
            'key' => $key,
            'describe' => $this->describe[$key],
            'values' => $values,
            'wxapp_id' => self::$wxapp_id,
        ]);
    }

    /**
     * 数据验证
     * @param $key
     * @param $values
     * @return bool
     */
    private function validValues($key, $values)
    {
        if ($key === 'settlement') {
            // 验证结算方式
            return $this->validSettlement($values);
        }
//        if ($key === 'condition') {
//            // 验证分销商条件
//            return $this->validCondition($values);
//        }
        return true;
    }

    /**
     * 验证结算方式
     * @param $values
     * @return bool
     */
    private function validSettlement($values)
    {
        if (!isset($values['pay_type']) || empty($values['pay_type'])) {
            $this->error = '请设置 结算-提现方式';
            return false;
        }
        return true;
    }

}