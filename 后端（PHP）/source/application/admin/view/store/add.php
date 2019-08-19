<div class="row">
    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
        <div class="widget am-cf">
            <form id="my-form" class="am-form tpl-form-line-form" method="post">
                <div class="widget-body">
                    <fieldset>
                        <div class="widget-head am-cf">
                            <div class="widget-title am-fl">新增小程序商城</div>
                        </div>
                        <div class="am-form-group">
                            <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 商城名称 </label>
                            <div class="am-u-sm-9 am-u-end">
                                <input type="text" class="tpl-form-input" name="store[store_name]" value=""
                                       required>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">排序 </label>
                            <div class="am-u-sm-9 am-u-end">
                                <input type="number" min="0" class="tpl-form-input" name="store[sort]" value="100"
                                       required>
                                <small>数字越小越靠前</small>
                            </div>
                        </div>
                        <div class="am-form-group am-padding-top-sm">
                            <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 商家账户名 </label>
                            <div class="am-u-sm-9 am-u-end">
                                <input type="text" class="tpl-form-input" name="store[user_name]" value=""
                                       required>
                                <small>商家后台用户名</small>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 商家账户密码 </label>
                            <div class="am-u-sm-9 am-u-end">
                                <input type="password" class="tpl-form-input" name="store[password]" value=""
                                       required>
                                <small>商家后台用户密码</small>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 确认密码 </label>
                            <div class="am-u-sm-9 am-u-end">
                                <input type="password" class="tpl-form-input" name="store[password_confirm]" value=""
                                       required>
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
<script>
    $(function () {

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });
</script>
