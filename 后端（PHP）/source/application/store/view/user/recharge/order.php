<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">余额充值记录</div>
                </div>
                <div class="widget-body am-fr">
                    <!-- 工具栏 -->
                    <div class="page_toolbar am-margin-bottom-xl am-cf">
                        <form id="form-search" class="toolbar-form" action="">
                            <input type="hidden" name="s" value="/<?= $request->pathinfo() ?>">
                            <div class="am fr">
                                <div class="am-form-group am-fl">
                                    <?php $rechargeType = $request->get('recharge_type'); ?>
                                    <select name="recharge_type"
                                            data-am-selected="{btnSize: 'sm', placeholder: '充值方式'}">
                                        <option value=""></option>
                                        <option value="-1"
                                            <?= $rechargeType === '-1' ? 'selected' : '' ?>>全部
                                        </option>
                                        <?php foreach ($attributes['rechargeType'] as $attr): ?>
                                            <option value="<?= $attr['value'] ?>"
                                                <?= $rechargeType === (string)$attr['value'] ? 'selected' : '' ?>>
                                                <?= $attr['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="am-form-group am-fl">
                                    <?php $payStatus = $request->get('pay_status'); ?>
                                    <select name="pay_status"
                                            data-am-selected="{btnSize: 'sm', placeholder: '付款状态'}">
                                        <option value=""></option>
                                        <option value="-1"
                                            <?= $payStatus === '-1' ? 'selected' : '' ?>>全部
                                        </option>
                                        <?php foreach ($attributes['pay_status'] as $attr): ?>
                                            <option value="<?= $attr['value'] ?>"
                                                <?= $payStatus === (string)$attr['value'] ? 'selected' : '' ?>>
                                                <?= $attr['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="am-form-group tpl-form-border-form am-fl">
                                    <input type="text" name="start_time"
                                           class="am-form-field"
                                           value="<?= $request->get('start_time') ?>" placeholder="请选择起始日期"
                                           data-am-datepicker>
                                </div>
                                <div class="am-form-group tpl-form-border-form am-fl">
                                    <input type="text" name="end_time"
                                           class="am-form-field"
                                           value="<?= $request->get('end_time') ?>" placeholder="请选择截止日期"
                                           data-am-datepicker>
                                </div>
                                <div class="am-form-group am-fl">
                                    <div class="am-input-group am-input-group-sm tpl-form-border-form">
                                        <input type="text" class="am-form-field" name="search" placeholder="请输入用户昵称/订单号"
                                               value="<?= $request->get('search') ?>">
                                        <div class="am-input-group-btn">
                                            <button class="am-btn am-btn-default am-icon-search" type="submit"></button>
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
                                <th>订单ID</th>
                                <th>微信头像</th>
                                <th>微信昵称</th>
                                <th>订单号</th>
                                <th>充值方式</th>
                                <th>套餐名称</th>
                                <th>支付金额</th>
                                <th>赠送金额</th>
                                <th>支付状态</th>
                                <th>付款时间</th>
                                <th>创建时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['order_id'] ?></td>
                                    <td class="am-text-middle">
                                        <a href="<?= $item['user']['avatarUrl'] ?>" title="点击查看大图" target="_blank">
                                            <img src="<?= $item['user']['avatarUrl'] ?>" width="72" height="72" alt="">
                                        </a>
                                    </td>
                                    <td class="am-text-middle">
                                        <p class=""><?= $item['user']['nickName'] ?></p>
                                        <p class="am-link-muted">(用户ID：<?= $item['user']['user_id'] ?>)</p>
                                    </td>
                                    <td class="am-text-middle"><?= $item['order_no'] ?></td>
                                    <td class="am-text-middle">
                                        <span class="am-badge
                                        <?= $item['recharge_type']['value'] == 10 ? 'am-badge-secondary' : 'am-badge-success' ?>">
                                                <?= $item['recharge_type']['text'] ?></span>
                                    </td>
                                    <td class="am-text-middle"><?= $item['order_plan']['plan_name'] ?: '--' ?></td>
                                    <td class="am-text-middle"><?= $item['pay_price'] ?></td>
                                    <td class="am-text-middle"><?= $item['gift_money'] ?></td>
                                    <td class="am-text-middle">
                                        <span class="am-badge
                                        <?= $item['pay_status']['value'] == 20 ? 'am-badge-success' : '' ?>">
                                                <?= $item['pay_status']['text'] ?></span>
                                    </td>
                                    <td class="am-text-middle"><?= $item['pay_time']['text'] ?: '--' ?></td>
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
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

        // 删除元素
        var url = "<?= url('market.coupon/delete') ?>";
        $('.item-delete').delete('coupon_id', url);

    });
</script>

