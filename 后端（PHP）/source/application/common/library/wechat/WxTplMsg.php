<?php

namespace app\common\library\wechat;

/**
 * 微信模板消息
 * Class WxTplMsg
 * @package app\common\library\wechat
 */
class WxTplMsg extends WxBase
{
    /**
     * 发送模板消息
     * @param array $param
     * @return bool
     * @throws \app\common\exception\BaseException
     */
    public function sendTemplateMessage($param)
    {
        // 微信接口url
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$accessToken}";
        // 构建请求
        $params = [
            'touser' => $param['touser'],
            'template_id' => $param['template_id'],
            'page' => $param['page'],
            'form_id' => $param['form_id'],
            'data' => $this->createData($param['data'])
        ];
        $result = $this->post($url, $this->jsonEncode($params));
        // 记录日志
        $this->doLogs(['describe' => '发送模板消息', 'url' => $url, 'params' => $params, 'result' => $result]);
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
     * 生成关键字数据
     * @param $data
     * @return array
     */
    private function createData($data)
    {
        $params = [];
        foreach ($data as $key => $value) {
            $params[$key] = [
                'value' => $value,
                'color' => '#333333'
            ];
        }
        return $params;
    }

}