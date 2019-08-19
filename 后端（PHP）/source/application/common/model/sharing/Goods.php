<?php

namespace app\common\model\sharing;

use app\common\model\BaseModel;
use app\common\library\helper;

/**
 * 拼团商品模型
 * Class Goods
 * @package app\common\model\sharing
 */
class Goods extends BaseModel
{
    protected $name = 'sharing_goods';
    protected $append = ['goods_sales'];

    /**
     * 计算显示销量 (初始销量 + 实际销量)
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getGoodsSalesAttr($value, $data)
    {
        return $data['sales_initial'] + $data['sales_actual'];
    }

    /**
     * 获取器：单独设置折扣的配置
     * @param $json
     * @return mixed
     */
    public function getAloneGradeEquityAttr($json)
    {
        return json_decode($json, true);
    }

    /**
     * 修改器：单独设置折扣的配置
     * @param $data
     * @return mixed
     */
    public function setAloneGradeEquityAttr($data)
    {
        return json_encode($data);
    }

    /**
     * 关联商品分类表
     * @return \think\model\relation\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('Category');
    }

    /**
     * 关联商品规格表
     * @return \think\model\relation\HasMany
     */
    public function sku()
    {
        return $this->hasMany('GoodsSku', 'goods_id')->order(['goods_sku_id' => 'asc']);
    }

    /**
     * 关联商品规格关系表
     * @return \think\model\relation\BelongsToMany
     */
    public function specRel()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->belongsToMany(
            "app\\{$module}\\model\\SpecValue",
            'SharingGoodsSpecRel',
            'spec_value_id',
            'goods_id'
        );
    }

    /**
     * 关联商品图片表
     * @return \think\model\relation\HasMany
     */
    public function image()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->hasMany("app\\{$module}\\model\\sharing\\GoodsImage", 'goods_id')
            ->order(['id' => 'asc']);
    }

    /**
     * 关联运费模板表
     * @return \think\model\relation\BelongsTo
     */
    public function delivery()
    {
        $module = self::getCalledModule() ?: 'common';
        return $this->BelongsTo("app\\{$module}\\model\\Delivery");
    }

    /**
     * 关联订单评价表
     * @return \think\model\relation\HasMany
     */
    public function commentData()
    {
        return $this->hasMany('Comment', 'goods_id');
    }

    /**
     * 计费方式
     * @param $value
     * @return mixed
     */
    public function getGoodsStatusAttr($value)
    {
        $status = [10 => '上架', 20 => '下架'];
        return ['text' => $status[$value], 'value' => $value];
    }

    /**
     * 获取商品列表
     * @param $param
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function getList($param)
    {

        // 商品列表获取条件
        $params = array_merge([
            'status' => 10,         // 商品状态
            'category_id' => 0,     // 分类id
            'search' => '',         // 搜索关键词
            'sortType' => 'all',    // 排序类型
            'sortPrice' => false,   // 价格排序 高低
            'listRows' => 15,       // 每页数量
        ], $param);
        // 筛选条件
        $filter = [];
        $params['category_id'] > 0 && $filter['category_id'] = ['IN', Category::getSubCategoryId($params['category_id'])];
        $params['status'] > 0 && $filter['goods_status'] = $params['status'];
        !empty($params['search']) && $filter['goods_name'] = ['like', '%' . trim($params['search']) . '%'];
        // 排序规则
        $sort = [];
        if ($params['sortType'] === 'all') {
            $sort = ['goods_sort', 'goods_id' => 'desc'];
        } elseif ($params['sortType'] === 'sales') {
            $sort = ['goods_sales' => 'desc'];
        } elseif ($params['sortType'] === 'price') {
            $sort = $params['sortPrice'] ? ['goods_max_price' => 'desc'] : ['goods_min_price'];
        }
        // 商品表名称
        $tableName = $this->getTable();
        // 多规格商品 最高价与最低价
        $GoodsSku = new GoodsSku;
        $minPriceSql = $GoodsSku->field(['MIN(sharing_price)'])
            ->where('goods_id', 'EXP', "= `$tableName`.`goods_id`")->buildSql();
        $maxPriceSql = $GoodsSku->field(['MAX(sharing_price)'])
            ->where('goods_id', 'EXP', "= `$tableName`.`goods_id`")->buildSql();
        // 执行查询
        $list = $this
            ->field(['*', '(sales_initial + sales_actual) as goods_sales',
                "$minPriceSql AS goods_min_price",
                "$maxPriceSql AS goods_max_price"
            ])
            ->with(['category', 'image.file', 'sku'])
            ->where('is_delete', '=', 0)
            ->where($filter)
            ->order($sort)
            ->paginate($params['listRows'], false, [
                'query' => \request()->request()
            ]);
        // 整理列表数据并返回
        return $this->setGoodsListData($list, true);
    }

    /**
     * 设置商品展示的数据
     * @param $data
     * @param bool $isMultiple
     * @param callable $callback
     * @return mixed
     */
    protected function setGoodsListData(&$data, $isMultiple = true, callable $callback = null)
    {
        if (!$isMultiple) $dataSource = [&$data]; else $dataSource = &$data;
        // 整理商品列表数据
        foreach ($dataSource as &$goods) {
            // 商品默认规格
            $goodsSku = $goods['sku'][0];
            // 商品默认数据
            $goods['goods_image'] = $goods['image'][0]['file_path'];
            $goods['goods_sku'] = $goodsSku;
            // 回调函数
            is_callable($callback) && call_user_func($callback, $goods);
        }
        return $data;
    }

    /**
     * 根据商品id集获取商品列表
     * @param array $goodsIds
     * @param null $status
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getListByIds($goodsIds, $status = null)
    {
        // 筛选条件
        $filter = ['goods_id' => ['in', $goodsIds]];
        $status > 0 && $filter['goods_status'] = $status;
        if (!empty($goodsIds)) {
            $this->orderRaw('field(goods_id, ' . implode(',', $goodsIds) . ')');
        }
        // 获取商品列表数据
        $data = $this->with(['category', 'image.file', 'sku', 'spec_rel.spec', 'delivery.rule'])
            ->where($filter)
            ->select();
        if ($data->isEmpty()) return $data;
        // 格式化数据
        foreach ($data as &$item) {
            $item['goods_image'] = $item['image'][0]['file_path'];
        }
        return $data;
    }

    /**
     * 商品多规格信息
     * @param \think\Collection $spec_rel
     * @param \think\Collection $skuData
     * @return array
     */
    public function getManySpecData($spec_rel, $skuData)
    {
        // spec_attr
        $specAttrData = [];
        foreach ($spec_rel->toArray() as $item) {
            if (!isset($specAttrData[$item['spec_id']])) {
                $specAttrData[$item['spec_id']] = [
                    'group_id' => $item['spec']['spec_id'],
                    'group_name' => $item['spec']['spec_name'],
                    'spec_items' => [],
                ];
            }
            $specAttrData[$item['spec_id']]['spec_items'][] = [
                'item_id' => $item['spec_value_id'],
                'spec_value' => $item['spec_value'],
            ];
        }
        // spec_list
        $specListData = [];
        foreach ($skuData->toArray() as $item) {
            $image = (isset($item['image']) && !empty($item['image'])) ? $item['image'] : ['file_id' => 0, 'file_path' => ''];
            $specListData[] = [
                'goods_sku_id' => $item['goods_sku_id'],
                'spec_sku_id' => $item['spec_sku_id'],
                'rows' => [],
                'form' => [
                    'image_id' => $image['file_id'],
                    'image_path' => $image['file_path'],
                    'goods_no' => $item['goods_no'],
                    'goods_price' => $item['goods_price'],
                    'sharing_price' => $item['sharing_price'],
                    'goods_weight' => $item['goods_weight'],
                    'line_price' => $item['line_price'],
                    'stock_num' => $item['stock_num'],
                ],
            ];
        }
        return ['spec_attr' => array_values($specAttrData), 'spec_list' => $specListData];
    }

    /**
     * 多规格表格数据
     * @param $goods
     * @return array
     */
    public function getManySpecTable(&$goods)
    {
        $specData = $this->getManySpecData($goods['spec_rel'], $goods['sku']);
        $totalRow = count($specData['spec_list']);
        foreach ($specData['spec_list'] as $i => &$sku) {
            $rowData = [];
            $rowCount = 1;
            foreach ($specData['spec_attr'] as $attr) {
                $skuValues = $attr['spec_items'];
                $rowCount *= count($skuValues);
                $anInterBankNum = ($totalRow / $rowCount);
                $point = (($i / $anInterBankNum) % count($skuValues));
                if (0 === ($i % $anInterBankNum)) {
                    $rowData[] = [
                        'rowspan' => $anInterBankNum,
                        'item_id' => $skuValues[$point]['item_id'],
                        'spec_value' => $skuValues[$point]['spec_value']
                    ];
                }
            }
            $sku['rows'] = $rowData;
        }
        return $specData;
    }

    /**
     * 获取商品详情
     * @param $goods_id
     * @return static|false|\PDOStatement|string|\think\Model
     */
    public static function detail($goods_id)
    {
        $model = new static;
        return $model->with([
            'category',
            'image.file',
            'sku.image',
            'spec_rel.spec',
            'delivery.rule',
            'commentData' => function ($query) {
                $query->with('user')->where(['is_delete' => 0, 'status' => 1])->limit(2);
            }
        ])->withCount(['commentData' => function ($query) {
            $query->where(['is_delete' => 0, 'status' => 1]);
        }])->where('goods_id', '=', $goods_id)->find();
    }

    /**
     * 指定的商品规格信息
     * @param static $goods 商品详情
     * @param int $specSkuId
     * @return array|bool
     */
    public static function getGoodsSku($goods, $specSkuId)
    {
        // 获取指定的sku
        $goodsSku = [];
        foreach ($goods['sku'] as $item) {
            if ($item['spec_sku_id'] == $specSkuId) {
                $goodsSku = $item;
                break;
            }
        }
        if (empty($goodsSku)) {
            return false;
        }
        // 多规格文字内容
        $goodsSku['goods_attr'] = '';
        if ($goods['spec_type'] == 20) {
            $specRelData = helper::arrayColumn2Key($goods['spec_rel'], 'spec_value_id');
            $attrs = explode('_', $goodsSku['spec_sku_id']);
            foreach ($attrs as $specValueId) {
                $goodsSku['goods_attr'] .= $specRelData[$specValueId]['spec']['spec_name'] . ':'
                    . $specRelData[$specValueId]['spec_value'] . '; ';
            }
        }
        return $goodsSku;
    }

}
