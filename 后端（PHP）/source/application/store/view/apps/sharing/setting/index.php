<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">拼团设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 拼团失败自动退款 </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="basic[auto_refund]" value="1"
                                               data-am-ucheck
                                            <?= $values['auto_refund'] == 1 ? 'checked' : '' ?>
                                               required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="basic[auto_refund]" value="0"
                                               data-am-ucheck
                                            <?= $values['auto_refund'] == 0 ? 'checked' : '' ?>>
                                        关闭
                                    </label>
                                    <div class="help-block am-margin-top-sm">
                                        <small>注：如果不开启自动退款，则需要在 [订单管理] 处手动退款</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group am-padding-top-lg">
                                <label class="am-u-sm-3 am-form-label form-require"> 是否允许使用优惠券 </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="basic[is_coupon]" value="1"
                                               data-am-ucheck
                                            <?= $values['is_coupon'] == 1 ? 'checked' : '' ?>
                                               required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="basic[is_coupon]" value="0"
                                               data-am-ucheck
                                            <?= $values['is_coupon'] == 0 ? 'checked' : '' ?>>
                                        关闭
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group am-padding-top-lg">
                                <label class="am-u-sm-3 am-form-label form-require"> 是否开启分销 </label>
                                <div class="am-u-sm-9">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="basic[is_dealer]" value="1"
                                               data-am-ucheck
                                            <?= $values['is_dealer'] == 1 ? 'checked' : '' ?>
                                               required>
                                        开启
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="basic[is_dealer]" value="0"
                                               data-am-ucheck
                                            <?= $values['is_dealer'] == 0 ? 'checked' : '' ?>>
                                        关闭
                                    </label>
                                    <div class="help-block am-margin-top-sm">
                                        <small>注：如需使用分销功能必须在 [分销中心 - 分销设置] 中开启</small>
                                    </div>
                                </div>
                            </div>

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">规则描述</div>
                            </div>
                            <div class="am-form-group am-padding-top-lg">
                                <label class="am-u-sm-3 am-form-label form-require"> 规则简述 </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input" name="basic[rule_brief]"
                                           value="<?= $values['rule_brief'] ?>">
                                </div>
                            </div>
                            <div class="am-form-group am-padding-top">
                                <label class="am-u-sm-3 am-form-label form-require"> 规则详述 </label>
                                <div class="am-u-sm-9">
                                    <textarea class="am-field-valid" rows="7" placeholder="请输入分销商申请协议"
                                              name="basic[rule_detail]"><?= $values['rule_detail'] ?></textarea>
                                </div>
                            </div>

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">模板消息</div>
                            </div>
                            <div class="am-form-group am-padding-top-lg">
                                <label class="am-u-sm-3 am-form-label form-require"> 拼团拼单状态通知 </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input" name="basic[tpl_msg_id]"
                                           placeholder="请填写模板消息ID" value="<?= $values['tpl_msg_id'] ?>">
                                    <small>模板编号AT1814，关键词 (订单编号、商品名称、拼团价格、拼团人数、拼团时间、拼团结果)</small>
                                    <small class="am-margin-left-xs">
                                        <a href="<?= url('store/setting.help/tplmsg') ?>" target="_blank">如何获取模板消息ID？</a>
                                    </small>
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
