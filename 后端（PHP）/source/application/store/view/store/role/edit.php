<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css"/>
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">编辑角色</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">角色名称 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="role[role_name]"
                                           value="<?= $model['role_name'] ?>" placeholder="请输入角色名称" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">上级角色 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <select name="role[parent_id]" data-am-selected="{btnSize: 'sm'}">
                                        <option value="0"> 顶级角色</option>
                                        <?php if (isset($roleList)): foreach ($roleList as $role): ?>
                                            <option value="<?= $role['role_id'] ?>"
                                                <?= $model['parent_id'] == $role['role_id'] ? 'selected' : '' ?>
                                                <?= $model['role_id'] == $role['role_id'] ? 'disabled' : '' ?>>
                                                <?= $role['role_name_h1'] ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">权限列表 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div id="jstree"></div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">排序 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="number" min="0" class="tpl-form-input" name="role[sort]"
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
<script src="assets/common/js/jstree.min.js"></script>
<script>
    $(function () {

        var $jstree = $('#jstree');
        $jstree.jstree({
            icon: false,
            plugins: ['checkbox'],
            core: {
                themes: {icons: false},
                checkbox: {
                    keep_selected_style: false
                },
                data: <?= $accessList ?>
            }
        });

        // 读取选中的条目
        $.jstree.core.prototype.get_all_checked = function (full) {
            var obj = this.get_selected(), i, j;
            for (i = 0, j = obj.length; i < j; i++) {
                obj = obj.concat(this.get_node(obj[i]).parents);
            }
            obj = $.grep(obj, function (v) {
                return v !== '#';
            });
            obj = obj.filter(function (itm, i, a) {
                return i === a.indexOf(itm);
            });
            return full ? $.map(obj, $.proxy(function (i) {
                return this.get_node(i);
            }, this)) : obj;
        };

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm({
            buildData: function () {
                return {
                    role: {
                        access: $jstree.jstree('get_all_checked')
                    }
                }
            }
        });

    });
</script>
