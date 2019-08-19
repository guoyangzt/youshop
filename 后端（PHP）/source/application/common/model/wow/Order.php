<?php

namespace app\common\model\wow;

use app\common\library\helper;
use app\common\model\BaseModel;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\service\wechat\wow\Order as WowOrderService;

/**
 * 好物圈订单同步记录模型
 * Class Order
 * @package app\common\model\wow
 */
class Order extends BaseModel
{
    protected $name = 'wow_order';
    protected $alias = 'wow_order';

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
     * 获取器：最后更新时间
     * @param $value
     * @return array
     */
    public function getLastTimeAttr($value)
    {
        return ['value' => $value, 'text' => date('Y-m-d H:i:s', $value)];
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
     * 添加好物圈订单同步记录(批量)
     * @param $wxappId
     * @param $orderList
     * @param $orderType
     * @return array|false
     * @throws \Exception
     */
    public function add($wxappId, $orderList, $orderType = OrderTypeEnum::MASTER)
    {
        // 批量添加
        $saveData = [];
        foreach ($orderList as $item) {
            $saveData[] = [
                'order_id' => $item['order_id'],
                'user_id' => $item['user_id'],
                'order_type' => $orderType,
                'status' => WowOrderService::getStatusByOrder($item),
                'last_time' => time(),
                'wxapp_id' => $wxappId,
            ];
        }
        return $this->isUpdate(false)->saveAll($saveData);
    }

    /**
     * 更新好物圈订单同步记录(批量)
     * @param $legalList
     * @return array|false
     * @throws \Exception
     */
    public function edit($legalList)
    {
        // 批量更新
        $saveData = [];
        foreach ($legalList as $id => $item) {
            $saveData[] = [
                'id' => $id,
                'status' => WowOrderService::getStatusByOrder($item),
                'last_time' => time(),
            ];
        }
        return $this->isUpdate()->saveAll($saveData);
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
     * 根据订单id集和订单类型获取记录
     * @param array $orderIds
     * @param int $orderType
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getListByOrderIds($orderIds, $orderType = OrderTypeEnum::MASTER)
    {
        return $this->where('order_id', 'in', $orderIds)
            ->where('order_type', '=', $orderType)
            ->where('is_delete', '=', 0)
            ->select();
    }

}