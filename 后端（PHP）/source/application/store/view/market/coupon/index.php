<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">优惠券列表</div>
                </div>
                <div class="widget-body am-fr">
                    <div class="tips am-margin-bottom-sm am-u-sm-12">
                        <div class="pre">
                            <p> 注：优惠券只能抵扣商品金额，最多优惠到0.01元，不能抵扣运费</p>
                        </div>
                    </div>
                    <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                        <div class="am-form-group">
                            <div class="am-btn-toolbar">
                                <?php if (checkPrivilege('market.coupon/add')): ?>
                                    <div class="am-btn-group am-btn-group-xs">
                                        <a class="am-btn am-btn-default am-btn-success am-radius"
                                           href="<?= url('market.coupon/add') ?>">
                                            <span class="am-icon-plus"></span> 新增
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="am-u-sm-12 am-scrollable-horizontal">
                        <table width="100%"
                               class="am-table am-table-compact am-table-striped tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>优惠券ID</th>
                                <th>优惠券名称</th>
                                <th>优惠券类型</th>
                                <th>最低消费金额</th>
                                <th>优惠方式</th>
                                <th>有效期</th>
                                <th>发放总数量</th>
                                <th>已领取数量</th>
                                <th>排序</th>
                                <th>添加时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): ?>
                                <?php foreach ($list as $item): ?>
                                    <tr>
                                        <td class="am-text-middle"><?= $item['coupon_id'] ?></td>
                                        <td class="am-text-middle"><?= $item['name'] ?></td>
                                        <td class="am-text-middle"><?= $item['coupon_type']['text'] ?></td>
                                        <td class="am-text-middle"><?= $item['min_price'] ?></td>
                                        <td class="am-text-middle">
                                            <?php if ($item['coupon_type']['value'] == 10) : ?>
                                                <span>立减 <strong><?= $item['reduce_price'] ?></strong> 元</span>
                                            <?php elseif ($item['coupon_type']['value'] == 20) : ?>
                                                <span>打 <strong><?= $item['discount'] ?></strong> 折</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="am-text-middle">
                                            <?php if ($item['expire_type'] == 10) : ?>
                                                <span>领取 <strong><?= $item['expire_day'] ?></strong> 天内有效</span>
                                            <?php elseif ($item['expire_type'] == 20) : ?>
                                                <span><?= $item['start_time']['text'] ?>
                                                    ~ <?= $item['end_time']['text'] ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="am-text-middle"><?= $item['total_num'] == -1 ? '不限制' : $item['total_num'] ?></td>
                                        <td class="am-text-middle"><?= $item['receive_num'] ?></td>
                                        <td class="am-text-middle"><?= $item['sort'] ?></td>

                                        <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                        <td class="am-text-middle">
                                            <div class="tpl-table-black-operation">
                                                <?php if (checkPrivilege('market.coupon/edit')): ?>
                                                    <a href="<?= url('market.coupon/edit', ['coupon_id' => $item['coupon_id']]) ?>">
                                                        <i class="am-icon-pencil"></i> 编辑
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (checkPrivilege('market.coupon/delete')): ?>
                                                    <a href="javascript:void(0);"
                                                       class="item-delete tpl-table-black-operation-del"
                                                       data-id="<?= $item['coupon_id'] ?>">
                                                        <i class="am-icon-trash"></i> 删除
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="am-text-center">暂无记录</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="am-u-lg-12 am-cf">
                        <div class="am-fr"><?= $list->render() ?> </div>
                        <div class="am-fr pagination-total am-margin-right">
                            <div class="am-vertical-align-middle">总记录：<?= $list->total() ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {

        // 删除元素
        var url = "<?= url('market.coupon/delete') ?>";
        $('.item-delete').delete('coupon_id', url);

    });
</script>

