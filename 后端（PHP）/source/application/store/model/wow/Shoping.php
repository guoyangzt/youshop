<?php

namespace app\store\model\wow;

use app\common\model\wow\Shoping as ShopingModel;


/**
 * 好物圈商品收藏记录模型
 * Class Shoping
 * @package app\store\model\wow
 */
class Shoping extends ShopingModel
{
    /**
     * 获取列表
     * @param string $search
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($search = '')
    {
        $this->setBaseQuery($this->alias, [
            ['goods', 'goods_id'],
            ['user', 'user_id'],
        ]);
        // 检索查询条件
        if (!empty($search)) {
            $this->where(function ($query) use ($search) {
                $query->whereOr('goods.goods_name', 'like', "%{$search}%")
                    ->whereOr('user.nickName', 'like', "%{$search}%");
            });
        }
        // 返回列表数据
        return $this->with(['goods.image.file', 'user'])
            ->where("{$this->alias}.is_delete", '=', 0)
            ->order(["{$this->alias}.create_time" => 'desc'])
            ->paginate(15, false, [
                'query' => request()->request()
            ]);
    }

}