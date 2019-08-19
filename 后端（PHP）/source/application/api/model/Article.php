<?php

namespace app\api\model;

use app\common\exception\BaseException;
use app\common\model\Article as ArticleModel;

/**
 * 商品评价模型
 * Class Article
 * @package app\api\model
 */
class Article extends ArticleModel
{
    /**
     * 追加字段
     * @var array
     */
    protected $append = [
        'show_views',
        'view_time'
    ];

    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'is_delete',
        'wxapp_id',
        'create_time',
        'update_time'
    ];

    public function getViewTimeAttr($value, $data)
    {
        return date('Y-m-d', $data['create_time']);
    }

    /**
     * 文章详情
     * @param $article_id
     * @return ArticleModel|null
     * @throws BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public static function detail($article_id)
    {
        if (!$model = parent::detail($article_id)) {
            throw new BaseException(['msg' => '文章不存在']);
        }
        // 累积阅读数
        $model->setInc('actual_views', 1);
        return $model;
    }

    /**
     * 获取文章列表
     * @param int $category_id
     * @param int $limit
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($category_id = 0, $limit = 15)
    {
        $category_id > 0 && $this->where('category_id', '=', $category_id);
        return $this->field(['article_content'], true)
            ->with(['image', 'category'])
            ->where('article_status', '=', 1)
            ->where('is_delete', '=', 0)
            ->order(['article_sort' => 'asc', 'create_time' => 'desc'])
            ->paginate($limit, false, [
                'query' => \request()->request()
            ]);
    }

}