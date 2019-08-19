<?php

namespace app\api\model\sharing;

use app\common\exception\BaseException;
use app\common\model\sharing\Comment as CommentModel;

/**
 * 拼团商品评价模型
 * Class Comment
 * @package app\api\model\sharing
 */
class Comment extends CommentModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'status',
        'sort',
        'order_id',
        'goods_id',
        'order_goods_id',
        'is_delete',
        'update_time'
    ];

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\User")
            ->field(['user_id', 'nickName', 'avatarUrl']);
    }

    /**
     * 获取指定商品评价列表
     * @param $goods_id
     * @param int $scoreType
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getGoodsCommentList($goods_id, $scoreType = -1)
    {
        // 筛选条件
        $filter = [
            'goods_id' => $goods_id,
            'is_delete' => 0,
            'status' => 1,
        ];
        // 评分
        $scoreType > 0 && $filter['score'] = $scoreType;
        return $this->with(['user', 'OrderGoods', 'image.file'])
            ->where($filter)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->paginate(15, false, [
                'query' => request()->request()
            ]);
    }

    /**
     * 获取指定评分总数
     * @param $goods_id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getTotal($goods_id)
    {
        return $this->field([
            'count(comment_id) AS `all`',
            'count(score = 10 OR NULL) AS `praise`',
            'count(score = 20 OR NULL) AS `review`',
            'count(score = 30 OR NULL) AS `negative`',
        ])->where([
            'goods_id' => $goods_id,
            'is_delete' => 0,
            'status' => 1
        ])->find();
    }

    /**
     * 验证订单是否允许评价
     * @param Order $order
     * @return boolean
     */
    public function checkOrderAllowComment($order)
    {
        // 验证订单是否已完成
        if ($order['order_status']['value'] != 30) {
            $this->error = '该订单未完成，无法评价';
            return false;
        }
        // 验证订单是否已评价
        if ($order['is_comment'] == 1) {
            $this->error = '该订单已完成评价';
            return false;
        }
        return true;
    }

    /**
     * 根据已完成订单商品 添加评价
     * @param Order $order
     * @param \think\Collection|OrderGoods $goodsList
     * @param $formJsonData
     * @return boolean
     * @throws \Exception
     */
    public function addForOrder($order, $goodsList, $formJsonData)
    {
        // 生成 formData
        $formData = $this->formatFormData($formJsonData);
        // 生成评价数据
        $data = $this->createCommentData($order['user_id'], $order['order_id'], $goodsList, $formData);
        if (empty($data)) {
            $this->error = '没有输入评价内容';
            return false;
        }
        return $this->transaction(function () use ($order, $goodsList, $formData, $data) {
            // 记录评价内容
            $result = $this->isUpdate(false)->saveAll($data);
            // 记录评价图片
            $this->saveAllImages($result, $formData);
            // 更新订单评价状态
            $isComment = count($goodsList) === count($data);
            $this->updateOrderIsComment($order, $isComment, $result);
            return true;
        });
    }

    /**
     * 更新订单评价状态
     * @param Order $order
     * @param $isComment
     * @param $commentList
     * @return array|false
     * @throws \Exception
     */
    private function updateOrderIsComment($order, $isComment, &$commentList)
    {
        // 更新订单商品
        $orderGoodsData = [];
        foreach ($commentList as $comment) {
            $orderGoodsData[] = [
                'order_goods_id' => $comment['order_goods_id'],
                'is_comment' => 1
            ];
        }
        // 更新订单
        $isComment && $order->save(['is_comment' => 1]);
        return (new OrderGoods)->saveAll($orderGoodsData);
    }

    /**
     * 生成评价数据
     * @param $user_id
     * @param $order_id
     * @param $goodsList
     * @param $formData
     * @return array
     * @throws BaseException
     */
    private function createCommentData($user_id, $order_id, &$goodsList, &$formData)
    {
        $data = [];
        foreach ($goodsList as $goods) {
            if (!isset($formData[$goods['order_goods_id']])) {
                throw new BaseException(['msg' => '提交的数据不合法']);
            }
            $item = $formData[$goods['order_goods_id']];
            !empty($item['content']) && $data[$goods['order_goods_id']] = [
                'score' => $item['score'],
                'content' => $item['content'],
                'is_picture' => !empty($item['uploaded']),
                'sort' => 100,
                'status' => 1,
                'user_id' => $user_id,
                'order_id' => $order_id,
                'goods_id' => $item['goods_id'],
                'order_goods_id' => $item['order_goods_id'],
                'wxapp_id' => self::$wxapp_id
            ];
        }
        return $data;
    }

    /**
     * 格式化 formData
     * @param string $formJsonData
     * @return array
     */
    private function formatFormData($formJsonData)
    {
        return array_column(json_decode($formJsonData, true), null, 'order_goods_id');
    }

    /**
     * 记录评价图片
     * @param $commentList
     * @param $formData
     * @return bool
     * @throws \Exception
     */
    private function saveAllImages(&$commentList, &$formData)
    {
        // 生成评价图片数据
        $imageData = [];
        foreach ($commentList as $comment) {
            $item = $formData[$comment['order_goods_id']];
            foreach ($item['uploaded'] as $imageId) {
                $imageData[] = [
                    'comment_id' => $comment['comment_id'],
                    'image_id' => $imageId,
                    'wxapp_id' => self::$wxapp_id
                ];
            }
        }
        $model = new CommentImage;
        return !empty($imageData) && $model->saveAll($imageData);
    }

}
