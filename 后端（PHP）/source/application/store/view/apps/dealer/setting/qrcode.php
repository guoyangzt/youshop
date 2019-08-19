<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-body">
                    <div class="widget-head am-cf">
                        <div class="widget-title am-fl">分销海报设置</div>
                    </div>
                    <div class="tips am-margin-bottom">
                        <div class="pre">
                            <p> 注：可拖动头像、二维码、昵称调整位置，如修改</p>
                            <p> 注：修改后如需生效请前往 <a href="<?= url('setting.cache/clear') ?>" target="_blank">设置-清理缓存</a>，清除临时图片
                            </p>
                        </div>
                    </div>
                    <div id="app" v-cloak class="poster-pannel am-cf am-padding-bottom-xl">
                        <div class="pannel__left am-fl">
                            <div id="j-preview" ref="preview" class="poster-preview">
                                <img id="preview-backdrop" class="backdrop" :src="backdrop.src" alt="">
                                <div id="j-qrcode" ref="qrcode" class="drag pre-qrcode" v-bind:class="qrcode.style"
                                     v-bind:style="{ width: qrcode.width + 'px', height: qrcode.width + 'px', top: qrcode.top + 'px', left: qrcode.left + 'px' }">
                                    <img src="assets/store/img/dealer/qrcode.png" alt="">
                                </div>
                                <div id="j-avatar" ref="avatar" v-drag class="drag pre-avatar"
                                     v-bind:class="avatar.style"
                                     v-bind:style="{ width: avatar.width + 'px', height: avatar.width + 'px', top: avatar.top + 'px', left: avatar.left + 'px' }">
                                    <img src="assets/store/img/dealer/avatar.png" alt="">
                                </div>
                                <div id="j-nickName" ref="nickName" class="drag pre-nickName"
                                     v-bind:style="{ fontSize: nickName.fontSize + 'px', color: nickName.color, top: nickName.top + 'px', left: nickName.left + 'px' }">
                                    <span>这里是昵称</span>
                                </div>
                            </div>
                        </div>

                        <div class="pannel__right am-fl">
                            <form id="my-form" class="am-form tpl-form-line-form" method="post">

                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-lg-4 am-form-label form-require">海报背景图 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <div class="am-form-file">
                                            <div class="am-form-file">
                                                <button type="button"
                                                        class="j-image upload-file am-btn am-btn-secondary am-radius">
                                                    <i class="am-icon-cloud-upload"></i> 选择图片
                                                </button>
                                            </div>
                                            <div class="help-block">
                                                <small>尺寸：宽750像素 高大于(等于)1200像素</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-md-4 am-form-label form-require"> 头像宽度 </label>
                                    <div class="am-u-sm-9 am-u-md-8">
                                        <input type="number" min="30" class="tpl-form-input" v-model="avatar.width"
                                               required>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-md-4 am-form-label form-require"> 头像样式 </label>
                                    <div class="am-u-sm-9 am-u-md-8">
                                        <label class="am-radio-inline">
                                            <input type="radio" value="square" data-am-ucheck
                                                   v-model="avatar.style"> 正方形
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="circle" data-am-ucheck
                                                   v-model="avatar.style"> 圆形
                                        </label>
                                    </div>
                                </div>

                                <div class="am-form-group am-padding-top">
                                    <label class="am-u-sm-3 am-u-md-4 am-form-label form-require"> 昵称字体大小 </label>
                                    <div class="am-u-sm-9 am-u-md-8">
                                        <input type="number" min="12" class="tpl-form-input"
                                               v-model="nickName.fontSize" required>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-md-4 am-form-label form-require"> 昵称字体颜色 </label>
                                    <div class="am-u-sm-9 am-u-md-8">
                                        <input class="tpl-form-input" type="color" v-model="nickName.color">
                                    </div>
                                </div>

                                <div class="am-form-group am-padding-top">
                                    <label class="am-u-sm-3 am-u-md-4 am-form-label form-require"> 小程序码宽度 </label>
                                    <div class="am-u-sm-9 am-u-md-8">
                                        <input type="number" min="50" class="tpl-form-input" v-model="qrcode.width"
                                               required>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-u-md-4 am-form-label form-require"> 小程序码样式 </label>
                                    <div class="am-u-sm-9 am-u-md-8">
                                        <label class="am-radio-inline">
                                            <input type="radio" value="square" v-model="qrcode.style" data-am-ucheck
                                                   checked> 正方形
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="circle" v-model="qrcode.style" data-am-ucheck> 圆形
                                        </label>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                        <button type="submit" class="j-submit am-btn am-btn-secondary">提交
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 文件库弹窗 -->
{{include file="layouts/_template/file_library" /}}

<script src="assets/common/js/vue.min.js"></script>
<script>

    $(function () {

        var appVue = new Vue({
            el: '#app',
            data: <?= $data ?>,
            created: function () {
                /**
                 * 注册拖拽事件
                 */
                this.$nextTick(function () {
                    this.dragEvent('qrcode');
                    this.dragEvent('avatar');
                    this.dragEvent('nickName');
                });
            },
            methods: {
                /**
                 * 注册拖拽事件
                 * @param ele
                 */
                dragEvent: function (ele) {
                    var _this = this
                        , $preview = this.$refs.preview
                        , $ele = this.$refs[ele]
                        , l = 0
                        , t = 0
                        , r = $preview.offsetWidth - $ele.offsetWidth
                        , b = $preview.offsetHeight - $ele.offsetHeight;
                    $ele.onmousedown = function (ev) {
                        var sentX = ev.clientX - $ele.offsetLeft;
                        var sentY = ev.clientY - $ele.offsetTop;
                        document.onmousemove = function (ev) {
                            var slideLeft = ev.clientX - sentX;
                            var slideTop = ev.clientY - sentY;
                            slideLeft <= l && (slideLeft = l);
                            slideLeft >= r && (slideLeft = r);
                            slideTop <= t && (slideTop = t);
                            slideTop >= b && (slideTop = b);

                            _this[ele].left = slideLeft;
                            _this[ele].top = slideTop;
                        };
                        document.onmouseup = function () {
                            document.onmousemove = null;
                            document.onmouseup = null;
                        };
                        return false;
                    }
                }
            }
        });

        // 选择图片：分销中心首页
        $('.j-image').selectImages({
            multiple: false,
            done: function (data) {
                appVue.$data.backdrop.src = data[0].file_path;
            }
        });

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm({
            buildData: function () {
                return {
                    qrcode: appVue.$data
                };
            }
        });

    });

</script>
