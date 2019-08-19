<?php

namespace app\admin\model\admin;

use think\Session;
use app\common\model\admin\User as UserModel;

/**
 * 超管后台用户模型
 * Class User
 * @package app\admin\model\admin
 */
class User extends UserModel
{
    /**
     * 超管后台用户登录
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login($data)
    {
        // 验证用户名密码是否正确
        if (!$user = self::useGlobalScope(false)->where([
            'user_name' => $data['user_name'],
            'password' => yoshop_hash($data['password'])
        ])->find()) {
            $this->error = '登录失败, 用户名或密码错误';
            return false;
        }
        // 保存登录状态
        Session::set('yoshop_admin', [
            'user' => [
                'admin_user_id' => $user['admin_user_id'],
                'user_name' => $user['user_name'],
            ],
            'is_login' => true,
        ]);
        return true;
    }

    /**
     * 超管用户信息
     * @param $admin_user_id
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($admin_user_id)
    {
        return self::get($admin_user_id);
    }

    /**
     * 更新当前管理员信息
     * @param $data
     * @return bool
     */
    public function renew($data)
    {
        if ($data['password'] !== $data['password_confirm']) {
            $this->error = '确认密码不正确';
            return false;
        }
        // 更新管理员信息
        if ($this->save([
                'user_name' => $data['user_name'],
                'password' => yoshop_hash($data['password']),
            ]) === false) {
            return false;
        }
        // 更新session
        Session::set('yoshop_admin.user', [
            'admin_user_id' => $this['admin_user_id'],
            'user_name' => $data['user_name'],
        ]);
        return true;
    }

}