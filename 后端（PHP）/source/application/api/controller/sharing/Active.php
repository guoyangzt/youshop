<?php

namespace app\api\controller\sharing;

use app\api\controller\Controller;
use app\api\model\sharing\Active as ActiveModel;
use app\api\model\sharing\Goods as GoodsModel;

/**
 * 拼团拼单控制器
 * Class Active
 * @package app\api\controller\sharing
 */
class Active extends Controller
{
    /**
     * 拼单详情
     * @param $active_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function detail($active_id)
    {
        // 拼单详情
        $detail = ActiveModel::detail($active_id);
        if (!$detail) {
            return $this->renderError('很抱歉，拼单不存在');
        }
        // 拼团商品详情
        $goods = GoodsModel::getDetails($detail['goods_id'], $this->getUser(false));
        // 多规格商品sku信息, todo: 已废弃 v1.1.25
        $specData = $goods['spec_type'] == 20 ? $goods->getManySpecData($goods['spec_rel'], $goods['sku']) : null;
        // 更多拼团商品
        $model = new GoodsModel;
        $goodsList = $model->getList([], $this->getUser(false));
        return $this->renderSuccess(compact('detail', 'goods', 'goodsList', 'specData'));
    }

}
