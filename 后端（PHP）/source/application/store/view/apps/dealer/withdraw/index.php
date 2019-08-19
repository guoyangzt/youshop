<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">分销商提现申请</div>
                </div>
                <div class="widget-body am-fr">
                    <!-- 工具栏 -->
                    <div class="page_toolbar am-margin-bottom-xs am-cf">
                        <form class="toolbar-form" action="">
                            <input type="hidden" name="s" value="/<?= $request->pathinfo() ?>">
                            <input type="hidden" name="user_id" value="<?= $request->get('user_id') ?>">
                            <div class="am-u-sm-12 am-u-md-9 am-u-sm-push-3">
                                <div class="am fr">
                                    <div class="am-form-group am-fl">
                                        <select name="apply_status"
                                                data-am-selected="{btnSize: 'sm', placeholder: '审核状态'}">
                                            <option value=""></option>
                                            <option value="-1" <?= $request->get('apply_status') === '-1' ? 'selected' : '' ?>>
                                                全部
                                            </option>
                                            <option value="10" <?= $request->get('apply_status') == '10' ? 'selected' : '' ?>>
                                                待审核
                                            </option>
                                            <option value="20" <?= $request->get('apply_status') == '20' ? 'selected' : '' ?>>
                                                审核通过
                                            </option>
                                            <option value="40" <?= $request->get('apply_status') == '40' ? 'selected' : '' ?>>
                                                已打款
                                            </option>
                                            <option value="30" <?= $request->get('apply_status') == '30' ? 'selected' : '' ?>>
                                                驳回
                                            </option>
                                        </select>
                                    </div>
                                    <div class="am-form-group am-fl">
                                        <select name="pay_type"
                                                data-am-selected="{btnSize: 'sm', placeholder: '提现方式'}">
                                            <option value=""></option>
                                            <option value="-1" <?= $request->get('pay_type') == '-1' ? 'selected' : '' ?>>
                                                全部
                                            </option>
                                            <option value="20" <?= $request->get('pay_type') == '20' ? 'selected' : '' ?>>
                                                支付宝
                                            </option>
                                            <option value="30" <?= $request->get('pay_type') == '30' ? 'selected' : '' ?>>
                                                银行卡
                                            </option>
                                        </select>
                                    </div>
                                    <div class="am-form-group am-fl">
                                        <div class="am-input-group am-input-group-sm tpl-form-border-form">
                                            <input type="text" class="am-form-field" name="search"
                                                   placeholder="请输入昵称/姓名/手机号"
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
                    <div class="am-scrollable-horizontal am-u-sm-12 am-padding-bottom-lg">
                        <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>用户ID</th>
                                <th>微信头像</th>
                                <th>微信昵称</th>
                                <th>
                                    <p>姓名</p>
                                    <p>手机号</p>
                                </th>
                                <th>提现金额</th>
                                <th>提现方式</th>
                                <th>提现信息</th>
                                <th class="am-text-center">审核状态</th>
                                <th>申请时间</th>
                                <th>审核时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['user_id'] ?></td>
                                    <td class="am-text-middle">
                                        <a href="<?= $item['avatarUrl'] ?>" title="点击查看大图" target="_blank">
                                            <img src="<?= $item['avatarUrl'] ?>"
                                                 width="50" height="50" alt="">
                                        </a>
                                    </td>
                                    <td class="am-text-middle">
                                        <p><span><?= $item['nickName'] ?></span></p>
                                    </td>
                                    <td class="am-text-middle">
                                        <?php if (!empty($item['real_name']) || !empty($item['mobile'])): ?>
                                            <p><?= $item['real_name'] ?: '--' ?></p>
                                            <p><?= $item['mobile'] ?: '--' ?></p>
                                        <?php else: ?>
                                            <p>--</p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="am-text-middle">
                                        <p><span><?= $item['money'] ?></span></p>
                                    </td>
                                    <td class="am-text-middle">
                                        <p><span><?= $item['pay_type']['text'] ?></span></p>
                                    </td>
                                    <td class="am-text-middle">
                                        <?php if ($item['pay_type']['value'] == 20) : ?>
                                            <p><span><?= $item['alipay_name'] ?></span></p>
                                            <p><span><?= $item['alipay_account'] ?></span></p>
                                        <?php elseif ($item['pay_type']['value'] == 30) : ?>
                                            <p><span><?= $item['bank_name'] ?></span></p>
                                            <p><span><?= $item['bank_account'] ?></span></p>
                                            <p><span><?= $item['bank_card'] ?></span></p>
                                        <?php else : ?>
                                            <p><span>--</span></p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="am-text-middle am-text-center">
                                        <?php if ($item['apply_status'] == 10) : ?>
                                            <span class="am-badge">待审核</span>
                                        <?php elseif ($item['apply_status'] == 20) : ?>
                                            <span class="am-badge am-badge-secondary">审核通过</span>
                                        <?php elseif ($item['apply_status'] == 30) : ?>
                                            <p><span class="am-badge am-badge-warning">已驳回</span></p>
                                            <span class="f-12">
                                                <a class="j-show-reason" href="javascript:void(0);"
                                                   data-reason="<?= $item['reject_reason'] ?>">
                                                    查看原因</a>
                                            </span>
                                        <?php elseif ($item['apply_status'] == 40) : ?>
                                            <span class="am-badge am-badge-success">已打款</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                    <td class="am-text-middle"><?= $item['audit_time'] ?: '--' ?></td>
                                    <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
                                            <?php if (in_array($item['apply_status'], [10, 20])) : ?>
                                                <?php if (checkPrivilege('apps.dealer.withdraw/submit')): ?>
                                                    <a class="j-audit" data-id="<?= $item['id'] ?>"
                                                       href="javascript:void(0);">
                                                        <i class="am-icon-pencil"></i> 审核
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($item['apply_status'] == 20) : ?>
                                                    <?php if (checkPrivilege('apps.dealer.withdraw/money')): ?>
                                                        <a class="j-money tpl-table-black-operation-del"
                                                           data-id="<?= $item['id'] ?>" href="javascript:void(0);">确认打款
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if ($item['apply_status'] == 20 && $item['pay_type']['value'] == 10) : ?>
                                                    <?php if (checkPrivilege('apps.dealer.withdraw/wechat_pay')): ?>
                                                        <a class="j-wechat-pay tpl-table-black-operation-green"
                                                           data-id="<?= $item['id'] ?>" href="javascript:void(0);">微信付款
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if (in_array($item['apply_status'], [30, 40])) : ?>
                                                <span>---</span>
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
</div>

<!-- 提现审核 -->
<script id="tpl-dealer-withdraw" type="text/template">
    <div class="am-padding-top-sm">
        <form class="form-dealer-withdraw am-form tpl-form-line-form" method="post"
              action="<?= url('apps.dealer.withdraw/submit') ?>">
            <input type="hidden" name="id" value="{{ id }}">
            <div class="am-form-group">
                <label class="am-u-sm-3 am-form-label"> 审核状态 </label>
                <div class="am-u-sm-9">
                    <label class="am-radio-inline">
                        <input type="radio" name="withdraw[apply_status]" value="20" data-am-ucheck
                               checked> 审核通过
                    </label>
                    <label class="am-radio-inline">
                        <input type="radio" name="withdraw[apply_status]" value="30" data-am-ucheck> 驳回
                    </label>
                </div>
            </div>
            <div class="am-form-group">
                <label class="am-u-sm-3 am-form-label"> 驳回原因 </label>
                <div class="am-u-sm-9">
                    <input type="text" class="tpl-form-input" name="withdraw[reject_reason]" placeholder="仅在驳回时填写"
                           value="">
                </div>
            </div>
        </form>
    </div>
</script>

<script>
    $(function () {

        /**
         * 审核操作
         */
        $('.j-audit').click(function () {
            var $this = $(this);
            layer.open({
                type: 1
                , title: '提现审核'
                , area: '340px'
                , offset: 'auto'
                , anim: 1
                , closeBtn: 1
                , shade: 0.3
                , btn: ['确定', '取消']
                , content: template('tpl-dealer-withdraw', $this.data())
                , success: function (layero) {
                    // 注册radio组件
                    layero.find('input[type=radio]').uCheck();
                }
                , yes: function (index, layero) {
                    // 表单提交
                    layero.find('.form-dealer-withdraw').ajaxSubmit({
                        type: 'post',
                        dataType: 'json',
                        success: function (result) {
                            result.code === 1 ? $.show_success(result.msg, result.url)
                                : $.show_error(result.msg);
                        }
                    });
                    layer.close(index);
                }
            });
        });

        /**
         * 显示驳回原因
         */
        $('.j-show-reason').click(function () {
            var $this = $(this);
            layer.alert($this.data('reason'), {title: '驳回原因'});
        });

        /**
         * 确认打款
         */
        $('.j-money').click(function () {
            var id = $(this).data('id');
            var url = "<?= url('apps.dealer.withdraw/money') ?>";
            layer.confirm('确定已打款吗？', {title: '友情提示'}, function (index) {
                $.post(url, {id: id}, function (result) {
                    result.code === 1 ? $.show_success(result.msg, result.url)
                        : $.show_error(result.msg);
                });
                layer.close(index);
            });
        });

        /**
         * 微信付款
         */
        $('.j-wechat-pay').click(function () {
            var id = $(this).data('id');
            var url = "<?= url('apps.dealer.withdraw/wechat_pay') ?>";
            layer.confirm('该操作 将使用微信支付企业付款到零钱功能，确定打款吗？', {title: '友情提示'}, function (index) {
                $.post(url, {id: id}, function (result) {
                    result.code === 1 ? $.show_success(result.msg, result.url)
                        : $.show_error(result.msg);
                });
                layer.close(index);
            });
        });

    });
</script>

