<?php

namespace app\store\model;

use app\common\model\Order as OrderModel;

use app\store\model\UserCoupon as UserCouponModel;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\DeliveryType as DeliveryTypeEnum;
use app\common\service\Message as MessageService;
use app\common\service\order\Refund as RefundService;
use app\common\service\wechat\wow\Order as WowService;
use app\common\library\helper;

/**
 * 订单管理
 * Class Order
 * @package app\store\model
 */
class Order extends OrderModel
{
    /**
     * 订单列表
     * @param string $dataType
     * @param array $query
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($dataType, $query = [])
    {
        // 检索查询条件
        !empty($query) && $this->setWhere($query);
        // 获取数据列表
        return $this->with(['goods.image', 'address', 'user'])
            ->alias('order')
            ->field('order.*')
            ->join('user', 'user.user_id = order.user_id')
            ->where($this->transferDataType($dataType))
            ->where('order.is_delete', '=', 0)
            ->order(['order.create_time' => 'desc'])
            ->paginate(10, false, [
                'query' => \request()->request()
            ]);
    }

    /**
     * 订单列表(全部)
     * @param $dataType
     * @param array $query
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getListAll($dataType, $query = [])
    {
        // 检索查询条件
        !empty($query) && $this->setWhere($query);
        // 获取数据列表
        return $this->with(['goods.image', 'address', 'user', 'extract_shop'])
            ->alias('order')
            ->field('order.*')
            ->join('user', 'user.user_id = order.user_id')
            ->where($this->transferDataType($dataType))
            ->where('order.is_delete', '=', 0)
            ->order(['order.create_time' => 'desc'])
            ->select();
    }

    /**
     * 订单导出
     * @param $dataType
     * @param $query
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportList($dataType, $query)
    {
        // 获取订单列表
        $list = $this->getListAll($dataType, $query);
        // 表格标题
        $tileArray = ['订单号', '商品名称', '商品规格', '单价', '数量', '支付方式', '付款金额', '运费金额', '下单时间',
            '买家', '买家留言', '配送方式', '自提门店名称', '收货人姓名', '联系电话', '收货人地址', '物流公司', '物流单号',
            '付款状态', '付款时间', '发货状态', '发货时间', '收货状态', '收货时间', '订单状态',
            '微信支付交易号', '是否已评价'];
        // 表格内容
        $dataArray = [];
        foreach ($list as $order) {
            /* @var OrderAddress $address */
            $address = $order['address'];
            foreach ($order['goods'] as $goods) {
                $dataArray[] = [
                    '订单号' => $this->filterValue($order['order_no']),
                    '商品名称' => $goods['goods_name'],
                    '商品规格' => $goods['goods_attr'],
                    '单价' => $goods['goods_price'],
                    '数量' => $goods['total_num'],
                    '支付方式' => $this->filterValue($order['pay_type']['text']),
                    '付款金额' => $this->filterValue($order['pay_price']),
                    '运费金额' => $this->filterValue($order['express_price']),
                    '下单时间' => $this->filterValue($order['create_time']),
                    '买家' => $this->filterValue($order['user']['nickName']),
                    '买家留言' => $this->filterValue($order['buyer_remark']),
                    '配送方式' => $this->filterValue($order['delivery_type']['text']),
                    '自提门店名称' => $order['extract_shop_id'] > 0 ? $this->filterValue($order['extract_shop']['shop_name']) : '',
                    '收货人姓名' => $this->filterValue($order['address']['name']),
                    '联系电话' => $this->filterValue($order['address']['phone']),
                    '收货人地址' => $this->filterValue($address ? $address->getFullAddress() : ''),
                    '物流公司' => $this->filterValue($order['express']['express_name']),
                    '物流单号' => $this->filterValue($order['express_no']),
                    '付款状态' => $this->filterValue($order['pay_status']['text']),
                    '付款时间' => $this->filterTime($order['pay_time']),
                    '发货状态' => $this->filterValue($order['delivery_status']['text']),
                    '发货时间' => $this->filterTime($order['delivery_time']),
                    '收货状态' => $this->filterValue($order['receipt_status']['text']),
                    '收货时间' => $this->filterTime($order['receipt_time']),
                    '订单状态' => $this->filterValue($order['order_status']['text']),
                    '微信支付交易号' => $this->filterValue($order['transaction_id']),
                    '是否已评价' => $this->filterValue($order['is_comment'] ? '是' : '否'),
                ];
            }
        }
        // 导出csv文件
        $filename = 'order-' . date('YmdHis');
        return export_excel($filename . '.csv', $tileArray, $dataArray);
    }

    /**
     * 批量发货模板
     */
    public function deliveryTpl()
    {
        // 导出csv文件
        $filename = 'delivery-' . date('YmdHis');
        return export_excel($filename . '.csv', ['订单号', '物流单号']);
    }

    /**
     * 表格值过滤
     * @param $value
     * @return string
     */
    private function filterValue($value)
    {
        return "\t" . $value . "\t";
    }

    /**
     * 日期值过滤
     * @param $value
     * @return string
     */
    private function filterTime($value)
    {
        if (!$value) return '';
        return $this->filterValue(date('Y-m-d H:i:s', $value));
    }

    /**
     * 设置检索查询条件
     * @param $query
     */
    private function setWhere($query)
    {
        if (isset($query['search']) && !empty($query['search'])) {
            $this->where('order_no|user.nickName', 'like', '%' . trim($query['search']) . '%');
        }
        if (isset($query['start_time']) && !empty($query['start_time'])) {
            $this->where('order.create_time', '>=', strtotime($query['start_time']));
        }
        if (isset($query['end_time']) && !empty($query['end_time'])) {
            $this->where('order.create_time', '<', strtotime($query['end_time']) + 86400);
        }
        if (isset($query['delivery_type']) && !empty($query['delivery_type'])) {
            $query['delivery_type'] > -1 && $this->where('delivery_type', '=', $query['delivery_type']);
        }
        if (isset($query['extract_shop_id']) && !empty($query['extract_shop_id'])) {
            $query['extract_shop_id'] > -1 && $this->where('extract_shop_id', '=', $query['extract_shop_id']);
        }
        // 用户id
        if (isset($query['user_id']) && $query['user_id'] > 0) {
            $this->where('order.user_id', '=', (int)$query['user_id']);
        }
    }

    /**
     * 转义数据类型条件
     * @param $dataType
     * @return array
     */
    private function transferDataType($dataType)
    {
        // 数据类型
        $filter = [];
        switch ($dataType) {
            case 'delivery':
                $filter = [
                    'pay_status' => 20,
                    'delivery_status' => 10,
                    'order_status' => ['in', [10, 21]]
                ];
                break;
            case 'receipt':
                $filter = [
                    'pay_status' => 20,
                    'delivery_status' => 20,
                    'receipt_status' => 10
                ];
                break;
            case 'pay':
                $filter = ['pay_status' => 10, 'order_status' => 10];
                break;
            case 'complete':
                $filter = ['order_status' => 30];
                break;
            case 'cancel':
                $filter = ['order_status' => 20];
                break;
            case 'all':
                $filter = [];
                break;
        }
        return $filter;
    }

    /**
     * 确认发货(单独订单)
     * @param $data
     * @return array|bool|false
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function delivery($data)
    {
        // 转义为订单列表
        $orderList = [$this];
        // 验证订单是否满足发货条件
        if (!$this->verifyDelivery($orderList)) {
            return false;
        }
        // 整理更新的数据
        $updateList = [[
            'order_id' => $this['order_id'],
            'express_id' => $data['express_id'],
            'express_no' => $data['express_no']
        ]];
        // 更新订单发货状态
        if ($status = $this->updateToDelivery($updateList)) {
            // 获取已发货的订单
            $completed = self::detail($this['order_id'], ['user', 'address', 'goods', 'express']);
            // 发送消息通知
            $this->sendDeliveryMessage([$completed]);
            // 同步好物圈订单
            (new WowService($this['wxapp_id']))->update([$completed]);
        }
        return $status;
    }

    /**
     * 批量发货
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function batchDelivery($data)
    {
        // 获取csv文件中的数据
        if (!$csvData = $this->getCsvData()) {
            return false;
        }
        // 整理订单id集
        $orderNos = helper::getArrayColumn($csvData, 0);
        // 获取订单列表数据
        $orderList = helper::arrayColumn2Key($this->getListByOrderNos($orderNos), 'order_no');
        // 验证订单是否存在
        $tempArr = array_values(array_diff($orderNos, array_keys($orderList)));
        if (!empty($tempArr)) {
            $this->error = "订单号[{$tempArr[0]}] 不存在!";
            return false;
        }
        // 整理物流单号
        $updateList = [];
        foreach ($csvData as $item) {
            $updateList[] = [
                'order_id' => $orderList[$item[0]]['order_id'],
                'express_id' => $data['express_id'],
                'express_no' => $item[1],
            ];
        }
        // 验证订单是否满足发货条件
        if (!$this->verifyDelivery($orderList)) {
            return false;
        }
        // 更新订单发货状态(批量)
        if ($status = $this->updateToDelivery($updateList)) {
            // 获取已发货的订单
            $completed = $this->getListByOrderNos($orderNos, ['user', 'address', 'goods', 'express']);
            // 发送消息通知
            $this->sendDeliveryMessage($completed);
            //  同步好物圈订单
            (new WowService(self::$wxapp_id))->update($completed);
        }
        return $status;
    }

    /**
     * 确认发货后发送消息通知
     * @param array|\think\Collection $orderList
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    private function sendDeliveryMessage($orderList)
    {
        // 实例化消息通知服务类
        $Service = new MessageService;
        foreach ($orderList as $item) {
            // 发送消息通知
            $Service->delivery($item, OrderTypeEnum::MASTER);
        }
        return true;
    }

    /**
     * 更新订单发货状态(批量)
     * @param $orderList
     * @return array|false
     * @throws \Exception
     */
    private function updateToDelivery($orderList)
    {
        $data = [];
        foreach ($orderList as $item) {
            $data[] = [
                'order_id' => $item['order_id'],
                'express_no' => $item['express_no'],
                'express_id' => $item['express_id'],
                'delivery_status' => 20,
                'delivery_time' => time(),
            ];
        }
        return $this->isUpdate()->saveAll($data);
    }

    /**
     * 验证订单是否满足发货条件
     * @param $orderList
     * @return bool
     */
    private function verifyDelivery($orderList)
    {
        foreach ($orderList as $order) {
            if (
                $order['pay_status']['value'] != 20
                || $order['delivery_type']['value'] != DeliveryTypeEnum::EXPRESS
                || $order['delivery_status']['value'] != 10
            ) {
                $this->error = "订单号[{$order['order_no']}] 不满足发货条件!";
                return false;
            }
        }
        return true;
    }

    /**
     * 获取csv文件中的数据
     * @return array|bool
     */
    private function getCsvData()
    {
        // 获取表单上传文件 例如上传了001.jpg
        if (!$file = \request()->file('iFile')) {
            $this->error = '请上传发货模板';
            return false;
        }
        // 设置区域信息
        setlocale(LC_ALL, 'zh_CN');
        // 打开上传的文件
        $csvFile = fopen($file->getInfo()['tmp_name'], 'r');
        // 忽略第一行(csv标题)
        fgetcsv($csvFile);
        // 遍历并记录订单信息
        $orderList = [];
        while ($item = fgetcsv($csvFile)) {
            if (!isset($item[0]) || empty($item[0]) || !isset($item[1]) || empty($item[1])) {
                $this->error = '模板文件数据不合法';
                return false;
            }
            $orderList[] = $item;
        }
        if (empty($orderList)) {
            $this->error = '模板文件中没有订单数据';
            return false;
        }
        return $orderList;
    }

    /**
     * 修改订单价格
     * @param $data
     * @return bool
     */
    public function updatePrice($data)
    {
        if ($this['pay_status']['value'] != 10) {
            $this->error = '该订单不合法';
            return false;
        }
        // 实际付款金额
        $payPrice = bcadd($data['update_price'], $data['update_express_price'], 2);
        if ($payPrice <= 0) {
            $this->error = '订单实付款价格不能为0.00元';
            return false;
        }
        return $this->save([
                'order_no' => $this->orderNo(), // 修改订单号, 否则微信支付提示重复
                'order_price' => $data['update_price'],
                'pay_price' => $payPrice,
                'update_price' => helper::bcsub($data['update_price'], helper::bcsub($this['total_price'], $this['coupon_money'])),
                'express_price' => $data['update_express_price']
            ]) !== false;
    }

    /**
     * 审核：用户取消订单
     * @param $data
     * @return bool|mixed
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function confirmCancel($data)
    {
        // 判断订单是否有效
        if ($this['pay_status']['value'] != 20) {
            $this->error = '该订单不合法';
            return false;
        }
        // 订单取消事件
        $status = $this->transaction(function () use ($data) {
            if ($data['is_cancel'] == true) {
                // 执行退款操作
                (new RefundService)->execute($this);
                // 回退商品库存
                (new OrderGoods)->backGoodsStock($this['goods']);
                // 回退用户优惠券
                $this['coupon_id'] > 0 && UserCouponModel::setIsUse($this['coupon_id'], false);
            }
            // 更新订单状态
            return $this->save(['order_status' => $data['is_cancel'] ? 20 : 10]);
        });
        if ($status == true) {
            // 同步好物圈订单
            (new WowService(self::$wxapp_id))->update([$this]);
        }
        return $status;
    }

    /**
     * 获取已付款订单总数 (可指定某天)
     * @param null $day
     * @return int|string
     * @throws \think\Exception
     */
    public function getPayOrderTotal($day = null)
    {
        $filter = [
            'pay_status' => 20,
            'order_status' => ['<>', 20],
        ];
        if (!is_null($day)) {
            $startTime = strtotime($day);
            $filter['pay_time'] = [
                ['>=', $startTime],
                ['<', $startTime + 86400],
            ];
        }
        return $this->getOrderTotal($filter);
    }

    /**
     * 获取订单总数量
     * @param array $filter
     * @return int|string
     * @throws \think\Exception
     */
    public function getOrderTotal($filter = [])
    {
        return $this->where($filter)
            ->where('is_delete', '=', 0)
            ->count();
    }

    /**
     * 获取某天的总销售额
     * @param $day
     * @return float|int
     */
    public function getOrderTotalPrice($day)
    {
        $startTime = strtotime($day);
        return $this->where('pay_time', '>=', $startTime)
            ->where('pay_time', '<', $startTime + 86400)
            ->where('pay_status', '=', 20)
            ->where('order_status', '<>', 20)
            ->where('is_delete', '=', 0)
            ->sum('pay_price');
    }

    /**
     * 获取某天的下单用户数
     * @param $day
     * @return float|int
     */
    public function getPayOrderUserTotal($day)
    {
        $startTime = strtotime($day);
        $userIds = $this->distinct(true)
            ->where('pay_time', '>=', $startTime)
            ->where('pay_time', '<', $startTime + 86400)
            ->where('pay_status', '=', 20)
            ->where('is_delete', '=', 0)
            ->column('user_id');
        return count($userIds);
    }

}
