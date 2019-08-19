<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">编辑权限</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">权限名称 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="access[name]"
                                           value="<?= $model['name'] ?>" placeholder="请输入权限名称" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">上级权限 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <select name="access[parent_id]" data-am-selected="{searchBox: 1, btnSize: 'sm', maxHeight: 420}">
                                        <option value="0"> 顶级权限</option>
                                        <?php if (isset($accessList)): foreach ($accessList as $access): ?>
                                            <option value="<?= $access['access_id'] ?>"
                                                <?= $model['parent_id'] == $access['access_id'] ? 'selected' : '' ?>
                                                <?= $model['access_id'] == $access['access_id'] ? 'disabled' : '' ?>>
                                                <?= $access['name_h1'] ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">权限url </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="access[url]"
                                           value="<?= $model['url'] ?>" placeholder="请输入权限url" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">排序 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="number" min="0" class="tpl-form-input" name="access[sort]"
                                           value="<?= $model['sort'] ?>">
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
