<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">商品评价详情</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">用户 </label>
                                <div class="am-u-sm-9 am-u-end am-padding-top-xs">
                                    <small class="am-text-sm"><?= $model['user']['nickName'] ?></small>
                                    <small class="">(用户id：<?= $model['user']['user_id'] ?>)</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">商品图片 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <a href="<?= $model['order_goods']['image']['file_path'] ?>"
                                       title="点击查看大图" target="_blank">
                                        <img src="<?= $model['order_goods']['image']['file_path'] ?>" alt="商品图片"
                                             width="80" height="80">
                                    </a>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">商品名称 </label>
                                <div class="am-u-sm-9 am-u-end am-padding-top-xs">
                                    <small class="am-text-sm"><?= $model['order_goods']['goods_name'] ?></small>
                                </div>
                            </div>
                            <div class="am-form-group am-margin-top-xl">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">评分 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="comment[score]" value="10" data-am-ucheck
                                            <?= $model['score'] == 10 ? 'checked' : '' ?> >
                                        好评
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="comment[score]" value="20" data-am-ucheck
                                            <?= $model['score'] == 20 ? 'checked' : '' ?> >
                                        中评
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="comment[score]" value="30" data-am-ucheck
                                            <?= $model['score'] == 30 ? 'checked' : '' ?> >
                                        差评
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">评价内容 </label>
                                <div class="am-u-sm-9 am-u-end am-padding-top-xs">
                                    <textarea class="am-field-valid" rows="5" placeholder="请输入评价内容"
                                              name="comment[content]" required><?= $model['content'] ?></textarea>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">评价图片 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-form-file">
                                        <button type="button" class="upload-file am-btn am-btn-secondary am-radius">
                                            <i class="am-icon-cloud-upload"></i> 选择图片
                                        </button>
                                        <div class="uploader-list am-cf">
                                            <?php foreach ($model['image'] as $key => $item): ?>
                                                <div class="file-item">
                                                    <a href="<?= $item['file_path'] ?>" title="点击查看大图" target="_blank">
                                                        <img src="<?= $item['file_path'] ?>">
                                                    </a>
                                                    <input type="hidden" name="comment[images][]"
                                                           value="<?= $item['image_id'] ?>">
                                                    <i class="iconfont icon-shanchu file-item-delete"></i>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="help-block am-margin-top-sm">
                                        <small>最多允许6张，可拖拽调整显示顺序</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">评价排序 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="number" min="0" class="tpl-form-input" name="comment[sort]"
                                           value="<?= $model['sort'] ?>" required>
                                    <small>数字越小越靠前</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">显示状态 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="comment[status]" value="1" data-am-ucheck
                                            <?= $model['status'] == 1 ? 'checked' : '' ?> >
                                        显示
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="comment[status]" value="0" data-am-ucheck
                                            <?= $model['status'] == 0 ? 'checked' : '' ?> >
                                        隐藏
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">评论时间 </label>
                                <div class="am-u-sm-9 am-u-end am-padding-top-xs">
                                    <small class="am-text-sm"><?= $model['create_time'] ?></small>
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

<!-- 图片文件列表模板 -->
{{include file="layouts/_template/tpl_file_item" /}}

<!-- 文件库弹窗 -->
{{include file="layouts/_template/file_library" /}}

<script src="assets/common/js/ddsort.js"></script>
<script>
    $(function () {

        // 选择图片
        $('.upload-file').selectImages({
            name: 'comment[images][]'
            , multiple: true
            , limit: 6
        });

        // 图片列表拖动
        $('.uploader-list').DDSort({
            target: '.file-item',
            delay: 100, // 延时处理，默认为 50 ms，防止手抖点击 A 链接无效
            floatStyle: {
                'border': '1px solid #ccc',
                'background-color': '#fff'
            }
        });

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });
</script>
