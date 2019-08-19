<?php

use app\common\service\wechat\wow\Order as WowOrderService;

?>
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf"> 订单同步记录</div>
                </div>
                <div class="widget-body am-fr">
                    <div class="tips am-margin-bottom am-u-sm-12">
                        <div class="pre">
                            <p> 注：用户下单(付款)后，自动同步到微信好物圈订单信息。</p>
                        </div>
                    </div>
                    <!-- 工具栏 -->
                    <div class="page_toolbar am-margin-bottom am-cf">
                        <form class="toolbar-form" action="">
                            <input type="hidden" name="s" value="/<?= $request->pathinfo() ?>">
                            <div class="am-u-sm-12 am-u-md-9 am-u-sm-push-3">
                                <div class="am fr">
                                    <div class="am-form-group am-fl">
                                        <div class="am-input-group am-input-group-sm tpl-form-border-form">
                                            <input type="text" class="am-form-field" name="search"
                                                   placeholder="请输入订单号/用户昵称"
                                                   value="<?= $request->get('search') ?>">
                                            <div class="am-input-group-btn">
                                                <button class="am-btn am-btn-default am-icon-search"
                                                        type="submit"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>用户头像</th>
                                <th>用户昵称</th>
                                <th>订单编号</th>
                                <th>付款金额</th>
                                <th>订单状态</th>
                                <th>最后同步时间</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['id'] ?></td>
                                    <td class="am-text-middle">
                                        <a href="<?= $item['user']['avatarUrl'] ?>" target="_blank">
                                            <img src="<?= $item['user']['avatarUrl'] ?>"
                                                 width="70" height="70" alt="用户头像">
                                        </a>
                                    </td>
                                    <td class="am-text-middle">
                                        <p class=""><?= $item['user']['nickName'] ?></p>
                                        <p class="am-link-muted">(用户id：<?= $item['user']['user_id'] ?>)</p>
                                    </td>
                                    <td class="am-text-middle">
                                        <a href="<?= url('order/detail', ['order_id' => $item['order_id']]) ?>"
                                           title="查看订单详情" target="_blank">
                                            <?= $item['order_no'] ?>
                                        </a>
                                    </td>
                                    <td class="am-text-middle"><?= $item['pay_price'] ?></td>
                                    <td class="am-text-middle">
                                        <span class="am-badge am-badge-secondary"><?= WowOrderService::status()[$item['status']] ?></span>
                                    </td>
                                    <td class="am-text-middle"><?= $item['last_time']['text'] ?></td>
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                    <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
                                            <?php if (checkPrivilege('apps.wow.order/delete')): ?>
                                                <a href="javascript:void(0);"
                                                   class="item-delete tpl-table-black-operation-default"
                                                   data-id="<?= $item['id'] ?>">
                                                    <i class="am-icon-trash"></i> 取消同步
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="9" class="am-text-center">暂无记录</td>
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
        var url = "<?= url('apps.wow.order/delete') ?>";
        $('.item-delete').delete('id', url, '取消后，用户好物圈中将不再显示该订单，确定吗？');

    });
</script>

