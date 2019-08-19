<?php

namespace app\common\model\wow;

use app\common\model\BaseModel;

/**
 * 好物圈商品收藏记录模型
 * Class Shoping
 * @package app\common\model\wow
 */
class Shoping extends BaseModel
{
    protected $name = 'wow_shoping';
    protected $updateTime = false;
    protected $alias = 'shoping';

    /**
     * 关联商品表
     * @return \think\model\relation\BelongsTo
     */
    public function goods()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\Goods");
    }

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsTo("app\\{$module}\\model\\User");
    }

    /**
     * 获取单条记录
     * @param $id
     * @param $with
     * @return static|null
     * @throws \think\exception\DbException
     */
    public static function detail($id, $with = ['goods.image.file', 'user'])
    {
        return static::get($id, $with);
    }

    /**
     * 新增好物圈商品收藏记录
     * @param int $userId 用户id
     * @param array $goodsIds 商品id
     * @return array|false
     * @throws \Exception
     */
    public function add($userId, $goodsIds)
    {
        // 过滤该用户已收藏的商品id
        $newGoodsIds = $this->getFilterGoodsIds($userId, $goodsIds);
        if (empty($newGoodsIds)) {
            return false;
        }
        $saveData = [];
        foreach ($newGoodsIds as $goodsId) {
            $saveData[] = [
                'goods_id' => $goodsId,
                'user_id' => $userId,
                'wxapp_id' => self::$wxapp_id,
            ];
        }
        return $this->isUpdate(false)->saveAll($saveData);
    }

    /**
     * 软删除
     * @return false|int
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }

    /**
     * 过滤指定用户已收藏的商品id
     * @param $userId
     * @param $newGoodsIds
     * @return array
     */
    private function getFilterGoodsIds($userId, $newGoodsIds)
    {
        $alreadyGoodsId = $this->where('user_id', '=', $userId)
            ->where('is_delete', '=', 0)
            ->column('goods_id');
        return array_diff($newGoodsIds, $alreadyGoodsId);
    }

}