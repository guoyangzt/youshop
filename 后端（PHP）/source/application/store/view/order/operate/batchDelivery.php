<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post" enctype="multipart/form-data">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">批量发货</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 导入发货模板 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-form-file">
                                        <button type="button" class="upload-file am-btn am-btn-secondary am-radius">
                                            <i class="am-icon-cloud-upload"></i> 选择模板
                                        </button>
                                        <input id="doc-form-file" name="iFile" type="file" multiple="multiple" required>
                                    </div>
                                    <div class="file-list am-margin-top-xs"></div>
                                    <div class="help-block am-margin-top-xs">
                                        <small><a href="<?= url('order.operate/deliveryTpl') ?>">默认模板格式下载</a>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">物流公司 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <select name="order[express_id]"
                                            data-am-selected="{btnSize: 'sm', maxHeight: 240}" required>
                                        <option value=""></option>
                                        <?php if (isset($express_list)): foreach ($express_list as $expres): ?>
                                            <option value="<?= $expres['express_id'] ?>">
                                                <?= $expres['express_name'] ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                    <div class="help-block am-margin-top-xs">
                                        <small>可在 <a href="<?= url('setting.express/index') ?>"
                                                     target="_blank">物流公司列表</a>
                                            中设置
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                    <button type="submit" class="j-submit am-btn am-btn-secondary">提交
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {

        /**
         * 文件列表
         */
        $('#doc-form-file').on('change', function () {
            var fileNames = '';
            $.each(this.files, function () {
                fileNames += '<span class="am-badge am-badge-success">' + this.name + '</span> ';
            });
            $('.file-list').html(fileNames);
        });

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });
</script>
