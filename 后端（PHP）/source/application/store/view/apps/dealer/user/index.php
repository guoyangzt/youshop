<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">分销商列表</div>
                </div>
                <div class="widget-body am-fr">
                    <!-- 工具栏 -->
                    <div class="page_toolbar am-margin-bottom-xs am-cf">
                        <form class="toolbar-form" action="">
                            <input type="hidden" name="s" value="/<?= $request->pathinfo() ?>">
                            <div class="am-u-sm-12 am-u-md-9 am-u-sm-push-3">
                                <div class="am fr">
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
                    <div class="__am-scrollable-horizontal am-u-sm-12 am-padding-bottom-lg">
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
                                <th>
                                    <p>累计佣金</p>
                                    <p>可提现佣金</p>
                                </th>
                                <th>推荐人</th>
                                <th>下级用户</th>
                                <th>成为时间</th>
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
                                        <p><?= sprintf('%.2f', $item['money'] + $item['freeze_money'] + $item['total_money']) ?></p>
                                        <p><?= $item['money'] ?></p>
                                    </td>
                                    <td class="am-text-middle">
                                        <?php if ($item['referee_id'] > 0): ?>
                                            <p><?= $item['referee']['nickName'] ?></p>
                                            <p class="am-link-muted f-12">(ID：<?= $item['referee']['user_id'] ?>)</p>
                                        <?php else: ?>
                                            <p>平台</p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="am-text-middle">
                                        <p>
                                            <a href="<?= url('apps.dealer.user/fans', ['user_id' => $item['user_id'], 'level' => 1]) ?>"
                                               target="_blank">一级：<?= $item['first_num'] ?></a>
                                        </p>
                                        <?php if ($basicSetting['level'] >= 2): ?>
                                            <p>
                                                <a href="<?= url('apps.dealer.user/fans', ['user_id' => $item['user_id'], 'level' => 2]) ?>"
                                                   target="_blank">二级：<?= $item['second_num'] ?></a>
                                            </p>
                                        <?php endif; ?>
                                        <?php if ($basicSetting['level'] == 3): ?>
                                            <p>
                                                <a href="<?= url('apps.dealer.user/fans', ['user_id' => $item['user_id'], 'level' => 3]) ?>"
                                                   target="_blank">三级：<?= $item['third_num'] ?></a>
                                            </p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                    <td class="am-text-middle">
                                        <?php if (checkPrivilege([
                                            'apps.dealer.order/index',
                                            'apps.dealer.withdraw/index',
                                            'apps.dealer.user/delete',
                                            'apps.dealer.user/qrcode',
                                        ], false)): ?>
                                            <div class="operation-select am-dropdown">
                                                <button type="button"
                                                        class="am-dropdown-toggle am-btn am-btn-sm am-btn-secondary">
                                                    <span>操作</span>
                                                    <span class="am-icon-caret-down"></span>
                                                </button>
                                                <ul class="am-dropdown-content" data-id="<?= $item['user_id'] ?>">
                                                    <?php if (checkPrivilege('apps.dealer.order/index')): ?>
                                                        <li>
                                                            <a class="" target="_blank"
                                                               href="<?= url('apps.dealer.order/index', ['user_id' => $item['user_id']]) ?>">分销订单</a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (checkPrivilege('apps.dealer.withdraw/index')): ?>
                                                        <li>
                                                            <a class="" target="_blank"
                                                               href="<?= url('apps.dealer.withdraw/index', ['user_id' => $item['user_id']]) ?>">提现明细</a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (checkPrivilege('apps.dealer.user/delete')): ?>
                                                        <li>
                                                            <a data-type="delete" class=""
                                                               href="javascript:void(0);">删除分销商</a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if (checkPrivilege('apps.dealer.user/qrcode')): ?>
                                                        <li>
                                                            <a class=""
                                                               href="<?= url('apps.dealer.user/qrcode', ['dealer_id' => $item['user_id']]) ?>"
                                                               target="_blank">分销二维码</a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="9" class="am-text-center">暂无记录</td>
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
<script>
    $(function () {

        /**
         * 注册操作事件
         * @type {jQuery|HTMLElement}
         */
        var $dropdown = $('.operation-select');
        $dropdown.dropdown();
        $dropdown.on('click', 'li a', function () {
            var $this = $(this);
            var id = $this.parent().parent().data('id');
            var type = $this.data('type');
            if (type === 'delete') {
                layer.confirm('确定要删除分销商吗？', function (index) {
                    $.post("<?= url('apps.dealer.user/delete') ?>", {dealer_id: id}, function (result) {
                        result.code === 1 ? $.show_success(result.msg, result.url)
                            : $.show_error(result.msg);
                    });
                    layer.close(index);
                });
            }
            $dropdown.dropdown('close');
        });

    });
</script>

