<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">文件列表</div>
                </div>
                <div class="widget-body am-fr">
                    <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                        <div class="am-form-group">
                        </div>
                    </div>
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>文件ID</th>
                                <th>文件名称</th>
                                <th>所属分组</th>
                                <th>存储方式</th>
                                <th>存储域名</th>
                                <th>文件大小</th>
                                <th>文件类型</th>
                                <th>文件后缀</th>
                                <th>上传时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['file_id'] ?></td>
                                    <td class="am-text-middle">
                                        <a href="<?= $item['file_path'] ?>"
                                           target="_blank"><?= $item['file_name'] ?></a>
                                    </td>
                                    <td class="am-text-middle">
                                        <?= !empty($item['upload_group']) ? $item['upload_group']['group_name'] : '--' ?>
                                    </td>
                                    <td class="am-text-middle"><?= $item['storage'] ?></td>
                                    <td class="am-text-middle"><?= $item['file_url'] ?: '--' ?></td>
                                    <td class="am-text-middle"><?= $item['file_size'] ?></td>
                                    <td class="am-text-middle"><?= $item['file_type'] ?></td>
                                    <td class="am-text-middle"><?= $item['extension'] ?></td>
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                    <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
                                            <?php if (checkPrivilege('content.files/recycle')): ?>
                                                <a href="javascript:void(0);"
                                                   class="item-delete tpl-table-black-operation-del"
                                                   data-id="<?= $item['file_id'] ?>">
                                                    <i class="am-icon-trash"></i> 移入回收站
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="10" class="am-text-center">暂无记录</td>
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
        var url = "<?= url('content.files/recovery') ?>";
        $('.item-delete').delete('file_id', url, '确定要移入回收站吗？');

    });
</script>

