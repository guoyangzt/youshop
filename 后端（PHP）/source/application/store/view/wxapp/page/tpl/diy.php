<!-- diy元素: page -->
<script id="tpl_diy_page" type="text/template">
    <div id="diy-{{ id }}" class="phone-top optional __no-move" data-itemid="page"
         style="background: {{ style.titleBackgroundColor }} url('assets/store/img/diy/phone-top-{{ style.titleTextColor }}.png') no-repeat center / contain;">
        <h4 style="color: {{ style.titleTextColor }};">{{ params.title }}</h4>
    </div>
</script>

<!-- diy元素: 搜索栏 -->
<script id="tpl_diy_search" type="text/template">
    <div class="drag optional" id="diy-{{ id }}" data-itemid="{{ id }}">
        <div class="diy-search">
            <div class="inner left {{ style.searchStyle }}" style="background: {{ style.inputBackground }};">
                <div class="search-input" style="text-align: {{ style.textAlign }}; color: {{ style.inputColor }};">
                    <i class="search-icon iconfont icon-ss-search"></i>
                    <span>{{ params.placeholder }}</span>
                </div>
            </div>
        </div>
        <div class="btn-edit-del">
            <div class="btn-del">删除</div>
        </div>
    </div>
</script>

<!-- diy元素: banner -->
<script id="tpl_diy_banner" type="text/template">
    <div class="drag optional" id="diy-{{ id }}" data-itemid="{{ id }}">
        <div class="diy-banner">
            {{each data}}
            <img src="{{ $value.imgUrl }}">
            {{/each}}
            <div class="dots center {{ style.btnShape }}">
                {{each data}}
                <span style="background: {{ style.btnColor }};"></span>
                {{/each}}
            </div>
        </div>
        <div class="btn-edit-del">
            <div class="btn-del">删除</div>
        </div>
    </div>
</script>

<!-- diy元素: 单图组 -->
<script id="tpl_diy_imageSingle" type="text/template">
    <div class="drag optional" id="diy-{{ id }}" data-itemid="{{ id }}">
        <div class="diy-imageSingle"
             style="padding-bottom: {{ style.paddingTop }}px; background: {{ style.background }};">
            {{each data}}
            <div class="item-image" style="padding: {{ style.paddingTop }}px {{ style.paddingLeft }}px 0;">
                <img src="{{ $value.imgUrl }}">
            </div>
            {{/each}}
        </div>
        <div class="btn-edit-del">
            <div class="btn-del">删除</div>
        </div>
    </div>
</script>

<!-- diy元素: 导航组 -->
<script id="tpl_diy_navBar" type="text/template">
    <div class="drag optional" id="diy-{{ id }}" data-itemid="{{ id }}">
        <div class="diy-navBar" style="background: {{ style.background }};">
            <ul class="am-avg-sm-{{ style.rowsNum }}">
                {{each data}}
                <li class="">
                    <div class="item-image">
                        <img src="{{ $value.imgUrl }}">
                    </div>
                    <p class="item-text am-text-truncate" style="color: {{ $value.color }};">{{ $value.text }}</p>
                </li>
                {{/each}}
            </ul>
        </div>
        <div class="btn-edit-del">
            <div class="btn-del">删除</div>
        </div>
    </div>
</script>

<!-- diy元素: 辅助空白 -->
<script id="tpl_diy_blank" type="text/template">
    <div class="drag optional" id="diy-{{ id }}" data-itemid="{{ id }}">
        <div class="diy-blank" style="height: {{ style.height }}px; background: {{ style.background }};">
        </div>
        <div class="btn-edit-del">
            <div class="btn-del">删除</div>
        </div>
    </div>
</script>

<!-- diy元素: 辅助线 -->
<script id="tpl_diy_guide" type="text/template">
    <div class="drag optional" id="diy-{{ id }}" data-itemid="{{ id }}">
        <div class="diy-guide" style="padding: {{ style.paddingTop }}px 0; background: {{ style.background }};">
            <p class="line" style="border-top: {{ style.lineHeight }}px {{ style.lineStyle }} {{ style.lineColor }};">
            </p>
        </div>
        <div class="btn-edit-del">
            <div class="btn-del">删除</div>
        </div>
    </div>
</script>

<!-- diy元素: 视频组 -->
<script id="tpl_diy_video" type="text/template">
    <div class="drag optional" id="diy-{{ id }}" data-itemid="{{ id }}">
        <div class="diy-video" style="padding: {{ style.paddingTop }}px 0;">
            <video style="height: {{ style.height }}px;" src="{{ params.videoUrl }}" poster="{{ params.poster }}"
                   controls>
                您的浏览器不支持 video 标签
            </video>
        </div>
        <div class="btn-edit-del">
            <div class="btn-del">删除</div>
        </div>
    </div>
</script>

<!-- diy元素: 图片橱窗 -->
<script id="tpl_diy_window" type="text/template">
    <div class="drag optional __z10" id="diy-{{ id }}" data-itemid="{{ id }}">
        <div class="diy-window"
             style="background: {{ style.background }}; padding: {{ style.paddingTop }}px {{ style.paddingLeft }}px;">
            {{ if style.layout > -1 }}
            <ul class="data-list am-avg-sm-{{ style.layout }}">
                {{ each data }}
                <li style="padding: {{ style.paddingTop }}px {{ style.paddingLeft }}px;">
                    <div class="item-image">
                        <img src="{{ $value.imgUrl }}">
                    </div>
                </li>
                {{ /each }}
            </ul>
            {{ else }}
            {{ set keys = objectKeys(data) }}
            <div class="display">
                <div class="display-left" style="padding: {{ style.paddingTop }}px {{ style.paddingLeft }}px;">
                    <img src="{{ data[keys[0]].imgUrl }}">
                </div>
                {{ if dataNum == 2 }}
                <div class="display-right" style="padding: {{ style.paddingTop }}px {{ style.paddingLeft }}px;">
                    <img src="{{ data[keys[1]].imgUrl }}">
                </div>
                {{ /if }}
                {{ if dataNum == 3 }}
                <div class="display-right">
                    <div class="display-right1" style="padding: {{ style.paddingTop }}px {{ style.paddingLeft }}px;">
                        <img src="{{ data[keys[1]].imgUrl }}">
                    </div>
                    <div class="display-right2" style="padding: {{ style.paddingTop }}px {{ style.paddingLeft }}px;">
                        <img src="{{ data[keys[2]].imgUrl }}">
                    </div>
                </div>
                {{ /if }}
                {{ if dataNum == 4 }}
                <div class="display-right">
                    <div class="display-right1" style="padding: {{ style.paddingTop }}px {{ style.paddingLeft }}px;">
                        <img src="{{ data[keys[1]].imgUrl }}">
                    </div>
                    <div class="display-right2">
                        <div class="left" style="padding: {{ style.paddingTop }}px {{ style.paddingLeft }}px;">
                            <img src="{{ data[keys[2]].imgUrl }}">
                        </div>
                        <div class="right" style="padding: {{ style.paddingTop }}px {{ style.paddingLeft }}px;">
                            <img src="{{ data[keys[3]].imgUrl }}">
                        </div>
                    </div>
                </div>
                {{ /if }}
            </div>
            {{ /if }}
        </div>
        <div class="btn-edit-del">
            <div class="btn-del">删除</div>
        </div>
    </div>
</script>

<!-- diy元素: 商品组 -->
<script id="tpl_diy_goods" type="text/template">
    <div class="drag optional" id="diy-{{ id }}" data-itemid="{{ id }}">
        <div class="diy-goods" style="background: {{ style.background }};">
            <ul class="goods-list display__{{ style.display }} column__{{ style.column }} am-cf">
                {{ each params.source === 'choice' ? data : defaultData }}
                <li class="goods-item">
                    <div class="goods-image">
                        <img src="{{ $value.image }}">
                    </div>
                    <div class="detail">
                        {{ if style.show.goodsName === '1' }}
                        <p class="goods-name">
                            {{ $value.goods_name }}
                        </p>
                        {{ /if }}
                        {{ if style.show.goodsPrice === '1' }}
                        <p class="goods-price x-color-red">
                            ￥{{ $value.goods_price }}
                        </p>
                        {{ /if }}
                    </div>
                </li>
                {{ /each }}
            </ul>
        </div>
        <div class="btn-edit-del">
            <div class="btn-del">删除</div>
        </div>
    </div>
</script>

<!-- diy元素: 拼团商品组 -->
<script id="tpl_diy_sharingGoods" type="text/template">
    <div class="drag optional" id="diy-{{ id }}" data-itemid="{{ id }}">
        <div class="diy-sharingGoods" style="background: {{ style.background }};">
            <ul class="goods-list am-cf">
                {{ each params.source === 'choice' ? data : defaultData }}
                <li class="goods-item">
                    <div class="goods-image">
                        <img src="{{ $value.image }}">
                    </div>
                    <div class="detail">
                        {{ if style.show.goodsName === '1' }}
                        <p class="goods-name">
                            {{ $value.goods_name }}
                        </p>
                        {{ /if }}
                        <div class="goods_desc">
                            <p class="goods_introduction">此款商品美观大方 性价比较高 不容错过</p>
                            <div class="goods_situation">
                                <p class="iconfont icon-pintuan_huaban"></p>
                                <p class="people">3人团</p>
                                <p class="cl-9">已有356人进行拼团</p>
                            </div>
                            {{ if style.show.goodsPrice === '1' }}
                            <div class="goods_footer">
                                <p class="price_x">￥33</p>
                                <del class="price_y">¥99</del>
                            </div>
                            {{ /if }}
                        </div>
                        <a href="javascript:void(0);" class="goods_button">
                            去拼团
                        </a>
                    </div>
                </li>
                {{ /each }}
            </ul>
        </div>
        <div class="btn-edit-del">
            <div class="btn-del">删除</div>
        </div>
    </div>
</script>

<!-- diy元素: 优惠券组 -->
<script id="tpl_diy_coupon" type="text/template">
    <div class="drag optional __z10" id="diy-{{ id }}" data-itemid="{{ id }}">
        <div class="diy-coupon dis-flex flex-x-around"
             style="background: {{ style.background }}; padding: {{ style.paddingTop }}px 0;">
            {{each data}}
            <div class="coupon-wrapper">
                <div class="coupon-item">
                    <i class="before" style="background: {{ style.background }};"></i>
                    <div class="left-content color__{{ $value.color }} dis-flex flex-dir-column flex-x-center flex-y-center">
                        <div class="content-top">
                            <span class="unit">￥</span>
                            <span class="price">{{ $value.reduce_price }}</span>
                        </div>
                        <div class="content-bottom">
                            <span>满{{ $value.min_price }}元可用</span>
                        </div>
                    </div>
                    <div class="right-receive dis-flex flex-dir-column flex-x-center flex-y-center">
                        <span>立即</span>
                        <span>领取</span>
                    </div>
                </div>
            </div>
            {{/each}}
        </div>
        <div class="btn-edit-del">
            <div class="btn-del">删除</div>
        </div>
    </div>
</script>

<!-- diy元素: 公告组 -->
<script id="tpl_diy_notice" type="text/template">
    <div class="drag optional" id="diy-{{ id }}" data-itemid="{{ id }}">
        <div class="diy-notice dis-flex"
             style="background: {{ style.background }}; padding: {{ style.paddingTop }}px 10px;">
            <div class="notice__icon">
                <img src="{{ params.icon }}">
            </div>
            <div class="notice__text flex-box am-text-truncate">
                <span style="color: {{ style.textColor }};">{{ params.text }}</span>
            </div>
        </div>
        <div class="btn-edit-del">
            <div class="btn-del">删除</div>
        </div>
    </div>
</script>

<!-- diy元素: 富文本 -->
<script id="tpl_diy_richText" type="text/template">
    <div class="drag optional" id="diy-{{ id }}" data-itemid="{{ id }}">
        <div class="diy-richText"
             style="background: {{ style.background }}; padding: {{ style.paddingTop }}px {{ style.paddingLeft }}px;">
            {{ params.content }}
        </div>
        <div class="btn-edit-del">
            <div class="btn-del">删除</div>
        </div>
    </div>
</script>