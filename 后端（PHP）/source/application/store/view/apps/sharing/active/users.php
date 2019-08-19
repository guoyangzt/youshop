<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">拼单成员列表</div>
                </div>
                <div class="widget-body am-fr">
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>用户ID</th>
                                <th>微信头像</th>
                                <th>微信昵称</th>
                                <th>拼团角色</th>
                                <th>订单号</th>
                                <th>订单金额</th>
                                <th>收货人</th>
                                <th>联系方式</th>
                                <th>收货地址</th>
                                <th>下单时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['user_id'] ?></td>
                                    <td class="am-text-middle">
                                        <a href="<?= $item['user']['avatarUrl'] ?>" title="点击查看大图" target="_blank">
                                            <img src="<?= $item['user']['avatarUrl'] ?>" width="72" height="72" alt="">
                                        </a>
                                    </td>
                                    <td class="am-text-middle"><?= $item['user']['nickName'] ?></td>
                                    <td class="am-text-middle">
                                        <?php if ($item['is_creator']): ?>
                                            <span class="am-badge am-badge-warning">团长</span>
                                        <?php else: ?>
                                            <span class="am-badge am-badge-secondary">团员</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="am-text-middle"><?= $item['sharing_order']['order_no'] ?></td>
                                    <td class="am-text-middle">￥<?= $item['sharing_order']['total_price'] ?></td>
                                    <td class="am-text-middle"><?= $item['sharing_order']['address']['name'] ?></td>
                                    <td class="am-text-middle"><?= $item['sharing_order']['address']['phone'] ?></td>
                                    <td class="am-text-middle">
                                        <span><?= $item['sharing_order']['address']['region']['province'] ?></span>
                                        <span><?= $item['sharing_order']['address']['region']['city'] ?></span>
                                        <span><?= $item['sharing_order']['address']['region']['region'] ?></span>
                                        <span><?= $item['sharing_order']['address']['detail'] ?></span>
                                    </td>
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                    <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
                                            <?php if (checkPrivilege('apps.sharing.order/detail')): ?>
                                                <a class="tpl-table-black-operation-default"
                                                   href="<?= url('apps.sharing.order/detail', ['order_id' => $item['order_id']]) ?>">
                                                    <i class="iconfont icon-order-o"></i> 订单详情
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
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

    });
</script>

