<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">添加会员等级</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 等级名称 </label>
                                <div class="am-u-sm-9 am-u-md-6 am-u-lg-5 am-u-end">
                                    <input type="text" class="tpl-form-input" name="grade[name]"
                                           value="" placeholder="请输入等级名称" required>
                                    <small>例如：大众会员、黄金会员、铂金会员、钻石会员</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 等级权重 </label>
                                <div class="am-u-sm-9 am-u-md-6 am-u-lg-5 am-u-end">
                                    <div class="x-region-select">
                                        <select name="grade[weight]" style="width: 20rem;" required>
                                            <option value="">请选择等级权重</option>
                                            <?php for ($i = 1; $i <= 50; $i++): ?>
                                                <option value="<?= $i ?>"><?= $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="help-block">
                                        <small>会员等级的权重，数字越大 等级越高</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 升级条件 </label>
                                <div class="am-u-sm-9 am-u-md-6 am-u-lg-5 am-u-end">
                                    <div class="am-input-group">
                                        <span class="am-input-group-label am-input-group-label__left">实际消费金额满</span>
                                        <input type="number" name="grade[upgrade][expend_money]"
                                               class="am-form-field" min="0.01" required>
                                        <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">元</span>
                                    </div>
                                    <div class="help-block">
                                        <small>用户的实际消费金额满n元后，自动升级</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 等级权益 </label>
                                <div class="am-u-sm-9 am-u-md-6 am-u-lg-5 am-u-end">
                                    <div class="am-input-group">
                                        <span class="am-input-group-label am-input-group-label__left">折扣率</span>
                                        <input type="number" name="grade[equity][discount]" class="am-form-field"
                                               max="10" min="0" required>
                                        <span class="widget-dealer__unit am-input-group-label am-input-group-label__right">折</span>
                                    </div>
                                    <div class="help-block">
                                        <small>折扣率范围0-10，9.5代表9.5折，0代表不折扣</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 等级状态 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="grade[status]" value="1" data-am-ucheck
                                               checked>
                                        启用
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="grade[status]" value="0" data-am-ucheck>
                                        禁用
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                    <button type="submit" class="j-submit am-btn am-btn-secondary"> 提交
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
