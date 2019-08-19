<?php

namespace app\common\service\wechat\wow;

use app\common\model\Wxapp as WxappModel;
use app\common\model\wow\Order as OrderModel;
use app\common\model\wow\Setting as SettingModel;

use app\common\library\helper;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\order\PayType as PayTypeEnum;
use app\common\enum\DeliveryType as DeliveryTypeEnum;
use app\common\library\wechat\wow\Order as WowOrder;

/**
 * 好物圈-订单同步 服务类
 * Class Shoping
 * @package app\common\service\wechat\wow
 */
class Order
{
    /* @var int $wxapp_id 小程序商城id */
    private $wxappId;

    /* @var WowOrder $ApiDriver 微信api驱动 */
    private $ApiDriver;

    protected $error;

    /**
     * 构造方法
     * Shoping constructor.
     * @param $wxappId
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function __construct($wxappId)
    {
        $this->wxappId = $wxappId;
        $this->initApiDriver();
    }

    /**
     * 导入好物圈订单信息
     * @param array|\think\Collection $orderList 订单列表
     * @param bool $isCheck 验证后台是否开启同步设置
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \Exception
     */
    public function import($orderList, $isCheck = true)
    {
        // 判断是否开启同步设置
        $setting = SettingModel::getItem('basic', $this->wxappId);
        if ($isCheck && $setting['is_order'] == false) {
            return false;
        }
        // 整理订单列表
        $orderListParams = $this->getOrderList($orderList, true);
        // 执行api请求
        $status = $this->ApiDriver->import($orderListParams);
        if ($status == false) {
            $this->error = $this->ApiDriver->getError();
            return $status;
        }
        // 新增好物圈订单记录
        $this->model()->add($this->wxappId, $orderList);
        return $status;
    }

    /**
     * 更新好物圈订单信息
     * @param array|\think\Collection $orderList 订单列表
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \Exception
     */
    public function update($orderList)
    {
        // 过滤不存在的订单列表
        $legalList = $this->getLegalOrderList($orderList);
        if (empty($legalList)) {
            return false;
        }
        // 整理订单列表
        $orderListParams = $this->getOrderList($legalList);
        // 执行api请求
        $status = $this->ApiDriver->update($orderListParams);
        if ($status == false) {
            $this->error = $this->ApiDriver->getError();
            return $status;
        }
        // 更新好物圈订单记录
        $this->model()->edit($legalList);
        return $status;
    }

    /**
     * 获取存在好物圈记录的订单列表
     * 用于过滤不存在好物圈同步记录的订单
     * @param array|\think\Collection $orderList 订单列表
     * @param int $orderType
     * @return array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getLegalOrderList($orderList, $orderType = OrderTypeEnum::MASTER)
    {
        // 把order_id设置为key
        $orderList = helper::arrayColumn2Key($orderList, 'order_id');
        // 查询出合法的id集
        $legalOrderList = $this->model()->getListByOrderIds(array_keys($orderList), $orderType);
        // 遍历合法的订单信息
        $legalList = [];
        foreach ($legalOrderList as $item) {
            $legalList[$item['id']] = $orderList[$item['order_id']];
        }
        return $legalList;
    }

    /**
     * 删除好物圈订单记录
     * @param array $id 订单同步记录id
     * @return bool
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function delete($id)
    {
        // 实例化模型
        $model = $this->model($id, ['user']);
        // 执行api请求
        $status = $this->ApiDriver->delete($model['user']['open_id'], $model['order_id']);
        if ($status == false) {
            $this->error = $this->ApiDriver->getError();
        }
        // 删除订单记录
        $model->setDelete();
        return true;
    }

    /**
     * 返回错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 返回商城id
     * @return mixed
     */
    public function getWxappId()
    {
        return $this->wxappId;
    }

    /**
     * 实例化微信api驱动
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    private function initApiDriver()
    {
        $config = WxappModel::getWxappCache($this->wxappId);
        $this->ApiDriver = new WowOrder($config['app_id'], $config['app_secret']);
    }

    /**
     * 获取好物圈订单记录模型
     * @param int|null $id
     * @param array $with
     * @return OrderModel|null
     * @throws \think\exception\DbException
     */
    private function model($id = null, $with = ['user'])
    {
        static $model;
        if (!$model instanceof OrderModel) {
            $model = $id > 0 ? OrderModel::detail($id, $with) : (new OrderModel);
        }
        return $model;
    }

    /**
     * 整理订单列表 (用于添加好物圈接口)
     * @param $orderList
     * @param bool $isCreate 是否为创建新订单
     * @return array
     */
    private function getOrderList($orderList, $isCreate = false)
    {
        // 整理api参数
        $data = [];
        foreach ($orderList as $order) {
            // 商品列表
            $goodsList = $this->getProductList($order['goods']);
            // 订单记录
            $item = [
                'order_id' => $order['order_id'],
                'trans_id' => $order['transaction_id'],       // 微信支付交易单号
                'status' => self::getStatusByOrder($order),          // 订单状态，3：支付完成 4：已发货 5：已退款 100: 已完成
                'ext_info' => [
                    'user_open_id' => $order['user']['open_id'],
                    'order_detail_page' => [
                        'path' => "pages/order/detail?order_id={$order['order_id']}"
                    ],
                ],
            ];

            // 用于更新订单的参数
            if ($isCreate == false) {

                // 快递及包裹信息
                // 条件1：配送方式为快递配送
                // 条件2: 订单已发货
                if (
                    $order['delivery_type']['value'] == DeliveryTypeEnum::EXPRESS
                    && $order['delivery_status']['value'] == 20
                ) {
                    $item['ext_info']['express_info']['express_package_info_list'] = [[
                        'express_company_id' => $order['express']['express_id'],   // 快递公司id
                        'express_company_name' => $order['express']['express_name'], // 快递公司名
                        'express_code' => $order['express_no'],     // 快递单号
                        'ship_time' => $order['delivery_time'],    // 发货时间
                        'express_page' => [
                            'path' => "pages/order/detail?order_id={$order['order_id']}"
                        ],
                        'express_goods_info_list' => helper::getArrayColumns($goodsList, ['item_code', 'sku_id'])
                    ]];
                }

            }

            // 用于新增订单的参数
            if ($isCreate == true) {
                // 订单创建时间
                $item['create_time'] = $order['create_time'];
                // 支付完成时间
                $item['pay_finish_time'] = $order['pay_time'];
                // 订单金额，单位：分
                $item['fee'] = $order['pay_price'] * 100;
                // 订单支付方式，0：未知方式 1：微信支付 2：其他支付方式
                $item['ext_info']['payment_method'] = $order['pay_type']['value'] == PayTypeEnum::WECHAT ? 1 : 2;
                // 商品列表
                $item['ext_info']['product_info'] = ['item_list' => $goodsList];
                // 收件人信息
                $item['ext_info']['express_info'] = array_merge(
                    $this->getAddressInfo($order['delivery_type']['value'], $order['address']),
                    ['price' => $order['express_price'] * 100]  // 运费
                );
                // todo: 商家信息
                $item['ext_info']['brand_info'] = [
                    'phone' => '020-666666',    // 必填：联系电话
                    'contact_detail_page' => [
                        'kf_type' => 3,
                        'path' => 'pages/index/index',
                    ],
                ];
            }
            $data[] = $item;
        }
        return $data;
    }

    /**
     * 整理订单状态码
     * 订单状态，3：支付完成 4：已发货 5：已退款 100: 已完成
     * @param array $order
     * @return bool|int
     */
    public static function getStatusByOrder($order)
    {
        // 未付款
        if ($order['pay_status']['value'] != 20) {
            return (int)false;
        }
        // 已退款
        if ($order['order_status']['value'] == 20) {
            return 5;
        }
        // 已完成
        if ($order['order_status']['value'] == 30) {
            return 100;
        }
        // 支付完成(未发货)
        if ($order['delivery_status']['value'] == 10) {
            return 3;
        }
        // 已发货
        if ($order['delivery_status']['value'] == 20) {
            return 4;
        }
        return (int)false;
    }

    /**
     * 订单状态，3：支付完成 4：已发货 5：已退款 100: 已完成
     * @return array
     */
    public static function status()
    {
        return [
            0 => '未知',
            3 => '支付完成',
            4 => '已发货',
            5 => '已退款',
            100 => '已完成',
        ];
    }

    /**
     * 整理商品列表
     * @param array $goodsList
     * @return array
     */
    private function getProductList($goodsList)
    {
        $data = [];
        foreach ($goodsList as $goods) {
            $data[] = [
                'item_code' => $goods['goods_id'],         // 物品id，要求appid下全局唯一
                'sku_id' => $goods['goods_id'],
                'amount' => $goods['total_num'],                  // 物品数量
                'total_fee' => $goods['total_price'] * 100,       // 物品总价，单位：分
                'thumb_url' => $goods['image']['file_path'],
                'title' => $goods['goods_name'],            // 商品名称
                'unit_price' => $goods['goods_price'] * 100,          // 物品单价（实际售价）
                'original_price' => $goods['line_price'] * 100,     // 物品原价
                'category_list' => ['商品分类'],    // todo: 商品分类
                'item_detail_page' => ['path' => "pages/goods/index?goods_id={$goods['goods_id']}"],
            ];
        }
        return $data;
    }

    /**
     * 整理收件人信息
     * @param int $deliveryType
     * @param array $express
     * @return array
     */
    private function getAddressInfo($deliveryType, $express)
    {
        // 快递信息
        $data = [];
        if ($deliveryType == DeliveryTypeEnum::EXPRESS) {
            $data = [
                'name' => $express['name'],         // 收件人姓名
                'phone' => $express['phone'],       // 收件人联系电话
                'province' => $express['region']['province'],   // 省
                'city' => $express['region']['city'],           // 市
                'district' => $express['region']['region'],     // 区
            ];
            // 详细地址
            $data['address'] = "{$data['province']} {$data['city']} {$data['district']} {$express['detail']}";
        }
        return $data;
    }

}