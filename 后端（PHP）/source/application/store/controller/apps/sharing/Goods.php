<?php

namespace app\store\controller\apps\sharing;

use app\store\controller\Controller;
use app\store\model\user\Grade as GradeModel;
use app\store\model\Delivery as DeliveryModel;
use app\store\model\sharing\Goods as GoodsModel;
use app\store\model\sharing\Category as CategoryModel;

/**
 * 拼团商品管理控制器
 * Class Goods
 * @package app\store\controller\apps\sharing
 */
class Goods extends Controller
{
    /**
     * 商品列表(出售中)
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        // 获取全部商品列表
        $model = new GoodsModel;
        $list = $model->getList(array_merge(['status' => -1], $this->request->param()));
        // 商品分类
        $catgory = CategoryModel::getCacheTree();
        return $this->fetch('index', compact('list', 'catgory'));
    }

    /**
     * 添加商品
     * @return array|mixed
     * @throws \think\exception\PDOException
     */
    public function add()
    {
        if (!$this->request->isAjax()) {
            // 商品分类
            $catgory = CategoryModel::getCacheTree();
            // 配送模板
            $delivery = DeliveryModel::getAll();
            // 会员等级列表
            $gradeList = GradeModel::getUsableList();
            return $this->fetch('add', compact('catgory', 'delivery', 'gradeList'));
        }
        $model = new GoodsModel;
        if ($model->add($this->postData('goods'))) {
            return $this->renderSuccess('添加成功', url('apps.sharing.goods/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 复制主商城商品
     * @param $goods_id
     * @return array|mixed
     * @throws \think\exception\PDOException
     */
    public function copy_master($goods_id)
    {
        // 商品详情
        $model = \app\store\model\Goods::detail($goods_id);
        if (!$model || $model['is_delete']) {
            return $this->renderError('商品信息不存在');
        }
        if (!$this->request->isAjax()) {
            // 商品分类
            $catgory = CategoryModel::getCacheTree();
            // 配送模板
            $delivery = DeliveryModel::getAll();
            // 商品sku数据
            $specData = 'null';
            if ($model['spec_type'] == 20) {
                $specData = json_encode($model->getManySpecData($model['spec_rel'], $model['sku']), JSON_UNESCAPED_SLASHES);
            }
            // 会员等级列表
            $gradeList = GradeModel::getUsableList();
            return $this->fetch('copy_master', compact('model', 'catgory', 'delivery', 'specData', 'gradeList'));
        }
        // 新增拼团商品
        $model = new GoodsModel;
        if ($model->add($this->postData('goods'))) {
            return $this->renderSuccess('添加成功', url('apps.sharing.goods/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 一键复制
     * @param $goods_id
     * @return array|mixed
     * @throws \think\exception\PDOException
     */
    public function copy($goods_id)
    {
        // 商品详情
        $model = GoodsModel::detail($goods_id);
        if (!$this->request->isAjax()) {
            // 商品分类
            $catgory = CategoryModel::getCacheTree();
            // 配送模板
            $delivery = DeliveryModel::getAll();
            // 商品sku数据
            $specData = 'null';
            if ($model['spec_type'] == 20) {
                $specData = json_encode($model->getManySpecData($model['spec_rel'], $model['sku']), JSON_UNESCAPED_SLASHES);
            }
            // 会员等级列表
            $gradeList = GradeModel::getUsableList();
            return $this->fetch('edit', compact('model', 'catgory', 'delivery', 'specData', 'gradeList'));
        }
        $model = new GoodsModel;
        if ($model->add($this->postData('goods'))) {
            return $this->renderSuccess('添加成功', url('apps.sharing.goods/index'));
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 商品编辑
     * @param $goods_id
     * @return array|mixed
     * @throws \think\exception\PDOException
     */
    public function edit($goods_id)
    {
        // 商品详情
        $model = GoodsModel::detail($goods_id);
        if (!$this->request->isAjax()) {
            // 商品分类
            $catgory = CategoryModel::getCacheTree();
            // 配送模板
            $delivery = DeliveryModel::getAll();
            // 商品sku数据
            $specData = 'null';
            if ($model['spec_type'] == 20) {
                $specData = json_encode($model->getManySpecData($model['spec_rel'], $model['sku']), JSON_UNESCAPED_SLASHES);
            }
            // 会员等级列表
            $gradeList = GradeModel::getUsableList();
            return $this->fetch('edit', compact('model', 'catgory', 'delivery', 'specData', 'gradeList'));
        }
        // 更新记录
        if ($model->edit($this->postData('goods'))) {
            return $this->renderSuccess('更新成功', url('apps.sharing.goods/index'));
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 修改商品状态
     * @param $goods_id
     * @param boolean $state
     * @return array
     */
    public function state($goods_id, $state)
    {
        // 商品详情
        $model = GoodsModel::detail($goods_id);
        if (!$model->setStatus($state)) {
            return $this->renderError('操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /**
     * 删除商品
     * @param $goods_id
     * @return array
     */
    public function delete($goods_id)
    {
        // 商品详情
        $model = GoodsModel::detail($goods_id);
        if (!$model->setDelete()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
