<?php

namespace app\common\service\qrcode;

use app\common\enum\OrderType as OrderTypeEnum;

/**
 * 订单核销二维码
 * Class Extract
 * @package app\common\service\qrcode
 */
class Extract extends Base
{
    private $wxappId;

    /* @var int $user 用户 */
    private $user;

    private $orderId;

    private $orderType;

    /**
     * 构造方法
     * Extract constructor.
     * @param $wxappId
     * @param $user
     * @param $orderId
     * @param $orderType
     */
    public function __construct($wxappId, $user, $orderId, $orderType = OrderTypeEnum::MASTER)
    {
        parent::__construct();
        $this->wxappId = $wxappId;
        $this->user = $user;
        $this->orderId = $orderId;
        $this->orderType = $orderType;
    }

    /**
     * 获取小程序码
     * @return mixed
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function getImage()
    {
        // 判断二维码文件存在则直接返回url
        if (file_exists($this->getPosterPath())) {
            return $this->getPosterUrl();
        }
        // 下载小程序码
        $qrcode = $this->saveQrcode(
            $this->wxappId,
            "oid:{$this->orderId},oty:{$this->orderType}",
            'pages/store/check/order'
        );
        return $this->savePoster($qrcode);
    }

    private function savePoster($qrcode)
    {
        copy($qrcode, $this->getPosterPath());
        return $this->getPosterUrl();
    }

    /**
     * 二维码文件路径
     * @return string
     */
    private function getPosterPath()
    {
        // 保存路径
        $tempPath = WEB_PATH . "temp/{$this->wxappId}/";
        !is_dir($tempPath) && mkdir($tempPath, 0755, true);
        return $tempPath . $this->getPosterName();
    }

    /**
     * 二维码文件名称
     * @return string
     */
    private function getPosterName()
    {
        return 'extract_' . md5("{$this->orderId}_{$this->user['open_id']}}") . '.png';
    }

    /**
     * 二维码url
     * @return string
     */
    private function getPosterUrl()
    {
        return \base_url() . 'temp/' . $this->wxappId . '/' . $this->getPosterName() . '?t=' . time();
    }

}