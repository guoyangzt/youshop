<?php

namespace app\common\library\wechat\wow;

use app\common\library\wechat\WxBase;

/**
 * 微信好物圈-收藏接口
 * Class Shoping
 * @url https://wsad.weixin.qq.com/wsad/zh_CN/htmledition/order/html/document/cartlist/import.part.html
 * @package app\common\library\wechat\wow
 */
class Shoping extends WxBase
{
    /**
     * 导入商品收藏
     * @param string $openId
     * @param array $productList
     * @return bool
     * @throws \app\common\exception\BaseException
     */
    public function addList($openId, $productList)
    {
        // 微信接口url
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/mall/addshoppinglist?access_token={$accessToken}";
        // 请求参数
        $params = $this->jsonEncode([
            'user_open_id' => $openId,
            'sku_product_list' => $productList
        ]);
        // 执行请求
        $result = $this->post($url, $params);
        // 记录日志
        $this->doLogs(['describe' => '新增好物圈商品收藏', 'url' => $url, 'params' => $params, 'result' => $result]);
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
     * @param $openId
     * @param $productList
     * @return bool
     * @throws \app\common\exception\BaseException
     */
    public function delete($openId, $productList)
    {
        // 微信接口url
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/mall/deleteshoppinglist?access_token={$accessToken}";
        // 请求参数
        $params = [
            'user_open_id' => $openId,
            'sku_product_list' => $productList
        ];
        // 执行请求
        $result = $this->post($url, $this->jsonEncode($params));
        // 记录日志
        $this->doLogs(['describe' => '删除好物圈商品收藏', 'url' => $url, 'params' => $params, 'result' => $result]);
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