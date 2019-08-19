<!--编辑器: page-->
<script id="tpl_editor_page" type="text/template">
    <div class="editor-title"><span>{{ name }}</span></div>
    <form class="am-form tpl-form-line-form" data-itemid="{{ id }}">
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">页面名称 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="tpl-form-input" type="text" name="name"
                       data-bind="params.name" value="{{ params.name }}">
                <div class="help-block am-margin-top-xs">
                    <small>页面名称仅用于后台查找</small>
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">页面标题 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="tpl-form-input" type="text" name="title"
                       data-bind="params.title" value="{{ params.title }}">
                <div class="help-block am-margin-top-xs">
                    <small>小程序端顶部显示的标题</small>
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">分享标题 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="tpl-form-input" type="text" name="share_title"
                       data-bind="params.share_title" value="{{ params.share_title }}">
                <div class="help-block am-margin-top-xs">
                    <small>小程序端转发时显示的标题</small>
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">标题栏文字 </label>
            <div class="am-u-sm-8 am-u-end">
                <label class="am-radio-inline">
                    <input data-bind="style.titleTextColor" type="radio" name="titleTextColor"
                           value="black" {{ style.titleTextColor=== 'black' ? 'checked' : '' }}> 黑色
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.titleTextColor" type="radio" name="titleTextColor"
                           value="white" {{ style.titleTextColor=== 'white' ? 'checked' : '' }}> 白色
                </label>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">标题栏背景 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="" type="color" name="titleBackgroundColor"
                       data-bind="style.titleBackgroundColor" value="{{ style.titleBackgroundColor }}">
                <button type="button" class="btn-resetColor am-btn am-btn-xs" data-color="#ffffff">
                    重置
                </button>
            </div>
        </div>
    </form>
</script>

<!--编辑器: 搜索-->
<script id="tpl_editor_search" type="text/template">
    {{ if name }}
    <div class="editor-title"><span>{{ name }}</span></div>
    {{ /if }}
    <form class="am-form tpl-form-line-form" data-itemid="{{ id }}">
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">提示文字 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="tpl-form-input" type="text" name="searchStyle"
                       data-bind="params.placeholder" value="{{ params.placeholder }}">
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">搜索框样式 </label>
            <div class="am-u-sm-9 am-u-end">
                <label class="am-radio-inline">
                    <input data-bind="style.searchStyle" type="radio" name="searchStyle"
                           value="" {{ style.searchStyle=== '' ? 'checked' : '' }}> 方形
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.searchStyle" type="radio" name="searchStyle"
                           value="radius" {{ style.searchStyle=== 'radius' ? 'checked' : '' }}> 圆角
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.searchStyle" type="radio" name="searchStyle"
                           value="round" {{ style.searchStyle=== 'round' ? 'checked' : '' }}> 圆弧
                </label>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">文字对齐 </label>
            <div class="am-u-sm-9 am-u-end">
                <label class="am-radio-inline">
                    <input data-bind="style.textAlign" type="radio" name="textAlign"
                           value="left" {{ style.textAlign=== 'left' ? 'checked' : '' }}>
                    居左
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.textAlign" type="radio" name="textAlign"
                           value="center" {{ style.textAlign=== 'center' ? 'checked' : '' }}>
                    居中
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.textAlign" type="radio" name="textAlign"
                           value="right" {{ style.textAlign=== 'right' ? 'checked' : '' }}>
                    居右
                </label>
            </div>
        </div>
    </form>
</script>

<!--编辑器: banner-->
<script id="tpl_editor_banner" type="text/template">
    {{ if name }}
    <div class="editor-title"><span>{{ name }}</span></div>
    {{ /if }}
    <form class="am-form tpl-form-line-form" data-itemid="{{ id }}">
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">指示点形状 </label>
            <div class="am-u-sm-9 am-u-end">
                <label class="am-radio-inline">
                    <input data-bind="style.btnShape" type="radio" name="searchStyle"
                           value="rectangle" {{ style.btnShape=== 'rectangle' ? 'checked' : '' }}> 长方形
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.btnShape" type="radio" name="searchStyle"
                           value="square" {{ style.btnShape=== 'square' ? 'checked' : '' }}> 正方形
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.btnShape" type="radio" name="searchStyle"
                           value="round" {{ style.btnShape=== 'round' ? 'checked' : '' }}> 圆形
                </label>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">指示点颜色 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="" type="color" name="btnColor"
                       data-bind="style.btnColor" value="{{ style.btnColor }}">
                <button type="button" class="btn-resetColor am-btn am-btn-xs" data-color="#ffffff">
                    重置
                </button>
            </div>
        </div>
        <div class="form-items">
            {{ include 'tpl_editor_data_item_image' data }}
        </div>
        <div class="j-data-add form-item-add">
            <i class="fa fa-plus"></i> 添加一个
        </div>
    </form>
</script>

<!--编辑器: 单图组-->
<script id="tpl_editor_imageSingle" type="text/template">
    <div class="editor-title"><span>{{ name }}</span></div>
    <form class="am-form tpl-form-line-form" data-itemid="{{ id }}">
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">上下边距 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="tpl-form-input" type="range" name="paddingTop" data-bind="style.paddingTop"
                       value="{{ style.paddingTop }}" min="0" max="50">
                <div class="display-value">
                    <span class="value">{{ style.paddingTop }}</span>px (像素)
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">左右边距 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="tpl-form-input" type="range" name="paddingLeft" data-bind="style.paddingLeft"
                       value="{{ style.paddingTop }}" min="0" max="50">
                <div class="display-value">
                    <span class="value">{{ style.paddingLeft }}</span>px (像素)
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">背景颜色 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="" type="color" name="background"
                       data-bind="style.background" value="{{ style.background }}">
                <button type="button" class="btn-resetColor am-btn am-btn-xs" data-color="#ffffff">
                    重置
                </button>
            </div>
        </div>
        <div class="form-items">
            {{ include 'tpl_editor_data_item_image' data }}
        </div>
        <div class="j-data-add form-item-add">
            <i class="fa fa-plus"></i> 添加一个
        </div>
    </form>
</script>

<!--编辑器: 导航组-->
<script id="tpl_editor_navBar" type="text/template">
    <div class="editor-title"><span>{{ name }}</span></div>
    <form class="am-form tpl-form-line-form" data-itemid="{{ id }}">
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">背景颜色 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="" type="color" name="background"
                       data-bind="style.background" value="{{ style.background }}">
                <button type="button" class="btn-resetColor am-btn am-btn-xs" data-color="#ffffff">
                    重置
                </button>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">每行数量 </label>
            <div class="am-u-sm-8 am-u-end">
                <label class="am-radio-inline">
                    <input data-bind="style.rowsNum" type="radio" name="rowsNum"
                           value="3" {{ style.rowsNum=== '3' ? 'checked' : '' }}> 3个
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.rowsNum" type="radio" name="rowsNum"
                           value="4" {{ style.rowsNum=== '4' ? 'checked' : '' }}> 4个
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.rowsNum" type="radio" name="rowsNum"
                           value="5" {{ style.rowsNum=== '5' ? 'checked' : '' }}> 5个
                </label>
            </div>
        </div>
        <div class="form-items">
            {{ include 'tpl_editor_data_item_navBar' data }}
        </div>
        <div class="j-data-add form-item-add">
            <i class="fa fa-plus"></i> 添加一个
        </div>
    </form>
</script>

<!--编辑器: 辅助空白-->
<script id="tpl_editor_blank" type="text/template">
    <div class="editor-title"><span>{{ name }}</span></div>
    <form class="am-form tpl-form-line-form" data-itemid="{{ id }}">
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">背景颜色 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="" type="color" name="background"
                       data-bind="style.background" value="{{ style.background }}">
                <button type="button" class="btn-resetColor am-btn am-btn-xs" data-color="#ffffff">
                    重置
                </button>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">组件高度 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="tpl-form-input" type="range" name="height" data-bind="style.height"
                       value="{{ style.height }}" min="1" max="200">
                <div class="display-value">
                    <span class="value">{{ style.height }}</span>px (像素)
                </div>
            </div>
        </div>
    </form>
</script>

<!--编辑器: 辅助线-->
<script id="tpl_editor_guide" type="text/template">
    <div class="editor-title"><span>{{ name }}</span></div>
    <form class="am-form tpl-form-line-form" data-itemid="{{ id }}">
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">背景颜色 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="" type="color" name="background"
                       data-bind="style.background" value="{{ style.background }}">
                <button type="button" class="btn-resetColor am-btn am-btn-xs" data-color="#ffffff">
                    重置
                </button>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">线条样式 </label>
            <div class="am-u-sm-8 am-u-end">
                <label class="am-radio-inline">
                    <input data-bind="style.lineStyle" type="radio" name="lineStyle"
                           value="solid" {{ style.lineStyle=== 'solid' ? 'checked' : '' }}> 实线
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.lineStyle" type="radio" name="lineStyle"
                           value="dashed" {{ style.lineStyle=== 'dashed' ? 'checked' : '' }}> 虚线
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.lineStyle" type="radio" name="lineStyle"
                           value="dotted" {{ style.lineStyle=== 'dotted' ? 'checked' : '' }}> 点状
                </label>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">线条颜色 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="" type="color" name="lineColor"
                       data-bind="style.lineColor" value="{{ style.lineColor }}">
                <button type="button" class="btn-resetColor am-btn am-btn-xs" data-color="#000000">
                    重置
                </button>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">线条高度 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="tpl-form-input" type="range" name="lineHeight" data-bind="style.lineHeight"
                       value="{{ style.lineHeight }}" min="1" max="20">
                <div class="display-value">
                    <span class="value">{{ style.lineHeight }}</span>px (像素)
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">上下边距 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="tpl-form-input" type="range" name="paddingTop" data-bind="style.paddingTop"
                       value="{{ style.paddingTop }}" min="0" max="50">
                <div class="display-value">
                    <span class="value">{{ style.paddingTop }}</span>px (像素)
                </div>
            </div>
        </div>
    </form>
</script>

<!--编辑器: 视频组-->
<script id="tpl_editor_video" type="text/template">
    <div class="editor-title"><span>{{ name }}</span></div>
    <form class="am-form tpl-form-line-form" data-itemid="{{ id }}">
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">上下边距 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="tpl-form-input" type="range" name="paddingTop" data-bind="style.paddingTop"
                       value="{{ style.paddingTop }}" min="0" max="50">
                <div class="display-value">
                    <span class="value">{{ style.paddingTop }}</span>px (像素)
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">视频高度 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="tpl-form-input" type="range" name="height" data-bind="style.height"
                       value="{{ style.height }}" min="50" max="500">
                <div class="display-value">
                    <span class="value">{{ style.height }}</span>px (像素)
                </div>
                <div class="help-block am-margin-top-xs">
                    <small>滑块可用左右方向键精确调整</small>
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">视频封面 </label>
            <div class="am-u-sm-8 am-u-end">
                <div class="data-image j-selectImg">
                    <img src="{{ params.poster }}" alt="">
                    <input type="hidden" name="poster" data-bind="params.poster" value="{{ params.poster }}">
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">视频地址 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="tpl-form-input" type="url" name="videoUrl"
                       data-bind="params.videoUrl" value="{{ params.videoUrl }}">
            </div>
        </div>
    </form>
</script>

<!--编辑器: 公告组-->
<script id="tpl_editor_notice" type="text/template">
    <div class="editor-title"><span>{{ name }}</span></div>
    <form class="am-form tpl-form-line-form" data-itemid="{{ id }}">
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">上下边距 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="tpl-form-input" type="range" name="paddingTop" data-bind="style.paddingTop"
                       value="{{ style.paddingTop }}" min="0" max="50">
                <div class="display-value">
                    <span class="value">{{ style.paddingTop }}</span>px (像素)
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">背景颜色 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="" type="color" name="background" data-bind="style.background"
                       value="{{ style.background }}">
                <button type="button" class="btn-resetColor am-btn am-btn-xs" data-color="{{ style.background }}">
                    重置
                </button>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">文字颜色 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="" type="color" name="textColor" data-bind="style.textColor" value="{{ style.textColor }}">
                <button type="button" class="btn-resetColor am-btn am-btn-xs" data-color="{{ style.textColor }}">
                    重置
                </button>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">公告图标 </label>
            <div class="am-u-sm-8 am-u-end">
                <div class="data-image j-selectImg">
                    <img src="{{ params.icon }}" style="height: 30px;" alt="">
                    <input type="hidden" name="poster" data-bind="params.icon" value="{{ params.icon }}">
                </div>
                <div class="help-block">
                    <small>建议尺寸：32×32</small>
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">公告内容 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="tpl-form-input" type="text" name="text"
                       data-bind="params.text" value="{{ params.text }}">
            </div>
        </div>
    </form>
</script>

<!--编辑器: 富文本-->
<script id="tpl_editor_richText" type="text/template">
    <div class="editor-title"><span>{{ name }}</span></div>
    <form class="am-form tpl-form-line-form" data-itemid="{{ id }}">
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">上下边距 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="tpl-form-input" type="range" name="paddingTop" data-bind="style.paddingTop"
                       value="{{ style.paddingTop }}" min="0" max="50">
                <div class="display-value">
                    <span class="value">{{ style.paddingTop }}</span>px (像素)
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">左右边距 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="tpl-form-input" type="range" name="paddingLeft" data-bind="style.paddingLeft"
                       value="{{ style.paddingLeft }}" min="0" max="50">
                <div class="display-value">
                    <span class="value">{{ style.paddingLeft }}</span>px (像素)
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">背景颜色 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="" type="color" name="background" data-bind="style.background"
                       value="{{ style.background }}">
                <button type="button" class="btn-resetColor am-btn am-btn-xs" data-color="{{ style.background }}">
                    重置
                </button>
            </div>
        </div>
        <div class="am-form-group am-padding-top-sm">
            <!-- 加载编辑器的容器 -->
            <div id="ume-editor">{{ params.content }}</div>
            <textarea class="richtext am-hide" data-bind="params.content">{{ params.content }}</textarea>
        </div>
    </form>
</script>

<!--编辑器: 图片橱窗-->
<script id="tpl_editor_window" type="text/template">
    <div class="editor-title"><span>{{ name }}</span></div>
    <form class="am-form tpl-form-line-form" data-itemid="{{ id }}">
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">上下边距 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="tpl-form-input" type="range" name="paddingTop" data-bind="style.paddingTop"
                       value="{{ style.paddingTop }}" min="0" max="50">
                <div class="display-value">
                    <span class="value">{{ style.paddingTop }}</span>px (像素)
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">左右边距 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="tpl-form-input" type="range" name="paddingLeft" data-bind="style.paddingLeft"
                       value="{{ style.paddingLeft }}" min="0" max="50">
                <div class="display-value">
                    <span class="value">{{ style.paddingLeft }}</span>px (像素)
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">背景颜色 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="" type="color" name="background"
                       data-bind="style.background" value="{{ style.background }}">
                <button type="button" class="btn-resetColor am-btn am-btn-xs" data-color="#ffffff">
                    重置
                </button>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">布局方式 </label>
            <div class="j-switch-help am-u-sm-8 am-u-end">
                <label class="am-radio-inline">
                    <input data-bind="style.layout" type="radio" name="layout"
                           value="2" {{ style.layout=== '2' ? 'checked' : '' }}> 堆积两列
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.layout" type="radio" name="layout"
                           value="3" {{ style.layout=== '3' ? 'checked' : '' }}> 堆积三列
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.layout" type="radio" name="layout"
                           value="4" {{ style.layout=== '4' ? 'checked' : '' }}> 堆积四列
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.layout" type="radio" name="layout"
                           value="-1"
                           {{ style.layout=== '-1' ? 'checked' : '' }} > 橱窗样式
                    <small class="help am-hide">
                        最多显示四张图片，超出隐藏
                    </small>
                </label>
                <div class="help-block am-margin-top-xs" data-default="请确保所有图片的尺寸/比例相同。">
                    <small>{{ style.layout=== '-1' ? '最多显示四张图片，超出隐藏' : '请确保所有图片的尺寸/比例相同。' }}</small>
                </div>
            </div>
        </div>
        <div class="form-items">
            {{ include 'tpl_editor_data_item_image' data }}
        </div>
        <div class="j-data-add form-item-add">
            <i class="fa fa-plus"></i> 添加一个
        </div>
    </form>
</script>

<!--编辑器: 商品组-->
<script id="tpl_editor_goods" type="text/template">
    <div class="editor-title"><span>{{ name }}</span></div>
    <form class="am-form tpl-form-line-form" data-itemid="{{ id }}">
        <!--商品数据-->
        <div class="j-switch-box" data-item-class="switch-source">
            <div class="am-form-group">
                <label class="am-u-sm-4 am-form-label am-text-xs">商品来源 </label>
                <div class="am-u-sm-8 am-u-end">
                    <label class="am-radio-inline">
                        <input data-switch="__choice" data-bind="params.source" type="radio" name="source"
                               value="choice" {{ params.source=== 'choice' ? 'checked' : '' }}> 手动选择
                    </label>
                    <label class="am-radio-inline">
                        <input data-switch="__auto" data-bind="params.source" type="radio" name="source"
                               value="auto" {{ params.source=== 'auto' ? 'checked' : '' }}> 自动获取
                    </label>
                </div>
            </div>
            <!--手动选择-->
            <div class="switch-source __choice {{ params.source=== 'choice' ? '' : 'am-hide' }}">
                <div class="form-items __goods am-cf">
                    {{ include 'tpl_editor_data_item_goods' data }}
                </div>
                <div class="j-selectGoods form-item-add">
                    <i class="fa fa-plus"></i> 选择商品
                </div>
            </div>
            <!-- 自动获取 -->
            <div class="switch-source __auto {{ params.source=== 'auto' ? '' : 'am-hide' }}">
                <div class="am-form-group">
                    <label class="am-u-sm-4 am-form-label am-text-xs">商品分类 </label>
                    <div class="am-u-sm-8 am-u-end">
                        <select data-bind="params.auto.category" name="category"
                                data-am-selected="{searchBox: 1, btnSize: 'sm',  placeholder:'全部分类', maxHeight: 400}">
                            <option value=""></option>
                            <option value="-1">全部分类</option>
                            <?php if (isset($catgory)): foreach ($catgory as $first): ?>
                                <option value="<?= $first['category_id'] ?>"><?= $first['name'] ?></option>
                                <?php if (isset($first['child'])): foreach ($first['child'] as $two): ?>
                                    <option value="<?= $two['category_id'] ?>">
                                        　　<?= $two['name'] ?></option>
                                    <?php if (isset($two['child'])): foreach ($two['child'] as $three): ?>
                                        <option value="<?= $three['category_id'] ?>">
                                            　　　<?= $three['name'] ?></option>
                                    <?php endforeach; endif; ?>
                                <?php endforeach; endif; ?>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
                <div class="am-form-group">
                    <label class="am-u-sm-4 am-form-label am-text-xs">商品排序 </label>
                    <div class="am-u-sm-8 am-u-end">
                        <label class="am-radio-inline">
                            <input data-bind="params.auto.goodsSort" type="radio" name="goodsSort"
                                   value="all" {{ params.auto.goodsSort=== 'all' ? 'checked' : '' }}> 综合
                        </label>
                        <label class="am-radio-inline">
                            <input data-bind="params.auto.goodsSort" type="radio" name="goodsSort"
                                   value="sales" {{ params.auto.goodsSort=== 'sales' ? 'checked' : '' }}> 销量
                        </label>
                        <label class="am-radio-inline">
                            <input data-bind="params.auto.goodsSort" type="radio" name="goodsSort"
                                   value="price" {{ params.auto.goodsSort=== 'price' ? 'checked' : '' }}> 价格
                        </label>
                    </div>
                </div>
                <div class="am-form-group">
                    <label class="am-u-sm-4 am-form-label am-text-xs">显示数量 </label>
                    <div class="am-u-sm-8 am-u-end">
                        <input class="tpl-form-input" type="number" min="1" name="showNum"
                               data-bind="params.auto.showNum" value="{{ params.auto.showNum }}">
                    </div>
                </div>
            </div>
        </div>
        <!--分割线-->
        <hr data-am-widget="divider" style="" class="am-divider am-divider-dashed"/>
        <!--组件样式-->
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">背景颜色 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="" type="color" name="background"
                       data-bind="style.background" value="{{ style.background }}">
                <button type="button" class="btn-resetColor am-btn am-btn-xs" data-color="#f3f3f3">
                    重置
                </button>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">显示类型 </label>
            <div class="am-u-sm-8 am-u-end">
                <label class="am-radio-inline">
                    <input data-bind="style.display" type="radio" name="display"
                           value="list" {{ style.display=== 'list' ? 'checked' : '' }}> 列表平铺
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.display" type="radio" name="display"
                           value="slide" {{ style.display=== 'slide' ? 'checked' : '' }}> 横向滑动
                </label>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">分列数量 </label>
            <div class="am-u-sm-8 am-u-end">
                <label class="am-radio-inline">
                    <input data-bind="style.column" type="radio" name="column"
                           value="2" {{ style.column=== '2' ? 'checked' : '' }}> 两列
                </label>
                <label class="am-radio-inline">
                    <input data-bind="style.column" type="radio" name="column"
                           value="3" {{ style.column=== '3' ? 'checked' : '' }}> 三列
                </label>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">显示内容 </label>
            <div class="am-u-sm-8 am-u-end">
                <label class="am-checkbox-inline">
                    <input data-bind="style.show.goodsName" type="checkbox" name="goodsName"
                           value="" {{ style.show.goodsName=== '1' ? 'checked' : '' }}> 商品名称
                </label>
                <label class="am-checkbox-inline">
                    <input data-bind="style.show.goodsPrice" type="checkbox" name="goodsPrice"
                           value="1" {{ style.show.goodsPrice=== '1' ? 'checked' : '' }}> 商品价格
                </label>
            </div>
        </div>
    </form>
</script>

<!--编辑器: 拼团商品组-->
<script id="tpl_editor_sharingGoods" type="text/template">
    <div class="editor-title"><span>{{ name }}</span></div>
    <form class="am-form tpl-form-line-form" data-itemid="{{ id }}">
        <!--商品数据-->
        <div class="j-switch-box" data-item-class="switch-source">
            <div class="am-form-group">
                <label class="am-u-sm-4 am-form-label am-text-xs">商品来源 </label>
                <div class="am-u-sm-8 am-u-end">
                    <label class="am-radio-inline">
                        <input data-switch="__choice" data-bind="params.source" type="radio" name="source"
                               value="choice" {{ params.source=== 'choice' ? 'checked' : '' }}> 手动选择
                    </label>
                    <label class="am-radio-inline">
                        <input data-switch="__auto" data-bind="params.source" type="radio" name="source"
                               value="auto" {{ params.source=== 'auto' ? 'checked' : '' }}> 自动获取
                    </label>
                </div>
            </div>
            <!--手动选择-->
            <div class="switch-source __choice {{ params.source=== 'choice' ? '' : 'am-hide' }}">
                <div class="form-items __goods am-cf">
                    {{ include 'tpl_editor_data_item_goods' data }}
                </div>
                <div class="j-selectGoods form-item-add">
                    <i class="fa fa-plus"></i> 选择商品
                </div>
            </div>
            <!--自动获取-->
            <div class="switch-source __auto {{ params.source=== 'auto' ? '' : 'am-hide' }}">
                <div class="am-form-group">
                    <label class="am-u-sm-4 am-form-label am-text-xs">商品分类 </label>
                    <div class="am-u-sm-8 am-u-end">
                        <select data-bind="params.auto.category" name="category"
                                data-am-selected="{searchBox: 1, btnSize: 'sm',  placeholder:'全部分类', maxHeight: 400}">
                            <option value=""></option>
                            <option value="-1">全部分类</option>
                            <?php if (isset($sharingCatgory)): foreach ($sharingCatgory as $item): ?>
                                <option value="<?= $item['category_id'] ?>"><?= $item['name'] ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
                <div class="am-form-group">
                    <label class="am-u-sm-4 am-form-label am-text-xs">商品排序 </label>
                    <div class="am-u-sm-8 am-u-end">
                        <label class="am-radio-inline">
                            <input data-bind="params.auto.goodsSort" type="radio" name="goodsSort"
                                   value="all" {{ params.auto.goodsSort=== 'all' ? 'checked' : '' }}> 综合
                        </label>
                        <label class="am-radio-inline">
                            <input data-bind="params.auto.goodsSort" type="radio" name="goodsSort"
                                   value="sales" {{ params.auto.goodsSort=== 'sales' ? 'checked' : '' }}> 销量
                        </label>
                        <label class="am-radio-inline">
                            <input data-bind="params.auto.goodsSort" type="radio" name="goodsSort"
                                   value="price" {{ params.auto.goodsSort=== 'price' ? 'checked' : '' }}> 价格
                        </label>
                    </div>
                </div>
                <div class="am-form-group">
                    <label class="am-u-sm-4 am-form-label am-text-xs">显示数量 </label>
                    <div class="am-u-sm-8 am-u-end">
                        <input class="tpl-form-input" type="number" min="1" name="showNum"
                               data-bind="params.auto.showNum" value="{{ params.auto.showNum }}">
                    </div>
                </div>
            </div>
        </div>
        <!--分割线-->
        <hr data-am-widget="divider" style="" class="am-divider am-divider-dashed"/>
        <!--组件样式-->
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">背景颜色 </label>
            <div class="am-u-sm-8 am-u-end">
                <input class="" type="color" name="background"
                       data-bind="style.background" value="{{ style.background }}">
                <button type="button" class="btn-resetColor am-btn am-btn-xs" data-color="#f3f3f3">
                    重置
                </button>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-4 am-form-label am-text-xs">显示内容 </label>
            <div class="am-u-sm-8 am-u-end">
                <label class="am-checkbox-inline">
                    <input data-bind="style.show.goodsName" type="checkbox" name="goodsName"
                           value="" {{ style.show.goodsName=== '1' ? 'checked' : '' }}> 商品名称
                </label>
                <label class="am-checkbox-inline">
                    <input data-bind="style.show.goodsPrice" type="checkbox" name="goodsPrice"
                           value="1" {{ style.show.goodsPrice=== '1' ? 'checked' : '' }}> 商品价格
                </label>
            </div>
        </div>
    </form>
</script>

<!--编辑器: 优惠券组-->
<script id="tpl_editor_coupon" type="text/template">
    <div class="editor-title"><span>{{ name }}</span></div>
    <form class="am-form tpl-form-line-form" data-itemid="{{ id }}">
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">上下边距 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="tpl-form-input" type="range" name="paddingTop" data-bind="style.paddingTop"
                       value="{{ style.paddingTop }}" min="0" max="50">
                <div class="display-value">
                    <span class="value">{{ style.paddingTop }}</span>px (像素)
                </div>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">背景颜色 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="" type="color" name="background"
                       data-bind="style.background" value="{{ style.background }}">
                <button type="button" class="btn-resetColor am-btn am-btn-xs" data-color="#ffffff">
                    重置
                </button>
            </div>
        </div>
        <div class="am-form-group">
            <label class="am-u-sm-3 am-form-label am-text-xs">显示数量 </label>
            <div class="am-u-sm-9 am-u-end">
                <input class="tpl-form-input" type="range" name="limit" data-bind="params.limit"
                       value="{{ params.limit }}" min="1" max="15">
                <div class="display-value">
                    最多<span class="value">{{ params.limit }}</span>个
                </div>
            </div>
        </div>
    </form>
</script>

<!-- ////// -->
<!-- data-item: start -->

<!-- banner & imageSingle: data-item -->
<script id="tpl_editor_data_item_image" type="text/template">
    {{each $data}}
    <div class="form-item drag" data-key="{{ $index }}">
        <i class="iconfont icon-shanchu item-delete"></i>
        <div class="item-inner">
            <div class="am-form-group">
                <label class="am-u-sm-3 am-form-label am-text-xs">图片 </label>
                <div class="am-u-sm-8 am-u-end">
                    <div class="data-image j-selectImg">
                        <img src="{{ $value.imgUrl }}" alt="">
                        <input type="hidden" name="imgUrl" data-bind="data.{{ $index }}.imgUrl"
                               value="{{ $value.imgUrl }}">
                    </div>
                    {{ if $value.advise }}
                    <div class="help-block">
                        <small>{{ $value.advise }}</small>
                    </div>
                    {{ /if }}
                </div>
            </div>
            <div class="am-form-group">
                <label class="am-u-sm-3 am-form-label am-text-xs">链接地址 </label>
                <div class="am-u-sm-8 am-u-end">
                    <input type="text" name="linkUrl" data-bind="data.{{ $index }}.linkUrl"
                           value="{{ $value.linkUrl }}">
                </div>
            </div>
        </div>
    </div>
    {{/each}}
</script>

<!-- navBar: data-item -->
<script id="tpl_editor_data_item_navBar" type="text/template">
    {{each $data}}
    <div class="form-item drag" data-key="{{ $index }}">
        <i class="iconfont icon-shanchu item-delete"></i>
        <div class="item-inner">
            <div class="am-form-group">
                <label class="am-u-sm-3 am-form-label am-text-xs">图片 </label>
                <div class="am-u-sm-8 am-u-end">
                    <div class="data-image j-selectImg">
                        <img src="{{ $value.imgUrl }}" alt="">
                        <input type="hidden" name="imgUrl" data-bind="data.{{ $index }}.imgUrl"
                               value="{{ $value.imgUrl }}">
                    </div>
                    {{ if $value.advise }}
                    <div class="help-block">
                        <small>{{ $value.advise }}</small>
                    </div>
                    {{ /if }}
                </div>
            </div>
            <div class="am-form-group">
                <label class="am-u-sm-3 am-form-label am-text-xs">文字内容 </label>
                <div class="am-u-sm-8 am-u-end">
                    <input type="text" name="text" data-bind="data.{{ $index }}.text" value="{{ $value.text }}">
                </div>
            </div>
            <div class="am-form-group">
                <label class="am-u-sm-3 am-form-label am-text-xs">文字颜色 </label>
                <div class="am-u-sm-8 am-u-end">
                    <input type="color" name="color" data-bind="data.{{ $index }}.color" value="{{ $value.color }}">
                    <button type="button" class="btn-resetColor am-btn am-btn-xs" data-color="#666666">
                        重置
                    </button>
                </div>
            </div>
            <div class="am-form-group">
                <label class="am-u-sm-3 am-form-label am-text-xs">链接地址 </label>
                <div class="am-u-sm-8 am-u-end">
                    <input type="text" name="linkUrl" data-bind="data.{{ $index }}.linkUrl"
                           value="{{ $value.linkUrl }}">
                </div>
            </div>
        </div>
    </div>
    {{/each}}
</script>

<!-- goods: data-item -->
<script id="tpl_editor_data_item_goods" type="text/template">
    {{each $data}}
    <div class="form-item drag" data-key="{{ $index }}">
        <i class="iconfont icon-shanchu item-delete" data-no-confirm="{{ $value.is_default }}"></i>
        <div class="item-inner">
            <div class="data-image">
                <img src="{{ $value.image }}" alt="">
                <input type="hidden" name="goods_id" data-bind="data.{{ $index }}.goods_id"
                       value="{{ $value.goods_id }}">
            </div>
        </div>
    </div>
    {{/each}}
</script>

<!-- sharingGoods: data-item -->
<script id="tpl_editor_data_item_sharingGoods" type="text/template">
    {{each $data}}
    <div class="form-item drag" data-key="{{ $index }}">
        <i class="iconfont icon-shanchu item-delete" data-no-confirm="{{ $value.is_default }}"></i>
        <div class="item-inner">
            <div class="data-image">
                <img src="{{ $value.image }}" alt="">
                <input type="hidden" name="goods_id" data-bind="data.{{ $index }}.goods_id"
                       value="{{ $value.goods_id }}">
            </div>
        </div>
    </div>
    {{/each}}
</script>

<!-- ////// -->
<!-- data-item: end -->
