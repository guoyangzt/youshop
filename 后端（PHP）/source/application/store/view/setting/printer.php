<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">小票打印设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 是否开启小票打印 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="printer[is_open]" value="1" data-am-ucheck
                                            <?= $values['is_open'] ? 'checked' : '' ?>> 开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="printer[is_open]" value="0" data-am-ucheck
                                            <?= $values['is_open'] ? '' : 'checked' ?>> 关闭
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 选择订单打印机 </label>
                                <div class="am-u-sm-9 am-u-end am-padding-xs">
                                    <select name="printer[printer_id]"
                                            data-am-selected="{btnSize: 'sm', placeholder:'请选择打印机', maxHeight: 400}">
                                        <option value=""></option>
                                        <?php if (isset($printerList)): foreach ($printerList as $printer): ?>
                                            <option value="<?= $printer['printer_id'] ?>"
                                                <?= $values['printer_id'] == $printer['printer_id'] ? 'selected' : '' ?>>
                                                <?= $printer['printer_name'] ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 订单打印方式 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-checkbox-inline">
                                        <input type="checkbox" name="printer[order_status][]" value="20" data-am-ucheck
                                            <?= in_array('20', $values['order_status']) ? 'checked' : '' ?>>
                                        订单付款时
                                    </label>
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
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });
</script>
