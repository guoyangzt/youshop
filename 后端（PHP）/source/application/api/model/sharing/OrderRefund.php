<?php

namespace app\api\model\sharing;

use app\common\model\sharing\OrderRefund as OrderRefundModel;

/**
 * 售后单模型
 * Class OrderRefund
 * @package app\api\model\sharing
 */
class OrderRefund extends OrderRefundModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
        'update_time'
    ];

    /**
     * 追加字段
     * @var array
     */
    protected $append = [
        'state_text',   // 售后单状态文字描述
    ];

    /**
     * 售后单状态文字描述
     * @param $value
     * @param $data
     * @return string
     */
    public function getStateTextAttr($value, $data)
    {
        // 已完成
        if ($data['status'] == 20) {
            $text = [10 => '已同意退货并已退款', 20 => '已同意换货'];
            return $text[$data['type']];
        }
        // 已取消
        if ($data['status'] == 30) {
            return '已取消';
        }
        // 已拒绝
        if ($data['status'] == 10) {
//            return '已拒绝';
            return $data['type'] == 10 ? '已拒绝退货退款' : '已拒绝换货';
        }
        // 进行中
        if ($data['status'] == 0) {
            if ($data['is_agree'] == 0) {
                return '等待审核中';
            }
            if ($data['type'] == 10) {
                return $data['is_user_send'] ? '已发货，待平台确认' : '已同意退货，请及时发货';
            }
        }
        return $value;
    }

    /**
     * 获取用户售后单列表
     * @param $user_id
     * @param int $state
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($user_id, $state = -1)
    {
        $state > -1 && $this->where('status', '=', $state);
        return $this->with(['order_goods.image'])
            ->where('user_id', '=', $user_id)
            ->order(['create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => \request()->request()
            ]);
    }

    /**
     * 用户发货
     * @param $data
     * @return false|int
     */
    public function delivery($data)
    {
        if (
            $this['type']['value'] != 10
            || $this['is_agree']['value'] != 10
            || $this['is_user_send'] != 0
        ) {
            $this->error = '当前售后单不合法，不允许该操作';
            return false;
        }
        if ($data['express_id'] <= 0) {
            $this->error = '请选择物流公司';
            return false;
        }
        if (empty($data['express_no'])) {
            $this->error = '请填写物流单号';
            return false;
        }
        return $this->save([
            'is_user_send' => 1,
            'send_time' => time(),
            'express_id' => (int)$data['express_id'],
            'express_no' => $data['express_no'],
        ]);
    }

    /**
     * 新增售后单记录
     * @param $user
     * @param $goods
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function apply($user, $goods, $data)
    {
        $this->startTrans();
        try {
            // 新增售后单记录
            $this->save([
                'order_goods_id' => $data['order_goods_id'],
                'order_id' => $goods['order_id'],
                'user_id' => $user['user_id'],
                'type' => $data['type'],
                'apply_desc' => $data['content'],
                'is_agree' => 0,
                'status' => 0,
                'wxapp_id' => self::$wxapp_id,
            ]);
            // 记录凭证图片关系
            if (isset($data['images']) && !empty($data['images'])) {
                $this->saveImages($this['order_refund_id'], $data['images']);
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->rollback();
            return false;
        }
    }

    /**
     * 记录售后单图片
     * @param $order_refund_id
     * @param $images
     * @return bool
     * @throws \Exception
     */
    private function saveImages($order_refund_id, $images)
    {
        // 生成评价图片数据
        $data = [];
        foreach (explode(',', $images) as $image_id) {
            $data[] = [
                'order_refund_id' => $order_refund_id,
                'image_id' => $image_id,
                'wxapp_id' => self::$wxapp_id
            ];
        }
        return !empty($data) && (new OrderRefundImage)->saveAll($data);
    }

}