<?php

namespace app\common\service\qrcode;

use Grafika\Color;
use Grafika\Grafika;
use app\common\model\dealer\Setting;

/**
 * 分销二维码
 * Class Qrcode
 * @package app\common\service
 */
class Poster extends Base
{
    /* @var \app\common\model\dealer\User $dealer 分销商用户信息 */
    private $dealer;

    /* @var array $config 分销商海报设置 */
    private $config;

    /**
     * 构造方法
     * Poster constructor.
     * @param $dealer
     * @throws \Exception
     */
    public function __construct($dealer)
    {
        parent::__construct();
        // 分销商用户信息
        $this->dealer = $dealer;
        // 分销商海报设置
        $this->config = Setting::getItem('qrcode', $dealer['wxapp_id']);
    }

    /**
     * 获取分销二维码
     * @return string
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function getImage()
    {
        if (file_exists($this->getPosterPath())) {
            return $this->getPosterUrl();
        }
        // 小程序id
        $wxappId = $this->dealer['wxapp_id'];
        // 1. 下载背景图
        $backdrop = $this->saveTempImage($wxappId, $this->config['backdrop']['src'], 'backdrop');
        // 2. 下载用户头像
        $avatarUrl = $this->saveTempImage($wxappId, $this->dealer['user']['avatarUrl'], 'avatar');
        // 3. 下载小程序码
        $qrcode = $this->saveQrcode($wxappId, 'uid:' . $this->dealer['user_id']);
        // 4. 拼接海报图
        return $this->savePoster($backdrop, $avatarUrl, $qrcode);
    }

    /**
     * 海报图文件路径
     * @return string
     */
    private function getPosterPath()
    {
        // 保存路径
        $tempPath = WEB_PATH . 'temp' . DS . $this->dealer['wxapp_id'] . DS;
        !is_dir($tempPath) && mkdir($tempPath, 0755, true);
        return $tempPath . $this->getPosterName();
    }

    /**
     * 海报图文件名称
     * @return string
     */
    private function getPosterName()
    {
        return md5('poster_' . $this->dealer['user_id']) . '.png';
    }

    /**
     * 海报图url
     * @return string
     */
    private function getPosterUrl()
    {
        return \base_url() . 'temp/' . $this->dealer['wxapp_id'] . '/' . $this->getPosterName() . '?t=' . time();
    }

    /**
     * 拼接海报图
     * @param $backdrop
     * @param $avatarUrl
     * @param $qrcode
     * @return string
     * @throws \Exception
     */
    private function savePoster($backdrop, $avatarUrl, $qrcode)
    {
        // 实例化图像编辑器
        $editor = Grafika::createEditor(['Gd']);
        // 打开海报背景图
        $editor->open($backdropImage, $backdrop);
        // 生成圆形用户头像
        $this->config['avatar']['style'] === 'circle' && $this->circular($avatarUrl, $avatarUrl);
        // 打开用户头像
        $editor->open($avatarImage, $avatarUrl);
        // 重设用户头像宽高
        $avatarWidth = $this->config['avatar']['width'] * 2;
        $editor->resizeExact($avatarImage, $avatarWidth, $avatarWidth);
        // 用户头像添加到背景图
        $avatarX = $this->config['avatar']['left'] * 2;
        $avatarY = $this->config['avatar']['top'] * 2;
        $editor->blend($backdropImage, $avatarImage, 'normal', 1.0, 'top-left', $avatarX, $avatarY);

        // 生成圆形小程序码
        $this->config['qrcode']['style'] === 'circle' && $this->circular($qrcode, $qrcode);
        // 打开小程序码
        $editor->open($qrcodeImage, $qrcode);
        // 重设小程序码宽高
        $qrcodeWidth = $this->config['qrcode']['width'] * 2;
        $editor->resizeExact($qrcodeImage, $qrcodeWidth, $qrcodeWidth);
        // 小程序码添加到背景图
        $qrcodeX = $this->config['qrcode']['left'] * 2;
        $qrcodeY = $this->config['qrcode']['top'] * 2;
        $editor->blend($backdropImage, $qrcodeImage, 'normal', 1.0, 'top-left', $qrcodeX, $qrcodeY);

        // 写入用户昵称
        $fontSize = $this->config['nickName']['fontSize'] * 2 * 0.76;
        $fontX = $this->config['nickName']['left'] * 2;
        $fontY = $this->config['nickName']['top'] * 2;
        $Color = new Color($this->config['nickName']['color']);
        $fontPath = Grafika::fontsDir() . DS . 'st-heiti-light.ttc';
        $editor->text($backdropImage, $this->dealer['user']['nickName'], $fontSize, $fontX, $fontY, $Color, $fontPath);

        // 保存图片
        $editor->save($backdropImage, $this->getPosterPath());
        return $this->getPosterUrl();
    }

    /**
     * 生成圆形图片
     * @param static $imgpath 图片地址
     * @param string $saveName 保存文件名，默认空。
     */
    private function circular($imgpath, $saveName = '')
    {
        $srcImg = imagecreatefromstring(file_get_contents($imgpath));
        $w = imagesx($srcImg);
        $h = imagesy($srcImg);
        $w = $h = min($w, $h);
        $newImg = imagecreatetruecolor($w, $h);
        // 这一句一定要有
        imagesavealpha($newImg, true);
        // 拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        imagefill($newImg, 0, 0, $bg);
        $r = $w / 2; //圆半径
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($srcImg, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($newImg, $x, $y, $rgbColor);
                }
            }
        }
        // 输出图片到文件
        imagepng($newImg, $saveName);
        // 释放空间
        imagedestroy($srcImg);
        imagedestroy($newImg);
    }

}