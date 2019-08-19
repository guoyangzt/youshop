<?php

namespace app\api\model;

use app\common\model\Coupon as CouponModel;

/**
 * 优惠券模型
 * Class Coupon
 * @package app\api\model
 */
class Coupon extends CouponModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'receive_num',
        'is_delete',
        'create_time',
        'update_time',
    ];

    /**
     * 获取优惠券列表
     * @param bool $user
     * @param null $limit
     * @param bool $only_receive
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($user = false, $limit = null, $only_receive = false)
    {
        // 构造查询条件
        $this->where('is_delete', '=', 0);
        // 只显示可领取(未过期,未发完)的优惠券
        if ($only_receive) {
            $this->where('	IF ( `total_num` > - 1, `receive_num` < `total_num`, 1 = 1 )')
                ->where('IF ( `expire_type` = 20, (`end_time` + 86400) >= ' . time() . ', 1 = 1 )');
        }

        // 优惠券列表
        $couponList = $this->order(['sort' => 'asc', 'create_time' => 'desc'])->limit($limit)->select();

        // 获取用户已领取的优惠券
        if ($user !== false) {
            $UserCouponModel = new UserCoupon;
            $userCouponIds = $UserCouponModel->getUserCouponIds($user['user_id']);
            foreach ($couponList as $key => $item) {
                $couponList[$key]['is_receive'] = in_array($item['coupon_id'], $userCouponIds);
            }
        }
        return $couponList;
    }

    /**
     * 验证优惠券是否可领取
     * @return bool
     */
    public function checkReceive()
    {
        if ($this['total_num'] > -1 && $this['receive_num'] >= $this['total_num']) {
            $this->error = '优惠券已发完';
            return false;
        }
        if ($this['expire_type'] == 20 && ($this->getData('end_time') + 86400) < time()) {
            $this->error = '优惠券已过期';
            return false;
        }
        return true;
    }

    /**
     * 累计已领取数量
     * @return int|true
     * @throws \think\Exception
     */
    public function setIncReceiveNum()
    {
        return $this->setInc('receive_num');
    }

}
