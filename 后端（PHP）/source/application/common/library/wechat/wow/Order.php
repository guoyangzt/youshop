<?php

namespace app\common\library\wechat\wow;

use app\common\library\wechat\WxBase;

/**
 * 微信好物圈-订单接口
 * Class Order
 * @url https://wsad.weixin.qq.com/wsad/zh_CN/htmledition/order/html/document/orderlist/import.part.html
 * @package app\common\library\wechat\wow
 */
class Order extends WxBase
{
    /**
     * 接口方法描述
     * @var array
     */
    private $describe = [
        'import' => '导入订单',
        'update' => '更新订单信息',
        'delete' => '删除订单',
    ];

    /**
     * 导入订单
     * @param $orderList
     * @param int $isHistory
     * @return bool
     * @throws \app\common\exception\BaseException
     */
    public function import($orderList, $isHistory = 0)
    {
        // 微信接口url
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/mall/importorder?action=add-order&is_history={$isHistory}&access_token={$accessToken}";
        // 请求参数
        $params = $this->jsonEncode(['order_list' => $orderList]);
        // 执行请求
        $result = $this->post($url, $params);
        // 记录日志
        $this->doLogs(['describe' => $this->describe['import'], 'url' => $url, 'params' => $params, 'result' => $result]);
        // 返回结果
        $response = $this->jsonDecode($result);
        if (!isset($response['errcode'])) {
            $this->error = 'not found errcode';
            return false;
        }
        if ($response['errcode'] != 0) {
            $this->error = $response['errmsg'];
            return false;
        }
        return true;
    }

    /**
     * 更新订单
     * @param $orderList
     * @param int $isHistory
     * @return bool
     * @throws \app\common\exception\BaseException
     */
    public function update($orderList, $isHistory = 0)
    {
        // 微信接口url
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/mall/importorder?action=update-order&is_history={$isHistory}&access_token={$accessToken}";
        // 请求参数
        $params = $this->jsonEncode(['order_list' => $orderList]);
        // 执行请求
        $result = $this->post($url, $params);
        // 记录日志
        $this->doLogs(['describe' => $this->describe['update'], 'url' => $url, 'params' => $params, 'result' => $result]);
        // 返回结果
        $response = $this->jsonDecode($result);
        if (!isset($response['errcode'])) {
            $this->error = 'not found errcode';
            return false;
        }
        if ($response['errcode'] != 0) {
            $this->error = $response['errmsg'];
            return false;
        }
        return true;
    }

    /**
     * 删除商品收藏
     * @param string $openId
     * @param string $orderId
     * @return bool
     * @throws \app\common\exception\BaseException
     */
    public function delete($openId, $orderId)
    {
        // 微信接口url
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/mall/deleteorder?access_token={$accessToken}";
        // 请求参数
        $params = [
            'user_open_id' => $openId,
            'order_id' => $orderId
        ];
        // 执行请求
        $result = $this->post($url, $this->jsonEncode($params));
        // 记录日志
        $this->doLogs(['describe' => $this->describe['delete'], 'url' => $url, 'params' => $params, 'result' => $result]);
        // 返回结果
        $response = $this->jsonDecode($result);
        if (!isset($response['errcode'])) {
            $this->error = 'not found errcode';
            return false;
        }
        if ($response['errcode'] != 0) {
            $this->error = $response['errmsg'];
            return false;
        }
        return true;
    }

}