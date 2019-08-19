<?php

namespace app\common\model\sharing;

use think\Hook;
use app\common\model\BaseModel;

/**
 * 拼团拼单模型
 * Class Active
 * @package app\common\model\sharing
 */
class Active extends BaseModel
{
    protected $name = 'sharing_active';
    protected $append = ['surplus_people'];

    /**
     * 拼团拼单模型初始化
     */
    public static function init()
    {
        parent::init();
        // 监听订单处理事件
        $static = new static;
        Hook::listen('sharing_active', $static);
    }

    /**
     * 获取器：拼单状态
     * @param $value
     * @return array
     */
    public function getStatusAttr($value)
    {
        $state = [
            0 => '未拼单',
            10 => '拼单中',
            20 => '拼单成功',
            30 => '拼单失败',
        ];
        return ['text' => $state[$value], 'value' => $value];
    }

    /**
     * 获取器：结束时间
     * @param $value
     * @return array
     */
    public function getEndTimeAttr($value)
    {
        return ['text' => date('Y-m-d H:i:s', $value), 'value' => $value];
    }

    /**
     * 获取器：剩余拼团人数
     * @param $value
     * @return array
     */
    public function getSurplusPeopleAttr($value, $data)
    {
        return $data['people'] - $data['actual_people'];
    }

    /**
     * 关联拼团商品表
     * @return \think\model\relation\BelongsTo
     */
    public function goods()
    {
        return $this->belongsTo('Goods');
    }

    /**
     * 关联用户表（团长）
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\User", 'creator_id');
    }

    /**
     * 关联拼单成员表
     * @return \think\model\relation\HasMany
     */
    public function users()
    {
        return $this->hasMany('ActiveUsers', 'active_id')
            ->order(['is_creator' => 'desc', 'create_time' => 'asc']);
    }

    /**
     * 拼单详情
     * @param $active_id
     * @param array $with
     * @return static|null
     * @throws \think\exception\DbException
     */
    public static function detail($active_id, $with = [])
    {
        return static::get($active_id, array_merge(['goods', 'users' => ['user', 'sharingOrder']], $with));
    }

    /**
     * 验证当前拼单是否允许加入新成员
     * @return bool
     */
    public function checkAllowJoin()
    {
        if (!in_array($this['status']['value'], [0, 10])) {
            $this->error = '当前拼单已结束';
            return false;
        }
        if (time() > $this['end_time']) {
            $this->error = '当前拼单已结束';
            return false;
        }
        if ($this['actual_people'] >= $this['people']) {
            $this->error = '当前拼单人数已满';
            return false;
        }
        return true;
    }

}
