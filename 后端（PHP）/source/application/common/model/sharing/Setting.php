<?php

namespace app\common\model\sharing;

use think\Cache;
use app\common\model\BaseModel;

/**
 * 拼团设置模型
 * Class Setting
 * @package app\common\model\sharing
 */
class Setting extends BaseModel
{
    protected $name = 'sharing_setting';
    protected $createTime = false;

    /**
     * 获取器: 转义数组格式
     * @param $value
     * @return mixed
     */
    public function getValuesAttr($value)
    {
        return json_decode($value, true);
    }

    /**
     * 修改器: 转义成json格式
     * @param $value
     * @return string
     */
    public function setValuesAttr($value)
    {
        return json_encode($value);
    }

    /**
     * 获取指定项设置
     * @param $key
     * @param $wxapp_id
     * @return array
     */
    public static function getItem($key, $wxapp_id = null)
    {
        $data = static::getAll($wxapp_id);
        return isset($data[$key]) ? $data[$key]['values'] : [];
    }

    /**
     * 获取全部设置
     * @param null $wxapp_id
     * @return array|mixed
     */
    public static function getAll($wxapp_id = null)
    {
        $self = new static;
        is_null($wxapp_id) && $wxapp_id = $self::$wxapp_id;
        if (!$data = Cache::get('sharing_setting_' . $wxapp_id)) {
            $data = array_column(collection($self::all())->toArray(), null, 'key');
            Cache::tag('cache')->set('sharing_setting_' . $wxapp_id, $data);
        }
        return array_merge_multiple($self->defaultData(), $data);
    }

    /**
     * 获取设置项信息
     * @param $key
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($key)
    {
        return static::get(compact('key'));
    }

    /**
     * 默认配置
     * @return array
     */
    public function defaultData()
    {
        return [
            'basic' => [
                'key' => 'basic',
                'describe' => '基础设置',
                'values' => [
                    // 拼团失败自动退款
                    'auto_refund' => '1',
                    // 是否允许使用优惠券
                    'is_coupon' => '1',
                    // 是否开启分销
                    'is_dealer' => '0',
                    // 拼团规则 简述
                    'rule_brief' => '好友拼单 · 人满发货 · 人不满退款',
                    // 拼团规则 详述
                    'rule_detail' => "开团：选择商品，点击“发起拼单”按钮，付款完成后即开团成功，就可以邀请小伙伴一起拼团啦;\n\n参团：进入朋友分享的页面，点击“立即参团”按钮，付款完成后参团成功，在有效时间内凑齐人数即成团，就可以等待收货喽;\n\n成团：在开团或参团成功后，点击“立即分享”将页面分享给好友，在有效时间内凑齐人数即成团，成团后商家开始发货;\n\n组团失败：在有效时间内未凑齐人数，即组团失败，组团失败后订单所付款将原路退回到支付账户。",
                    // 拼单状态模板消息id
                    'tpl_msg_id' => '',
                ]
            ]
        ];
    }

}