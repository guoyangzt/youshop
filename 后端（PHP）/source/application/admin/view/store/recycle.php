<div class="row">
    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
        <div class="widget am-cf">
            <div class="widget-head am-cf">
                <div class="widget-title am-cf">回收站列表</div>
            </div>
            <div class="widget-body am-fr">
                <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                    <div class="am-form-group">
                        <div class="am-btn-toolbar">
                        </div>
                    </div>
                </div>
                <div class="am-u-sm-12">
                    <table width="100%" class="am-table am-table-compact am-table-striped tpl-table-black ">
                        <thead>
                        <tr>
                            <th>商城ID</th>
                            <th>商城名称</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                            <tr>
                                <td class="am-text-middle">
                                    <p class="item-title"><?= $item['wxapp_id'] ?></p>
                                </td>
                                <td class="am-text-middle">
                                    <p class="item-title"><?= $names[$item['wxapp_id']] ?></p>
                                </td>
                                <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                <td class="am-text-middle">
                                    <div class="tpl-table-black-operation">
                                        <a href="javascript:void(0);" class="j-move"
                                           data-id="<?= $item['wxapp_id'] ?>">
                                            <i class="am-icon-undo"></i> 还原
                                        </a>
                                        <a href="javascript:void(0);" class="j-delete tpl-table-black-operation-del"
                                           data-id="<?= $item['wxapp_id'] ?>">
                                            <i class="am-icon-trash"></i> 删除
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="4" class="am-text-center">暂无记录</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {

        // 移出回收站
        $('.j-move').delete('wxapp_id', "<?= url('store/move') ?>", '确定要还原吗？');

        // 删除小程序
        $('.j-delete').delete('wxapp_id', "<?= url('store/delete') ?>", '确定要删除吗？删除后无法恢复');
    });
</script>

