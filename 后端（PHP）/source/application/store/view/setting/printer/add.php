<?php

use app\common\enum\PrinterType as PrinterTypeEnum;

?>
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">新增小票打印机</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 打印机名称 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="printer[printer_name]" value=""
                                           required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 打印机类型 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <select name="printer[printer_type]"
                                            data-am-selected="{btnSize: 'sm', placeholder: '请选择', maxHeight: 400}">
                                        <option value=""></option>
                                        <?php if (isset($printerType)): foreach ($printerType as $key => $name): ?>
                                            <option value="<?= $key ?>"><?= $name ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                    <div class="help-block">
                                        <small>目前支持 飞鹅打印机、365云打印</small>
                                    </div>
                                </div>
                            </div>

                            <!-- 打印机配置：飞鹅打印机 -->
                            <div id="<?= PrinterTypeEnum::FEI_E_YUN ?>" class="form-tab-group">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> USER </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="text" class="tpl-form-input"
                                               name="printer[<?= PrinterTypeEnum::FEI_E_YUN ?>][USER]">
                                        <small>飞鹅云后台注册用户名</small>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> UKEY </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="text" class="tpl-form-input"
                                               name="printer[<?= PrinterTypeEnum::FEI_E_YUN ?>][UKEY]">
                                        <small>飞鹅云后台登录生成的UKEY</small>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 打印机编号 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="text" class="tpl-form-input"
                                               name="printer[<?= PrinterTypeEnum::FEI_E_YUN ?>][SN]">
                                        <small>打印机编号为9位数字，查看飞鹅打印机底部贴纸上面的编号</small>
                                    </div>
                                </div>
                            </div>

                            <!-- 打印机配置：365云打印 -->
                            <div id="<?= PrinterTypeEnum::PRINT_CENTER ?>" class="form-tab-group">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 打印机编号 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="text" class="tpl-form-input"
                                               name="printer[<?= PrinterTypeEnum::PRINT_CENTER ?>][deviceNo]">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 打印机秘钥 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="text" class="tpl-form-input"
                                               name="printer[<?= PrinterTypeEnum::PRINT_CENTER ?>][key]">
                                    </div>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 打印联数 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="number" class="tpl-form-input" name="printer[print_times]" value="1"
                                           required>
                                    <small>同一订单，打印的次数</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">排序 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="number" min="0" class="tpl-form-input" name="printer[sort]" value="100"
                                           required>
                                    <small>数字越小越靠前</small>
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

        // 切换打印机类型
        $("select[name='printer[printer_type]']").on('change', function (e) {
            $('.form-tab-group').removeClass('active');
            $('#' + e.currentTarget.value).addClass('active');
        });

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });
</script>
