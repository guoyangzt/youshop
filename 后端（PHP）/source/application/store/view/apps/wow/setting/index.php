<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl"> 好物圈设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 同步商品收藏 </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="basic[is_shopping]" value="1"
                                            <?= $values['is_shopping'] == 1 ? 'checked' : '' ?>
                                               data-am-ucheck required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="basic[is_shopping]" value="0"
                                               data-am-ucheck <?= $values['is_shopping'] == 0 ? 'checked' : '' ?>>
                                        关闭
                                    </label>
                                    <div class="help-block am-margin-top-xs">
                                        <small>注：用户将商品加入购物车时，自动同步到微信好物圈商品收藏</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 同步订单信息 </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="basic[is_order]" value="1"
                                            <?= $values['is_order'] == 1 ? 'checked' : '' ?>
                                               data-am-ucheck required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="basic[is_order]" value="0"
                                               data-am-ucheck <?= $values['is_order'] == 0 ? 'checked' : '' ?>>
                                        关闭
                                    </label>
                                    <div class="help-block am-margin-top-xs">
                                        <small>注：用户下单(付款)后，自动同步到微信好物圈订单信息。</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                    <button type="submit" class="j-submit am-btn am-btn-sm am-btn-secondary">提交
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
