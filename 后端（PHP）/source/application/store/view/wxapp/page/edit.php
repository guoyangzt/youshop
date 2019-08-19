<link rel="stylesheet" href="assets/common/plugins/umeditor/themes/default/css/umeditor.css">
<link rel="stylesheet" href="assets/store/css/diy.css?v=<?= $version ?>">
<div class="row-content am-cf">
    <div class="widget am-cf">
        <div class="widget-body">
            <!-- diy 工作区 -->
            <div id="app" class="work-diy dis-flex flex-x-between">
                <!-- 工具栏 -->
                <div id="diy-menu" class="diy-menu">
                    <div class="menu-title"><span>组件库</span></div>
                    <div class="navs">
                        <div class="navs-group">
                            <div class="title">媒体组件</div>
                            <div class="navs-components am-cf">
                                <nav class="special" @click="onAddItem('banner')">
                                    <p class="item-icon"><i class="iconfont icon-tupianlunbo"></i></p>
                                    <p>图片轮播</p>
                                </nav>
                                <nav class="special" @click="onAddItem('imageSingle')">
                                    <p class="item-icon"><i class="iconfont icon-tupian1"></i></p>
                                    <p>单图组</p>
                                </nav>
                                <nav class="special" @click="onAddItem('window')">
                                    <p class="item-icon"><i class="iconfont icon-newbilayout"></i></p>
                                    <p>图片橱窗</p>
                                </nav>
                                <nav class="special" @click="onAddItem('video')">
                                    <p class="item-icon"><i class="iconfont icon-shipin7"></i></p>
                                    <p>视频组</p>
                                </nav>
                                <nav class="special" @click="onAddItem('article')">
                                    <p class="item-icon"><i class="iconfont icon-zixun"></i></p>
                                    <p>文章组</p>
                                </nav>
                                <nav class="special" @click="onAddItem('special')">
                                    <p class="item-icon"><i class="iconfont icon-toutiao"></i></p>
                                    <p>头条快报</p>
                                </nav>
                            </div>
                            <div class="title">商城组件</div>
                            <div class="navs-components am-cf">
                                <nav class="special" @click="onAddItem('search')">
                                    <p class="item-icon"><i class="iconfont icon-wxbsousuotuiguang"></i></p>
                                    <p>搜索框</p>
                                </nav>
                                <nav class="special" @click="onAddItem('notice')">
                                    <p class="item-icon"><i class="iconfont icon-gonggao"></i></p>
                                    <p>公告组</p>
                                </nav>
                                <nav class="special" @click="onAddItem('navBar')">
                                    <p class="item-icon"><i class="iconfont icon-daohang"></i></p>
                                    <p>导航组</p>
                                </nav>
                                <nav class="special" @click="onAddItem('goods')">
                                    <p class="item-icon"><i class="iconfont icon-shangpin5"></i></p>
                                    <p>商品组</p>
                                </nav>
                                <nav class="special" @click="onAddItem('coupon')">
                                    <p class="item-icon"><i class="iconfont icon-youhuiquan2"></i></p>
                                    <p>优惠券组</p>
                                </nav>
                                <nav class="special" @click="onAddItem('sharingGoods')">
                                    <p class="item-icon"><i class="iconfont icon-shangpin5"></i></p>
                                    <p>拼团商品</p>
                                </nav>
                                <nav class="special" @click="onAddItem('shop')">
                                    <p class="item-icon"><i class="iconfont icon-mendian"></i></p>
                                    <p>线下门店</p>
                                </nav>
                            </div>
                            <div class="title">工具组件</div>
                            <div class="navs-components am-cf">
                                <nav class="special" @click="onAddItem('service')">
                                    <p class="item-icon"><i class="iconfont icon-kefu"></i></p>
                                    <p>在线客服</p>
                                </nav>
                                <nav class="special" @click="onAddItem('richText')">
                                    <p class="item-icon"><i class="iconfont icon-wenbenbianji"></i></p>
                                    <p>富文本</p>
                                </nav>
                                <nav class="special" @click="onAddItem('blank')">
                                    <p class="item-icon"><i class="iconfont icon-kongbai"></i></p>
                                    <p>辅助空白</p>
                                </nav>
                                <nav class="special" @click="onAddItem('guide')">
                                    <p class="item-icon"><i class="iconfont icon-fengexian1"></i></p>
                                    <p>辅助线</p>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="action">
                        <button @click.stop="onSubmit" type="button" class="am-btn am-btn-xs am-btn-secondary">
                            保存页面
                        </button>
                    </div>
                </div>

                <!--手机diy容器-->
                <div class="diy-phone" v-cloak>
                    <!-- 手机顶部标题 -->
                    <div id="diy-page" class="phone-top optional" @click.stop="onEditer('page')"
                         :class="{selected: 'page' === selectedIndex}"
                         :style="{background: diyData.page.style.titleBackgroundColor + ' url(assets/store/img/diy/phone-top-' + diyData.page.style.titleTextColor + '.png) no-repeat center / contain'}">
                        <h4 :style="{color: diyData.page.style.titleTextColor}">{{ diyData.page.params.title }}</h4>
                    </div>
                    <!-- 小程序内容区域 -->
                    <div id="phone-main" class="phone-main" v-cloak>
                        <draggable :list="diyData.items" class="dragArea" @update="onDragItemEnd"
                                   :options="{animation: 120, filter: '.drag__nomove' }">
                            <template v-for="(item, index) in diyData.items">

                                <!-- diy元素: 图片轮播 -->
                                <template v-if="item.type == 'banner'">
                                    <div class="drag optional" @click.stop="onEditer(index)"
                                         :class="{selected: index === selectedIndex}">
                                        <div class="diy-banner">
                                            <img v-for="(banner, index) in item.data" v-if="index <= 1"
                                                 :src="banner.imgUrl">
                                            <div class="dots center" :class="item.style.btnShape">
                                                    <span v-for="banner in item.data"
                                                          :style="{background:item.style.btnColor}"></span>
                                            </div>
                                        </div>
                                        <div class="btn-edit-del">
                                            <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 单图组 -->
                                <template v-else-if="item.type == 'imageSingle'">
                                    <div @click.stop="onEditer(index)">
                                        <div class="drag optional" :class="{selected:index === selectedIndex}">
                                            <div class="diy-imageSingle"
                                                 :style="{ paddingBottom: item.style.paddingTop + 'px', background: item.style.background}">
                                                <div class="item-image" v-for="imageSingle in item.data"
                                                     :style="{padding: item.style.paddingTop + 'px ' + item.style.paddingLeft + 'px 0'}">
                                                    <img :src="imageSingle.imgUrl">
                                                </div>
                                            </div>
                                            <div class="btn-edit-del">
                                                <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 图片橱窗 -->
                                <template v-else-if="item.type == 'window'">
                                    <div @click.stop="onEditer(index)">
                                        <div class="drag optional" :class="{selected:index === selectedIndex}">
                                            <div class="diy-window"
                                                 :style="{ background: item.style.background, padding: item.style.paddingTop + 'px ' + item.style.paddingLeft + 'px' }">
                                                <ul class="data-list" v-if="item.style.layout > -1"
                                                    :class="'am-avg-sm-' + item.style.layout">
                                                    <li v-for="window in item.data"
                                                        :style="{ padding: item.style.paddingTop + 'px ' + item.style.paddingLeft + 'px' }">
                                                        <div class="item-image">
                                                            <img :src="window.imgUrl">
                                                        </div>
                                                    </li>
                                                </ul>
                                                <div class="display" v-else>
                                                    <div class="display-left"
                                                         :style="{ padding: item.style.paddingTop + 'px ' + item.style.paddingLeft + 'px' }">
                                                        <img :src="item.data[0].imgUrl">
                                                    </div>
                                                    <div class="display-right">
                                                        <div v-if="item.data.length >= 2" class="display-right1"
                                                             :style="{ padding:item.style.paddingTop+'px '+item.style.paddingLeft +'px' }">
                                                            <img :src="item.data[1].imgUrl">
                                                        </div>
                                                        <div class="display-right2">
                                                            <div v-if="item.data.length >= 3" class="left"
                                                                 :style="{ padding:item.style.paddingTop + 'px ' + item.style.paddingLeft + 'px' }">
                                                                <img :src="item.data[2].imgUrl">
                                                            </div>
                                                            <div v-if="item.data.length >= 4" class="right"
                                                                 :style="{ padding:item.style.paddingTop + 'px ' + item.style.paddingLeft + 'px' }">
                                                                <img :src="item.data[3].imgUrl">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="btn-edit-del">
                                                <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 视频组 -->
                                <template v-else-if="item.type == 'video'">
                                    <div @click.stop="onEditer(index)">
                                        <div class="drag optional" :class="{selected:index === selectedIndex}">
                                            <div class="diy-video" :style="{padding:item.style.paddingTop + 'px 0'}">
                                                <video :style="{height:item.style.height + 'px'}"
                                                       :src="item.params.videoUrl"
                                                       :poster="item.params.poster"
                                                       :autoplay="item.params.autoplay == 1"
                                                       controls>
                                                    您的浏览器不支持 video 标签
                                                </video>
                                            </div>
                                            <div class="btn-edit-del">
                                                <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 文章组 -->
                                <template v-else-if="item.type == 'article'">
                                    <div @click.stop="onEditer(index)">
                                        <div class="drag optional" :class="{selected:index === selectedIndex}">
                                            <div class="diy-article">
                                                <div class="article-item"
                                                     v-for="item in (item.params.source == 'choice' ? item.data : item.defaultData)"
                                                     :class="'show-type__' + item.show_type">
                                                    <!-- 小图模式 -->
                                                    <template v-if="item.show_type == 10">
                                                        <div class="article-item__left flex-box">
                                                            <div class="article-item__title twolist-hidden">
                                                                <span class="f-30 col-3">{{ item.article_title }}</span>
                                                            </div>
                                                            <div class="article-item__footer">
                                                                <span class="article-views">
                                                                    {{ item.views_num }}次浏览
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="article-item__image">
                                                            <img :src="item.image" alt="">
                                                        </div>
                                                    </template>
                                                    <!-- 大图模式 -->
                                                    <template v-if="item.show_type == 20">
                                                        <div class="article-item__title">
                                                            <span class="f-30 col-3">{{ item.article_title }}</span>
                                                        </div>
                                                        <div class="article-item__image">
                                                            <img :src="item.image">
                                                        </div>
                                                        <div class="article-item__footer">
                                                            <span class="article-views">
                                                                {{ item.views_num }}次浏览
                                                            </span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                            <div class="btn-edit-del">
                                                <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 头条快报 -->
                                <template v-else-if="item.type == 'special'">
                                    <div @click.stop="onEditer(index)">
                                        <div class="drag optional" :class="{selected:index === selectedIndex}">
                                            <div class="diy-special dis-flex flex-y-center">
                                                <div class="special-left">
                                                    <img :src="item.style.image" alt="">
                                                </div>
                                                <div class="special-content flex-box"
                                                     :class="'display_' + item.style.display">
                                                    <ul class="special-content-list">
                                                        <li class="content-item am-text-truncate"
                                                            v-for="item in (item.params.source == 'choice' ? item.data : item.defaultData)">
                                                            <span>{{ item.article_title }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="special-more">
                                                    <i class="iconfont icon-xiangyoujiantou"></i>
                                                </div>
                                            </div>
                                            <div class="btn-edit-del">
                                                <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 搜索栏 -->
                                <template v-else-if="item.type == 'search'">
                                    <div @click.stop="onEditer(index)">
                                        <div class="drag optional" :class="{selected:index === selectedIndex}">
                                            <div class="diy-search">
                                                <div class="inner"
                                                     :class="item.style.searchStyle">
                                                    <div class="search-input"
                                                         :style="{textAlign: item.style.textAlign}">
                                                        <i class="search-icon iconfont icon-ss-search"></i>
                                                        <span>{{item.params.placeholder}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="btn-edit-del">
                                                <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 公告组 -->
                                <template v-else-if="item.type == 'notice'">
                                    <div @click.stop="onEditer(index)">
                                        <div class="drag optional" :class="{selected:index === selectedIndex}">
                                            <div class="diy-notice dis-flex"
                                                 :style="{padding: item.style.paddingTop + 'px' + ' 10px', background: item.style.background}">
                                                <div class="notice__icon">
                                                    <img :src="item.params.icon">
                                                </div>
                                                <div class="notice__text flex-box am-text-truncate">
                                                    <span :style="{color: item.style.textColor}">{{item.params.text}}</span>
                                                </div>
                                            </div>
                                            <div class="btn-edit-del">
                                                <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 导航组 -->
                                <template v-else-if="item.type == 'navBar'">
                                    <div @click.stop="onEditer(index)">
                                        <div class="drag optional" :class="{selected:index === selectedIndex}">
                                            <div class="diy-navBar" :style="{background:item.style.background}">
                                                <ul class=""
                                                    :class="item.style.rowsNum === '4'?'am-avg-sm-4':(item.style.rowsNum ==='3'?'am-avg-sm-3':'am-avg-sm-5')">
                                                    <li class="" v-for="(navBar,index) in item.data">
                                                        <div class="item-image">
                                                            <img :src="navBar.imgUrl">
                                                        </div>
                                                        <p class="item-text am-text-truncate"
                                                           :style="{color:navBar.color}">
                                                            {{navBar.text}}</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="btn-edit-del">
                                                <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 商品组 -->
                                <template v-else-if="item.type == 'goods'">
                                    <div @click.stop="onEditer(index)">
                                        <div class="drag optional" :class="{selected: index === selectedIndex}">
                                            <div class="diy-goods" :style="{background:item.style.background}">
                                                <ul class="goods-list am-cf"
                                                    :class="['display__' + item.style.display, 'column__' + item.style.column]">


                                                    <li class="goods-item"
                                                        v-for="goods in (item.params.source == 'choice' ? item.data : item.defaultData)">

                                                        <!-- 单列商品 -->
                                                        <template v-if="item.style.column == 1">
                                                            <div class="dis-flex">
                                                                <!-- 商品图片 -->
                                                                <div class="goods-item_left">
                                                                    <img :src="goods.image">
                                                                </div>
                                                                <div class="goods-item_right">
                                                                    <!-- 商品名称 -->
                                                                    <div v-if="item.style.show.goodsName"
                                                                         class="goods-item_title twolist-hidden">
                                                                        <span>{{ goods.goods_name }}</span>
                                                                    </div>
                                                                    <div class="goods-item_desc">
                                                                        <!-- 商品卖点 -->
                                                                        <div v-if="item.style.show.sellingPoint"
                                                                             class="desc-selling_point am-text-truncate">
                                                                            <span>{{ goods.selling_point }}</span>
                                                                        </div>
                                                                        <!-- 商品销量 -->
                                                                        <div v-if="item.style.show.goodsSales"
                                                                             class="desc-goods_sales am-text-truncate">
                                                                            <span>已售{{ goods.goods_sales }}件</span>
                                                                        </div>
                                                                        <!-- 商品价格 -->
                                                                        <div class="desc_footer">
                                                                            <span v-if="item.style.show.goodsPrice"
                                                                                  class="price_x">¥{{ goods.goods_price }}</span>
                                                                            <span class="price_y x-color-999"
                                                                                  v-if="item.style.show.linePrice && goods.line_price > 0">¥{{ goods.line_price }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </template>

                                                        <!-- 两列三列 -->
                                                        <template v-else>
                                                            <div class="goods-image">
                                                                <img :src="goods.image">
                                                            </div>
                                                            <div class="detail">
                                                                <p v-if="item.style.show.goodsName"
                                                                   class="goods-name twolist-hidden">
                                                                    {{goods.goods_name}}
                                                                </p>
                                                                <p class="detail-price">
                                                                  <span v-if="item.style.show.goodsPrice"
                                                                        class="goods-price x-color-red">{{ goods.goods_price }}</span>
                                                                    <span v-if="item.style.show.linePrice && goods.line_price > 0"
                                                                          class="line-price">{{ goods.line_price }}</span>
                                                                </p>
                                                            </div>
                                                        </template>

                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="btn-edit-del">
                                                <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 优惠券组 -->
                                <template v-else-if="item.type == 'coupon'">
                                    <div @click.stop="onEditer(index)"
                                         class="drag optional" :class="{selected: index === selectedIndex}">
                                        <div class="diy-coupon dis-flex flex-x-around"
                                             :style="{background:item.style.background,padding: item.style.paddingTop+'px '+' 0'}">
                                            <div v-for="coupon in item.data" class="coupon-wrapper">
                                                <div class="coupon-item" :style="{background:coupon.color}">
                                                    <i class="before" :style="{background:item.style.background}"></i>
                                                    <div :style="{background:coupon.color}"
                                                         class="left-content dis-flex flex-dir-column flex-x-center flex-y-center">
                                                        <div class="content-top">
                                                            <span class="unit">￥</span>
                                                            <span class="price">{{ coupon.reduce_price }}</span>
                                                        </div>
                                                        <div class="content-bottom">
                                                            <span>满{{ coupon.min_price }}元可用</span>
                                                        </div>
                                                    </div>
                                                    <div class="right-receive dis-flex flex-dir-column flex-x-center flex-y-center">
                                                        <span>立即</span>
                                                        <span>领取</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-edit-del">
                                            <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 拼团商品组 -->
                                <template v-else-if="item.type == 'sharingGoods'">
                                    <div @click.stop="onEditer(index)">
                                        <div class="drag optional" :class="{selected:index === selectedIndex}">
                                            <div class="diy-sharingGoods" :style="{background: item.style.background }">
                                                <div class="goods-item dis-flex" v-for="(goods, index) in item.data">
                                                    <!-- 商品图片 -->
                                                    <div class="goods-item_left">
                                                        <img :src="goods.image">
                                                    </div>
                                                    <div class="goods-item_right">
                                                        <!-- 商品名称 -->
                                                        <div v-if="item.style.show.goodsName"
                                                             class="goods-item_title twolist-hidden">
                                                            <span>{{ goods.goods_name }}</span>
                                                        </div>
                                                        <div class="goods-item_desc">
                                                            <!-- 商品卖点 -->
                                                            <div v-if="item.style.show.sellingPoint"
                                                                 class="desc-selling_point am-text-truncate">
                                                                <span>{{ goods.selling_point }}</span>
                                                            </div>
                                                            <!-- 拼团信息 -->
                                                            <div class="desc-situation">
                                                                <span class="iconfont icon-pintuan_huaban"></span>
                                                                <span class="people">2人团</span>
                                                                <span class="x-color-999">已有43人进行拼团</span>
                                                            </div>
                                                            <!-- 商品价格 -->
                                                            <div class="desc_footer">
                                                                <span class="price_x"
                                                                      v-if="item.style.show.sharingPrice">¥{{ goods.sharing_price }}</span>
                                                                <span class="price_y x-color-999"
                                                                      v-if="item.style.show.linePrice && goods.line_price > 0">¥{{ goods.line_price }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="btn-settlement">去拼团</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="btn-edit-del">
                                                <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 线下门店 -->
                                <template v-else-if="item.type == 'shop'">
                                    <div @click.stop="onEditer(index)">
                                        <div class="drag optional" :class="{selected:index === selectedIndex}">
                                            <div class="diy-shop">
                                                <div class="shop-item dis-flex flex-y-center"
                                                     v-for="(shop, index) in (item.params.source == 'choice' ? item.data : item.defaultData)">
                                                    <div class="shop-item__logo">
                                                        <img :src="shop.logo_image" alt="门店logo">
                                                    </div>
                                                    <div class="shop-item__content flex-box">
                                                        <div class="shop-item__title">
                                                            <span>{{ shop.shop_name }}</span>
                                                        </div>
                                                        <div class="shop-item__address am-text-truncate">
                                                            <span>门店地址：{{ shop.region.province }}{{ shop.region.city }}{{ shop.region.region }}{{ shop.address }}</span>
                                                        </div>
                                                        <div class="shop-item__phone">
                                                            <span>联系电话：{{ shop.phone }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="btn-edit-del">
                                                <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 辅助空白 -->
                                <template v-else-if="item.type == 'blank'">
                                    <div @click.stop="onEditer(index)">
                                        <div class="drag optional" :class="{selected:index === selectedIndex}">
                                            <div class="diy-blank"
                                                 :style="{height: item.style.height +'px', background:item.style.background }">
                                            </div>
                                            <div class="btn-edit-del">
                                                <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 辅助线 -->
                                <template v-else-if="item.type == 'guide'">
                                    <div @click.stop="onEditer(index)"
                                         class="drag optional" :class="{selected: index === selectedIndex}">
                                        <div class="diy-guide"
                                             :style="{padding: item.style.paddingTop +'px '+'0', background:item.style.background }">
                                            <p class="line"
                                               :style="{borderTopWidth: item.style.lineHeight +'px',borderTopColor:item.style.lineColor,borderTopStyle: item.style.lineStyle }">
                                            </p>
                                        </div>
                                        <div class="btn-edit-del">
                                            <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 在线客服 -->
                                <template v-else-if="item.type == 'service'">
                                    <div class="diy-service drag optional drag__nomove" @click.stop="onEditer(index)"
                                         :class="{selected: index === selectedIndex}"
                                         :style="{ right: item.style.right + '%', bottom: item.style.bottom + '%', opacity: item.style.opacity / 100 }">
                                        <div class="service-icon">
                                            <img :src="item.params.image" alt="">
                                        </div>
                                        <div class="btn-edit-del">
                                            <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                        </div>
                                    </div>
                                </template>

                                <!-- diy元素: 富文本 -->
                                <template v-else-if="item.type == 'richText'">
                                    <div @click.stop="onEditer(index)">
                                        <div class="drag optional" :class="{selected: index === selectedIndex}">
                                            <div class="diy-richText"
                                                 :style="{background: item.style.background, padding: item.style.paddingTop + 'px ' + item.style.paddingLeft + 'px'}"
                                                 v-html="item.params.content">
                                            </div>
                                            <div class="btn-edit-del">
                                                <div class="btn-del" @click.stop="onDeleleItem(index)">删除</div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                            </template>
                        </draggable>
                    </div>
                </div>

                <!-- 编辑器容器 -->
                <div id="diy-editor" ref="diy-editor" class="diy-editor form-horizontal"
                     :style="{ visibility: selectedIndex != -1 ? 'visible' : 'hidden' } " v-cloak>

                    <!-- 编辑器: 标题栏 -->
                    <div id="tpl_editor_page" v-if="selectedIndex === 'page'">
                        <div class="editor-title"><span>{{ diyData.page.name }}</span></div>
                        <form class="am-form tpl-form-line-form">
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label am-text-xs">页面名称 </label>
                                <div class="am-u-sm-8 am-u-end">
                                    <input class="tpl-form-input" type="text"
                                           v-model="diyData.page.params.name">
                                    <div class="help-block am-margin-top-xs">
                                        <small>页面名称仅用于后台查找</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label am-text-xs">页面标题 </label>
                                <div class="am-u-sm-8 am-u-end">
                                    <input class="tpl-form-input" type="text"
                                           v-model="diyData.page.params.title">
                                    <div class="help-block am-margin-top-xs">
                                        <small>小程序端顶部显示的标题</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label am-text-xs">分享标题 </label>
                                <div class="am-u-sm-8 am-u-end">
                                    <input class="tpl-form-input" type="text"
                                           v-model="diyData.page.params.share_title">
                                    <div class="help-block am-margin-top-xs">
                                        <small>小程序端转发时显示的标题</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label am-text-xs">标题栏文字 </label>
                                <div class="am-u-sm-8 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" value="black"
                                               v-model="diyData.page.style.titleTextColor">
                                        黑色
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" value="white"
                                               v-model="diyData.page.style.titleTextColor">
                                        白色
                                    </label>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label am-text-xs">标题栏背景 </label>
                                <div class="am-u-sm-8 am-u-end">
                                    <input class="" type="color"
                                           v-model="diyData.page.style.titleBackgroundColor">
                                    <button type="button" class="btn-resetColor am-btn am-btn-xs"
                                            @click.stop="onEditorResetColor(diyData.page.style, 'titleBackgroundColor', '#ffffff')">
                                        重置
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <template v-if="diyData.items.length && curItem">

                        <!--编辑器: 在线客服-->
                        <div id="tpl_editor_service" v-if="curItem.type == 'service'">
                            <div class="editor-title"><span>{{ curItem.name }}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs"> 底边距 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.style.bottom" min="0" max="100">
                                        <div class="display-value">
                                            <span class="value">{{ curItem.style.bottom }}</span>%
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs"> 右边距 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.style.right" min="0" max="100">
                                        <div class="display-value">
                                            <span class="value">{{ curItem.style.right }}</span>%
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs"> 不透明度 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.style.opacity" min="0" max="100">
                                        <div class="display-value">
                                            <span class="value">{{ curItem.style.opacity }}</span>%
                                        </div>
                                    </div>
                                </div>
                                <hr data-am-widget="divider" class="am-divider am-divider-dashed">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs"> 客服类型 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <label class="am-radio-inline">
                                            <input type="radio" value="chat"
                                                   v-model="curItem.params.type"> 在线聊天
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="phone"
                                                   v-model="curItem.params.type"> 拨打电话
                                        </label>
                                    </div>
                                </div>
                                <div class="am-form-group" v-show="curItem.params.type == 'phone'">
                                    <label class="am-u-sm-3 am-form-label am-text-xs"> 电话号码 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="text" class="tpl-form-input" placeholder="请输入电话号码"
                                               v-model="curItem.params.phone_num">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs"> 客服图标 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <div class="data-image">
                                            <img :src="curItem.params.image"
                                                 style="height: 45px;" title="点击更换图片" alt=""
                                                 @click="onEditorSelectImage(curItem.params, 'image')">
                                        </div>
                                        <div class="help-block">
                                            <small>建议尺寸：90×90</small>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- 编辑器: 图片轮播 -->
                        <div id="tpl_editor_banner" v-if="curItem.type == 'banner'">
                            <div class="editor-title"><span>{{curItem.name}}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">指示点形状 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <label class="am-radio-inline">
                                            <input type="radio" value="round"
                                                   v-model="curItem.style.btnShape"> 圆形
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="square"
                                                   v-model="curItem.style.btnShape"> 正方形
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="rectangle"
                                                   v-model="curItem.style.btnShape"> 长方形
                                        </label>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">指示点颜色 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input class="" type="color"
                                               v-model="curItem.style.btnColor">
                                        <button type="button" class="btn-resetColor am-btn am-btn-xs"
                                                @click.stop="onEditorResetColor(curItem.style, 'btnColor', '#ffffff')">
                                            重置
                                        </button>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs"> 切换时间 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="tpl-form-input" type="number"
                                               v-model="curItem.params.interval">
                                        <div class="help-block">
                                            <small>轮播图自动切换的间隔时间，单位：毫秒</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-items">
                                    <draggable :list="curItem.data"
                                               :options="{ animation: 120, filter: 'input', preventOnFilter: false }">
                                        <div class="form-item"
                                             v-for="(banner, index) in curItem.data">
                                            <i class="iconfont icon-shanchu item-delete"
                                               @click="onEditorDeleleData(index, selectedIndex)"></i>
                                            <div class="item-inner">
                                                <div class="am-form-group">
                                                    <label class="am-u-sm-3 am-form-label am-text-xs">图片 </label>
                                                    <div class="am-u-sm-8 am-u-end">
                                                        <div class="data-image">
                                                            <img :src="banner.imgUrl" alt=""
                                                                 @click="onEditorSelectImage(banner, 'imgUrl')">
                                                        </div>
                                                        <div class="help-block">
                                                            <small>建议尺寸750x360</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="am-form-group">
                                                    <label class="am-u-sm-3 am-form-label am-text-xs">链接地址 </label>
                                                    <div class="am-u-sm-8 am-u-end">
                                                        <input type="text" value=""
                                                               v-model='banner.linkUrl'>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </draggable>
                                </div>
                                <div class="j-data-add form-item-add" @click="onEditorAddData">
                                    <i class="fa fa-plus"></i> 添加一个
                                </div>
                            </form>
                        </div>

                        <!--编辑器: 单图组-->
                        <div id="tpl_editor_imageSingle" v-if="curItem.type == 'imageSingle'">
                            <div class="editor-title"><span>{{ curItem.name }}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">上下边距 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.style.paddingTop" min="0" max="50">
                                        <div class="display-value">
                                            <span class="value">{{ curItem.style.paddingTop }}</span>px
                                            (像素)
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">左右边距 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.style.paddingLeft" min="0"
                                               max="50">
                                        <div class="display-value">
                                            <span class="value">{{ curItem.style.paddingLeft }}</span>px
                                            (像素)
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">背景颜色 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input class="" type="color"
                                               v-model="curItem.style.background">
                                        <button type="button" class="btn-resetColor am-btn am-btn-xs"
                                                @click.stop="onEditorResetColor(curItem.style, 'background', '#ffffff')">
                                            重置
                                        </button>
                                    </div>
                                </div>
                                <div class="form-items">
                                    <draggable :list="curItem.data"
                                               :options="{ animation: 120, filter: 'input', preventOnFilter: false }">
                                        <div class="form-item drag" v-for="(imageSingle, index) in curItem.data">
                                            <i class="iconfont icon-shanchu item-delete"
                                               @click="onEditorDeleleData(index,selectedIndex)"></i>
                                            <div class="item-inner">
                                                <div class="am-form-group">
                                                    <label class="am-u-sm-3 am-form-label am-text-xs">图片 </label>
                                                    <div class="am-u-sm-8 am-u-end">
                                                        <div class="data-image">
                                                            <img :src="imageSingle.imgUrl" alt=""
                                                                 @click="onEditorSelectImage(imageSingle, 'imgUrl')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="am-form-group">
                                                    <label class="am-u-sm-3 am-form-label am-text-xs">链接地址 </label>
                                                    <div class="am-u-sm-8 am-u-end">
                                                        <input type="text" value="" v-model='imageSingle.linkUrl'>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </draggable>
                                </div>
                                <div class="j-data-add form-item-add" @click="onEditorAddData">
                                    <i class="fa fa-plus"></i> 添加一个
                                </div>
                            </form>
                        </div>

                        <!--编辑器: 图片橱窗-->
                        <div id="tpl_editor_window" v-if="curItem.type == 'window'">
                            <div class="editor-title"><span>{{ curItem.name }}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">上下边距 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.style.paddingTop" min="0" max="50">
                                        <div class="display-value">
                                            <span class="value">{{ curItem.style.paddingTop }}</span>px
                                            (像素)
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">左右边距 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.style.paddingLeft" min="0"
                                               max="50">
                                        <div class="display-value">
                                            <span class="value">{{ curItem.style.paddingLeft }}</span>px
                                            (像素)
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">背景颜色 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input class="" type="color"
                                               v-model="curItem.style.background">
                                        <button type="button" class="btn-resetColor am-btn am-btn-xs"
                                                @click.stop="onEditorResetColor(curItem.style, 'background', '#ffffff')">
                                            重置
                                        </button>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">布局方式 </label>
                                    <div class="j-switch-help am-u-sm-8 am-u-end">
                                        <label class="am-radio-inline">
                                            <input type="radio" value="2"
                                                   v-model="curItem.style.layout"> 堆积两列
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="3"
                                                   v-model="curItem.style.layout"> 堆积三列
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="4"
                                                   v-model="curItem.style.layout"> 堆积四列
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="-1"
                                                   v-model="curItem.style.layout"> 橱窗样式
                                            <small class="help am-hide">
                                                最多显示四张图片，超出隐藏
                                            </small>
                                        </label>
                                        <div class="help-block am-margin-top-xs" data-default="请确保所有图片的尺寸/比例相同。">
                                            <small>请确保所有图片的尺寸/比例相同。</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-items">
                                    <draggable :list="curItem.data"
                                               :options="{ animation: 120, filter: 'input', preventOnFilter: false }">
                                        <div class="form-item drag" v-for="(item, index) in curItem.data">
                                            <i class="iconfont icon-shanchu item-delete"
                                               @click="onEditorDeleleData(index,selectedIndex)">
                                            </i>
                                            <div class="item-inner">
                                                <div class="am-form-group">
                                                    <label class="am-u-sm-3 am-form-label am-text-xs">图片 </label>
                                                    <div class="am-u-sm-8 am-u-end">
                                                        <div class="data-image">
                                                            <img :src="item.imgUrl" alt=""
                                                                 @click="onEditorSelectImage(item, 'imgUrl')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="am-form-group">
                                                    <label class="am-u-sm-3 am-form-label am-text-xs">链接地址 </label>
                                                    <div class="am-u-sm-8 am-u-end">
                                                        <input type="text" v-model="item.linkUrl">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </draggable>
                                </div>
                                <div class="j-data-add form-item-add" @click="onEditorAddData">
                                    <i class="fa fa-plus"></i> 添加一个
                                </div>
                            </form>
                        </div>

                        <!--编辑器: 视频组-->
                        <div id="tpl_editor_video" v-if="curItem.type == 'video'">
                            <div class="editor-title"><span>{{ curItem.name }}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">上下边距 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.style.paddingTop" min="0" max="50">
                                        <div class="display-value">
                                            <span class="value">{{ curItem.style.paddingTop }}</span>px
                                            (像素)
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">视频高度 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.style.height" min="50" max="500">
                                        <div class="display-value">
                                            <span class="value">{{ curItem.style.height }}</span>px
                                            (像素)
                                        </div>
                                        <div class="help-block am-margin-top-xs">
                                            <small>滑块可用左右方向键精确调整</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group am-padding-top">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">视频封面 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <div class="data-image">
                                            <img :src="curItem.params.poster" alt=""
                                                 @click="onEditorSelectImage(curItem.params, 'poster')">
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">视频地址 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input class="tpl-form-input" type="url"
                                               v-model="curItem.params.videoUrl">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">自动播放 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <label class="am-radio-inline">
                                            <input type="radio" value="0"
                                                   v-model="curItem.params.autoplay"> 否
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="1"
                                                   v-model="curItem.params.autoplay"> 是
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!--编辑器: 文章组-->
                        <div id="tpl_editor_article" v-if="curItem.type == 'article'">
                            <div class="editor-title"><span>{{ curItem.name }}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">文章分类 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <select v-model="curItem.params.auto.category"
                                                data-am-selected="{btnSize: 'sm',  placeholder:'全部分类', maxHeight: 400}">
                                            <option value="0"> -- 全部分类 --</option>
                                            <template v-for="item in opts.articleCatgory">
                                                <option :value="item.category_id"> {{ item.name }}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">显示数量 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="tpl-form-input" type="number" min="1"
                                               v-model="curItem.params.auto.showNum">
                                        <div class="help-block am-padding-top-xs">
                                            <small>文章数据请到 <a href="<?= url('content.article/index') ?>" target="_blank">内容管理
                                                    - 文章列表</a>
                                                中管理
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- 编辑器: 头条快报 -->
                        <div id="tpl_editor_special" v-if="curItem.type == 'special'">
                            <div class="editor-title"><span>{{ curItem.name }}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">文章分类 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <select v-model="curItem.params.auto.category"
                                                data-am-selected="{btnSize: 'sm',  placeholder:'全部分类', maxHeight: 400}">
                                            <option value="0"> -- 全部分类 --</option>
                                            <template v-for="item in opts.articleCatgory">
                                                <option :value="item.category_id"> {{ item.name }}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">显示数量 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="tpl-form-input" type="number" min="1"
                                               v-model="curItem.params.auto.showNum">
                                        <div class="help-block am-padding-top-xs">
                                            <small>文章数据请到 <a href="<?= url('content.article/index') ?>" target="_blank">内容管理
                                                    - 文章列表</a>
                                                中管理
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <hr data-am-widget="divider" class="am-divider am-divider-dashed">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs form-require">图片 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <div class="data-image">
                                            <img :src="curItem.style.image" style="height: 38px;" alt=""
                                                 @click="onEditorSelectImage(curItem.style, 'image')">
                                        </div>
                                        <div class="help-block am-padding-top-xs">
                                            <small>建议尺寸140×38</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs form-require"> 显示类型 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <label class="am-radio-inline">
                                            <input type="radio" value="1" v-model="curItem.style.display">
                                            单行
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="2" v-model="curItem.style.display">
                                            两行 </label>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!--编辑器: 搜索栏-->
                        <div id="tpl_editor_search" v-if="curItem.type == 'search'">
                            <div class="editor-title"><span>{{ curItem.name }}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">提示文字 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input class="tpl-form-input" type="text"
                                               v-model="curItem.params.placeholder">
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">搜索框样式 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <label class="am-radio-inline">
                                            <input type="radio" value="square"
                                                   v-model="curItem.style.searchStyle"> 方形
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="radius"
                                                   v-model="curItem.style.searchStyle"> 圆角
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="round"
                                                   v-model="curItem.style.searchStyle"> 圆弧
                                        </label>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">文字对齐 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <label class="am-radio-inline">
                                            <input type="radio" value="left"
                                                   v-model="curItem.style.textAlign">
                                            居左
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="center"
                                                   v-model="curItem.style.textAlign">
                                            居中
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="right"
                                                   v-model="curItem.style.textAlign">
                                            居右
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!--编辑器: 公告组-->
                        <div id="tpl_editor_notice" v-if="curItem.type == 'notice'">
                            <div class="editor-title"><span>{{ curItem.name }}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">上下边距 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.style.paddingTop" min="0" max="50">
                                        <div class="display-value">
                                            <span class="value">{{ curItem.style.paddingTop }}</span>px
                                            (像素)
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">背景颜色 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input class="" type="color"
                                               v-model="curItem.style.background">
                                        <button type="button" class="btn-resetColor am-btn am-btn-xs"
                                                @click.stop="onEditorResetColor(curItem.style, 'background', '#ffffff')">
                                            重置
                                        </button>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">文字颜色 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input class="" type="color"
                                               v-model="curItem.style.textColor">
                                        <button type="button" class="btn-resetColor am-btn am-btn-xs"
                                                @click.stop="onEditorResetColor(curItem.style, 'textColor', '#000000')">
                                            重置
                                        </button>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">公告图标 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <div class="data-image">
                                            <img :src="curItem.params.icon"
                                                 @click="onEditorSelectImage(curItem.params, 'icon')"
                                                 style="height: 30px;" alt="">
                                        </div>
                                        <div class="help-block">
                                            <small>建议尺寸：32×32</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">公告内容</label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input class="tpl-form-input" type="text"
                                               v-model="curItem.params.text">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!--编辑器: 导航组-->
                        <div id="tpl_editor_navBar" v-if="curItem.type == 'navBar'">
                            <div class="editor-title"><span>{{ curItem.name }}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label class="am-u-sm-4 am-form-label am-text-xs">背景颜色 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="" type="color"
                                               v-model="curItem.style.background">
                                        <button type="button" class="btn-resetColor am-btn am-btn-xs"
                                                @click.stop="onEditorResetColor(curItem.style, 'background', '#ffffff')">
                                            重置
                                        </button>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-4 am-form-label am-text-xs">每行数量 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <label class="am-radio-inline">
                                            <input type="radio" value="3"
                                                   v-model="curItem.style.rowsNum"> 3个
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="4"
                                                   v-model="curItem.style.rowsNum"> 4个
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="5"
                                                   v-model="curItem.style.rowsNum"> 5个
                                        </label>
                                    </div>
                                </div>
                                <div class="form-items">
                                    <draggable :list="curItem.data"
                                               :options="{ animation: 120, filter: 'input', preventOnFilter: false }">
                                        <div class="form-item drag" v-for="(navBar, index) in curItem.data">
                                            <i class="iconfont icon-shanchu item-delete"
                                               @click="onEditorDeleleData(index, selectedIndex)"></i>
                                            <div class="item-inner">
                                                <div class="am-form-group">
                                                    <label class="am-u-sm-3 am-form-label am-text-xs">图片 </label>
                                                    <div class="am-u-sm-8 am-u-end">
                                                        <div class="data-image">
                                                            <img :src="navBar.imgUrl" alt=""
                                                                 @click="onEditorSelectImage(navBar, 'imgUrl')">
                                                        </div>
                                                        <div class="help-block">
                                                            <small>建议尺寸100x100</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="am-form-group">
                                                    <label class="am-u-sm-3 am-form-label am-text-xs">文字内容 </label>
                                                    <div class="am-u-sm-8 am-u-end">
                                                        <input type="text" v-model="navBar.text">
                                                    </div>
                                                </div>
                                                <div class="am-form-group">
                                                    <label class="am-u-sm-3 am-form-label am-text-xs">文字颜色 </label>
                                                    <div class="am-u-sm-8 am-u-end">
                                                        <input type="color" v-model="navBar.color">
                                                        <button type="button" class="btn-resetColor am-btn am-btn-xs"
                                                                @click.stop="onEditorResetColor(navBar, 'color', '#666666')">
                                                            重置
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="am-form-group">
                                                    <label class="am-u-sm-3 am-form-label am-text-xs">链接地址 </label>
                                                    <div class="am-u-sm-8 am-u-end">
                                                        <input type="text" v-model="navBar.linkUrl">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </draggable>
                                </div>
                                <div class="j-data-add form-item-add" @click="onEditorAddData">
                                    <i class="fa fa-plus"></i> 添加一个
                                </div>
                            </form>
                        </div>

                        <!--编辑器: 商品组-->
                        <div id="tpl_editor_goods" v-if="curItem.type == 'goods'">
                            <div class="editor-title"><span>{{ curItem.name }}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <!--商品数据-->
                                <div class="j-switch-box" data-item-class="switch-source">
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label am-text-xs">商品来源 </label>
                                        <div class="am-u-sm-8 am-u-end">
                                            <label class="am-radio-inline">
                                                <input type="radio" value="auto"
                                                       v-model="curItem.params.source"> 自动获取
                                            </label>
                                            <label class="am-radio-inline">
                                                <input type="radio" value="choice"
                                                       v-model="curItem.params.source"> 手动选择
                                            </label>
                                        </div>
                                    </div>
                                    <!--手动选择-->
                                    <div class="switch-source __choice "
                                         v-show="curItem.params.source == 'choice'">
                                        <div class="form-items __goods am-cf">
                                            <draggable :list="curItem.data"
                                                       :options="{ animation: 120, filter: 'input', preventOnFilter: false }">
                                                <div v-for="(goods,index) in curItem.data"
                                                     class="form-item drag">
                                                    <i class="iconfont icon-shanchu item-delete" data-no-confirm="true"
                                                       @click="onEditorDeleleData(index, selectedIndex)"></i>
                                                    <div class="item-inner">
                                                        <div class="data-image">
                                                            <img :src="goods.image" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </draggable>
                                        </div>
                                        <div class="j-selectGoods form-item-add" @click.stop="onSelectGoods(curItem)">
                                            <i class="fa fa-plus"></i> 选择商品
                                        </div>
                                    </div>
                                    <!-- 自动获取 -->
                                    <div class="switch-source"
                                         v-show="curItem.params.source == 'auto'">
                                        <div class="am-form-group">
                                            <label class="am-u-sm-3 am-form-label am-text-xs">商品分类 </label>
                                            <div class="am-u-sm-8 am-u-end">
                                                <select v-model="curItem.params.auto.category"
                                                        data-am-selected="{btnSize: 'sm',  placeholder:'全部分类', maxHeight: 400}">
                                                    <option value="0"> -- 全部分类 --</option>
                                                    <template v-for="first in opts.catgory">
                                                        <option :value="first.category_id"> {{ first.name }}</option>
                                                        <template v-if="first.child">
                                                            <option v-for="two in first.child" :value="two.category_id">
                                                                　{{ two.name }}
                                                            </option>
                                                        </template>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="am-form-group">
                                            <label class="am-u-sm-3 am-form-label am-text-xs"> 商品排序 </label>
                                            <div class="am-u-sm-8 am-u-end">
                                                <label class="am-radio-inline">
                                                    <input type="radio" value="all"
                                                           v-model="curItem.params.auto.goodsSort">
                                                    综合
                                                </label>
                                                <label class="am-radio-inline">
                                                    <input type="radio" value="sales"
                                                           v-model="curItem.params.auto.goodsSort">
                                                    销量 </label>
                                                <label class="am-radio-inline">
                                                    <input type="radio" value="price"
                                                           v-model="curItem.params.auto.goodsSort">
                                                    价格 </label>
                                            </div>
                                        </div>
                                        <div class="am-form-group">
                                            <label class="am-u-sm-3 am-form-label am-text-xs"> 显示数量 </label>
                                            <div class="am-u-sm-8 am-u-end">
                                                <input class="tpl-form-input" type="number" min="1"
                                                       v-model="curItem.params.auto.showNum">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--分割线-->
                                <hr data-am-widget="divider" style="" class="am-divider am-divider-dashed"/>
                                <!--组件样式-->
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">背景颜色 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input type="color"
                                               v-model="curItem.style.background">
                                        <button type="button" class="btn-resetColor am-btn am-btn-xs"
                                                @click.stop="onEditorResetColor(curItem.style, 'background', '#f3f3f3')">
                                            重置
                                        </button>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">显示类型 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <label class="am-radio-inline">
                                            <input type="radio" value="list"
                                                   v-model="curItem.style.display"> 列表平铺
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="slide"
                                                   v-model="curItem.style.display"
                                                   :disabled="curItem.style.column == 1"> 横向滑动
                                        </label>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">分列数量 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <label class="am-radio-inline">
                                            <input type="radio" value="1"
                                                   v-model="curItem.style.column"
                                                   :disabled="curItem.style.display == 'slide'"> 单列
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="2"
                                                   v-model="curItem.style.column"> 两列
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="3"
                                                   v-model="curItem.style.column"> 三列
                                        </label>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">显示内容 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <label class="am-checkbox-inline">
                                            <input type="checkbox"
                                                   v-model="curItem.style.show.goodsName"> 商品名称
                                        </label>
                                        <label class="am-checkbox-inline">
                                            <input type="checkbox"
                                                   v-model="curItem.style.show.goodsPrice"> 商品价格
                                        </label>
                                        <label class="am-checkbox-inline">
                                            <input type="checkbox"
                                                   v-model="curItem.style.show.linePrice"> 划线价格
                                        </label>
                                        <label class="am-checkbox-inline" v-show="curItem.style.column == 1">
                                            <input type="checkbox"
                                                   v-model="curItem.style.show.sellingPoint"> 商品卖点
                                        </label>
                                        <label class="am-checkbox-inline" v-show="curItem.style.column == 1">
                                            <input type="checkbox"
                                                   v-model="curItem.style.show.goodsSales"> 商品销量
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- 编辑器: 优惠券组 -->
                        <div id="tpl_editor_coupon" v-if="curItem.type == 'coupon'">
                            <div class="editor-title"><span>{{ curItem.name }}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">上下边距 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.style.paddingTop" min="0" max="50">
                                        <div class="display-value">
                                            <span class="value">{{ curItem.style.paddingTop }}</span>px
                                            (像素)
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">背景颜色 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input type="color" v-model="curItem.style.background">
                                        <button type="button" class="btn-resetColor am-btn am-btn-xs"
                                                @click.stop="onEditorResetColor(curItem.style, 'background', '#ffffff')">
                                            重置
                                        </button>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">显示数量 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.params.limit" min="1" max="5">
                                        <div class="display-value">
                                            最多<span class="value">{{ curItem.params.limit }}</span>个
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!--编辑器: 拼团商品组-->
                        <div id="tpl_editor_sharingGoods" v-if="curItem.type == 'sharingGoods'">
                            <div class="editor-title"><span>{{ curItem.name }}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <!--商品数据-->
                                <div class="j-switch-box" data-item-class="switch-source">
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label am-text-xs">商品来源 </label>
                                        <div class="am-u-sm-8 am-u-end">
                                            <label class="am-radio-inline">
                                                <input type="radio" value="auto"
                                                       v-model="curItem.params.source"> 自动获取
                                            </label>
                                            <label class="am-radio-inline">
                                                <input type="radio" value="choice"
                                                       v-model="curItem.params.source"> 手动选择
                                            </label>
                                        </div>
                                    </div>
                                    <!--手动选择-->
                                    <div class="switch-source __choice" v-show="curItem.params.source == 'choice'">
                                        <div class="form-items __goods am-cf">
                                            <draggable :list="curItem.data"
                                                       :options="{ animation: 120, filter: 'input', preventOnFilter: false }">
                                                <div v-for="(goods, index) in curItem.data"
                                                     class="form-item drag">
                                                    <i class="iconfont icon-shanchu item-delete" data-no-confirm="true"
                                                       @click="onEditorDeleleData(index,selectedIndex)"></i>
                                                    <div class="item-inner">
                                                        <div class="data-image">
                                                            <img :src="goods.image" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </draggable>
                                        </div>
                                        <div class="j-selectGoods form-item-add" @click.stop="onSelectGoods(curItem)">
                                            <i class="fa fa-plus"></i> 选择商品
                                        </div>
                                    </div>
                                    <!--自动获取-->
                                    <div class="switch-source __auto" v-show="curItem.params.source !== 'choice'">
                                        <div class="am-form-group">
                                            <label class="am-u-sm-3 am-form-label am-text-xs">商品分类 </label>
                                            <div class="am-u-sm-8 am-u-end">
                                                <select v-model="curItem.params.auto.category"
                                                        data-am-selected="{btnSize: 'sm',  placeholder:'全部分类', maxHeight: 400}">
                                                    <option value="0"> -- 全部分类 --</option>
                                                    <template v-for="first in opts.sharingCatgory">
                                                        <option :value="first.category_id"> {{ first.name }}</option>
                                                        <template v-if="first.child">
                                                            <option v-for="two in first.child" :value="two.category_id">
                                                                　{{ two.name }}
                                                            </option>
                                                        </template>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="am-form-group">
                                            <label class="am-u-sm-3 am-form-label am-text-xs">商品排序 </label>
                                            <div class="am-u-sm-8 am-u-end">
                                                <label class="am-radio-inline">
                                                    <input type="radio" value="all"
                                                           v-model="curItem.params.auto.goodsSort">
                                                    综合
                                                </label>
                                                <label class="am-radio-inline">
                                                    <input type="radio" value="sales"
                                                           v-model="curItem.params.auto.goodsSort">
                                                    销量
                                                </label>
                                                <label class="am-radio-inline">
                                                    <input type="radio" value="price"
                                                           v-model="curItem.params.auto.goodsSort">
                                                    价格
                                                </label>
                                            </div>
                                        </div>
                                        <div class="am-form-group">
                                            <label class="am-u-sm-3 am-form-label am-text-xs">显示数量 </label>
                                            <div class="am-u-sm-8 am-u-end">
                                                <input class="tpl-form-input" type="number" min="1"
                                                       v-model="curItem.params.auto.showNum">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--分割线-->
                                <hr data-am-widget="divider" style="" class="am-divider am-divider-dashed"/>
                                <!--组件样式-->
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">背景颜色 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="" type="color"
                                               v-model="curItem.style.background">
                                        <button type="button" class="btn-resetColor am-btn am-btn-xs"
                                                @click.stop="onEditorResetColor(curItem.style, 'background', '#f3f3f3')">
                                            重置
                                        </button>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">显示内容 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <label class="am-checkbox-inline">
                                            <input type="checkbox" value="1"
                                                   v-model="curItem.style.show.sellingPoint"> 商品卖点
                                        </label>
                                        <label class="am-checkbox-inline">
                                            <input type="checkbox" value="1"
                                                   v-model="curItem.style.show.sharingPrice"> 拼团价格
                                        </label>
                                        <label class="am-checkbox-inline">
                                            <input type="checkbox"
                                                   v-model="curItem.style.show.linePrice"> 划线价格
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!--编辑器: 辅助空白-->
                        <div id="tpl_editor_blank" v-if="curItem.type == 'blank'">
                            <div class="editor-title"><span>{{ curItem.name }}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label class="am-u-sm-4 am-form-label am-text-xs">背景颜色 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="" type="color"
                                               v-model="curItem.style.background">
                                        <button type="button" class="btn-resetColor am-btn am-btn-xs"
                                                @click.stop="onEditorResetColor(curItem.style, 'background', '#ffffff')">
                                            重置
                                        </button>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-4 am-form-label am-text-xs">组件高度 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.style.height" min="1" max="200">
                                        <div class="display-value">
                                            <span class="value">{{ curItem.style.height }}</span>px
                                            (像素)
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!--编辑器: 辅助线-->
                        <div id="tpl_editor_guide" v-if="curItem.type == 'guide'">
                            <div class="editor-title"><span>{{ curItem.name }}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label class="am-u-sm-4 am-form-label am-text-xs">背景颜色 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="" type="color"
                                               v-model="curItem.style.background">
                                        <button type="button" class="btn-resetColor am-btn am-btn-xs"
                                                @click.stop="onEditorResetColor(curItem.style, 'background', '#ffffff')">
                                            重置
                                        </button>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-4 am-form-label am-text-xs">线条样式 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <label class="am-radio-inline">
                                            <input type="radio" value="solid"
                                                   v-model='curItem.style.lineStyle'>
                                            实线
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="dashed"
                                                   v-model='curItem.style.lineStyle'> 虚线
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" value="dotted"
                                                   v-model='curItem.style.lineStyle'> 点状
                                        </label>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-4 am-form-label am-text-xs">线条颜色 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="" type="color"
                                               v-model="curItem.style.lineColor">
                                        <button type="button" class="btn-resetColor am-btn am-btn-xs"
                                                @click.stop="onEditorResetColor(curItem.style, 'lineColor', '#000000')">
                                            重置
                                        </button>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-4 am-form-label am-text-xs">线条高度 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.style.lineHeight" min="1" max="20">
                                        <div class="display-value">
                                            <span class="value">{{ curItem.style.lineHeight }}</span>px
                                            (像素)
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-4 am-form-label am-text-xs">上下边距 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.style.paddingTop" min="0" max="50">
                                        <div class="display-value">
                                            <span class="value">{{ curItem.style.paddingTop }}</span>px(像素)
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!--编辑器: 富文本-->
                        <div id="tpl_editor_richText" v-if="curItem.type == 'richText'">
                            <div class="editor-title"><span>{{curItem.name}}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">上下边距 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="tpl-form-input" type="range"
                                               v-model="curItem.style.paddingTop" min="0" max="50">
                                        <div class="display-value">
                                            <span class="value">{{ curItem.style.paddingTop }}</span>px
                                            (像素)
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">左右边距 </label>
                                    <div class="am-u-sm-8 am-u-end">
                                        <input class="tpl-form-input" type="range" min="0" max="50"
                                               v-model="curItem.style.paddingLeft">
                                        <div class="display-value">
                                            <span class="value">{{curItem.style.paddingLeft}}</span>px
                                            (像素)
                                        </div>
                                    </div>
                                </div>
                                <div class="am-form-group">
                                    <label class="am-u-sm-3 am-form-label am-text-xs">背景颜色 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input class="" type="color"
                                               v-model="curItem.style.background">
                                        <button type="button" class="btn-resetColor am-btn am-btn-xs"
                                                @click.stop="onEditorResetColor(curItem.style, 'background', '#ffffff')">
                                            重置
                                        </button>
                                    </div>
                                </div>
                                <div class="am-form-group am-padding-top-sm">
                                    <textarea id="ume-editor" name="editorValue"></textarea>
                                </div>
                            </form>
                        </div>

                        <!--编辑器: 线下门店-->
                        <div id="tpl_editor_shop" v-if="curItem.type == 'shop'">
                            <div class="editor-title"><span>{{ curItem.name }}</span></div>
                            <form class="am-form tpl-form-line-form">
                                <!--商品数据-->
                                <div class="j-switch-box" data-item-class="switch-source">
                                    <div class="am-form-group">
                                        <label class="am-u-sm-3 am-form-label am-text-xs">商品来源 </label>
                                        <div class="am-u-sm-8 am-u-end">
                                            <label class="am-radio-inline">
                                                <input type="radio" value="auto"
                                                       v-model="curItem.params.source"> 自动获取
                                            </label>
                                            <label class="am-radio-inline">
                                                <input type="radio" value="choice"
                                                       v-model="curItem.params.source"> 手动选择
                                            </label>
                                        </div>
                                    </div>
                                    <!--手动选择-->
                                    <div class="switch-source __choice"
                                         v-show="curItem.params.source == 'choice'">
                                        <div class="form-items __goods am-cf">
                                            <draggable :list="curItem.data"
                                                       :options="{ animation: 120, filter: 'input', preventOnFilter: false }">
                                                <div v-for="(shop, index) in curItem.data"
                                                     class="form-item drag">
                                                    <i class="iconfont icon-shanchu item-delete" data-no-confirm="true"
                                                       @click="onEditorDeleleData(index, selectedIndex)"></i>
                                                    <div class="item-inner">
                                                        <div class="data-image"><img :src="shop.logo_image" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </draggable>
                                        </div>
                                        <div class="j-selectShop form-item-add" @click.stop="onSelectShop(curItem)">
                                            <span>选择门店</span>
                                        </div>
                                    </div>
                                    <!-- 自动获取 -->
                                    <div class="switch-source"
                                         v-show="curItem.params.source == 'auto'">
                                        <div class="am-form-group">
                                            <label class="am-u-sm-3 am-form-label am-text-xs"> 展示数量 </label>
                                            <div class="am-u-sm-8 am-u-end">
                                                <input class="tpl-form-input" type="number" min="1"
                                                       v-model="curItem.params.auto.showNum">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </template>
                </div>
            </div>
            <!-- 提示 -->
            <div class="tips am-margin-top-lg am-margin-bottom-sm">
                <div class="pre">
                    <p>1. 设计完成后点击"保存页面"，在小程序端页面下拉刷新即可看到效果。</p>
                    <p>2. 如需填写链接地址请参考<a href="<?= url('wxapp.page/links') ?>" target="_blank">页面链接</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 文件库弹窗 -->
{{include file="layouts/_template/file_library" /}}

<script src="assets/common/js/vue.min.js?v=<?= $version ?>"></script>
<script src="assets/common/js/Sortable.min.js?v=<?= $version ?>"></script>
<script src="assets/common/js/vuedraggable.min.js?v=<?= $version ?>"></script>
<script src="assets/store/js/select.data.js?v=<?= $version ?>"></script>
<script src="assets/common/plugins/umeditor/umeditor.config.js?v=<?= $version ?>"></script>
<script src="assets/common/plugins/umeditor/umeditor.min.js?v=<?= $version ?>"></script>
<script src="assets/store/js/diy.js?v=<?= $version ?>"></script>
<script>

    $(function () {

        // 渲染diy页面
        new diyPhone(<?= $defaultData ?>, <?= $jsonData ?>, <?= $opts ?>);

    });

</script>