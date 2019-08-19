<?php

namespace app\api\model\dealer;

use app\common\model\dealer\Apply as ApplyModel;

/**
 * 分销商申请模型
 * Class Apply
 * @package app\api\model\dealer
 */
class Apply extends ApplyModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'create_time',
        'update_time',
    ];

    /**
     * 是否为分销商申请中
     * @param $user_id
     * @return bool
     * @throws \think\exception\DbException
     */
    public static function isApplying($user_id)
    {
        $detail = self::detail(['user_id' => $user_id]);
        return $detail ? ((int)$detail['apply_status'] === 10) : false;
    }

    /**
     * 提交申请
     * @param $user
     * @param $name
     * @param $mobile
     * @return bool
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function submit($user, $name, $mobile)
    {
        // 成为分销商条件
        $config = Setting::getItem('condition');
        // 数据整理
        $data = [
            'user_id' => $user['user_id'],
            'real_name' => trim($name),
            'mobile' => trim($mobile),
            'referee_id' => Referee::getRefereeUserId($user['user_id'], 1),
            'apply_type' => $config['become'],
            'apply_time' => time(),
            'wxapp_id' => self::$wxapp_id,
        ];
        if ($config['become'] == 10) {
            $data['apply_status'] = 10;
        } elseif ($config['become'] == 20) {
            $data['apply_status'] = 20;
        }
        return $this->add($user, $data);
    }

    /**
     * 更新分销商申请信息
     * @param $user
     * @param $data
     * @return bool
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    private function add($user, $data)
    {
        // 实例化模型
        $model = self::detail(['user_id' => $user['user_id']]) ?: $this;
        // 更新记录
        $this->startTrans();
        try {
            // $data['create_time'] = time();
            // 保存申请信息
            $model->save($data);
            // 无需审核，自动通过
            if ($data['apply_type'] == 20) {
                // 新增分销商用户记录
                User::add($user['user_id'], [
                    'real_name' => $data['real_name'],
                    'mobile' => $data['mobile'],
                    'referee_id' => $data['referee_id']
                ]);
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

}
