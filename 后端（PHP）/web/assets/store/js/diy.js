(function () {

    // 解决火狐浏览器拖动新增tab
    document.body.ondrop = function (event) {
        event.preventDefault();
        event.stopPropagation();
    };

    // 默认数据
    var defaultData = {};

    // umeditor 实例
    var $umeditor = {};

    /***
     * 前端可视化diy
     * @constructor
     */
    function diyPhone(initalData, diyData, opts) {
        defaultData = initalData;
        this.init(diyData, opts);
    }

    diyPhone.prototype = {

        init: function (data, opts) {
            // 实例化Vue
            new Vue({
                el: '#app',
                data: {
                    // diy数据
                    diyData: data,
                    // 当前选中的元素（下标）
                    selectedIndex: -1,
                    // 当前选中的diy元素
                    curItem: {},
                    // 外部数据
                    opts: opts
                },

                methods: {

                    /**
                     * 新增Diy组件
                     * @param key
                     */
                    onAddItem: function (key) {
                        // 复制默认diy组件数据
                        var data = $.extend(true, {}, defaultData[key]);
                        this.diyData.items.push(data);
                        // 编辑当前选中的元素
                        this.onEditer(this.diyData.items.length - 1);
                    },

                    /**
                     * 拖动diy元素更新当前索引
                     * @param e
                     */
                    onDragItemEnd: function (e) {
                        this.onEditer(e.newIndex);
                    },

                    /**
                     * 编辑当前选中的Diy元素
                     * @param index
                     */
                    onEditer: function (index) {
                        // 记录当前选中元素的索引
                        this.selectedIndex = index;
                        // 当前选中的元素数据
                        this.curItem = this.selectedIndex === 'page' ? this.diyData.page
                            : this.diyData.items[this.selectedIndex];
                        // 注册编辑器事件
                        this.initEditor();
                    },

                    /**
                     * 删除diy元素
                     * @param index
                     */
                    onDeleleItem: function (index) {
                        var _this = this;
                        layer.confirm('确定要删除吗？', function (layIdx) {
                            _this.diyData.items.splice(index, 1);
                            _this.selectedIndex = -1;
                            layer.close(layIdx);
                        });
                    },

                    /**
                     * 编辑器：选择图片
                     * @param source
                     * @param index
                     */
                    onEditorSelectImage: function (source, index) {
                        $.fileLibrary({
                            type: 'image',
                            done: function (images) {
                                source[index] = images[0]['file_path'];
                            }
                        });
                    },

                    /**
                     * 编辑器：重置颜色
                     * @param holder
                     * @param attribute
                     * @param color
                     */
                    onEditorResetColor: function (holder, attribute, color) {
                        holder[attribute] = color;
                    },

                    /**
                     * 编辑器：删除data元素
                     * @param index
                     * @param selectedIndex
                     */
                    onEditorDeleleData: function (index, selectedIndex) {
                        if (this.diyData.items[selectedIndex].data.length <= 1) {
                            layer.msg('至少保留一个', {anim: 6});
                            return false;
                        }
                        this.diyData.items[selectedIndex].data.splice(index, 1);
                    },

                    /**
                     * 编辑器：添加data元素
                     */
                    onEditorAddData: function () {
                        // 新增data数据
                        var newDataItem = $.extend(true, {}, defaultData[this.curItem.type].data[0]);
                        this.curItem.data.push(newDataItem);
                    },

                    /**
                     * 注册编辑器事件
                     */
                    initEditor: function () {
                        // 注册dom事件
                        this.$nextTick(function () {
                            // 销毁 umeditor 组件
                            if ($umeditor.hasOwnProperty('key')) {
                                $umeditor.destroy();
                            }
                            // 注册html组件
                            this.editorHtmlComponent();
                            // 富文本事件
                            if (this.curItem.type === 'richText') {
                                this.onRichText(this.curItem);
                            }
                        });
                    },

                    /**
                     * 编辑器事件：html组件
                     */
                    editorHtmlComponent: function () {
                        var $editor = $(this.$refs['diy-editor']);
                        // 单/多选框
                        $editor.find('input[type=checkbox], input[type=radio]').uCheck();
                        // select组件
                        // $editor.find('select').selected();
                    },

                    /**
                     * 编辑器事件：拼团商品选择
                     * @param item
                     */
                    onSelectGoods: function (item) {
                        var uris = {
                            goods: 'goods/lists&status=10',
                            sharingGoods: 'sharing.goods/lists&status=10'
                        };
                        $.selectData({
                            title: '选择商品',
                            uri: uris[item.type],
                            duplicate: false,
                            dataIndex: 'goods_id',
                            done: function (data) {
                                data.forEach(function (itm) {
                                    item.data.push(itm)
                                });
                            },
                            getExistData: function () {
                                var existData = [];
                                item.data.forEach(function (goods) {
                                    if (goods.hasOwnProperty('goods_id')) {
                                        existData.push(goods.goods_id);
                                    }
                                });
                                return existData;
                            }
                        });
                    },

                    /**
                     * 选择线下门店
                     * @param item
                     */
                    onSelectShop: function (item) {
                        $.selectData({
                            title: '选择门店',
                            uri: 'shop/lists&status=1',
                            duplicate: false,
                            dataIndex: 'shop_id',
                            done: function (data) {
                                data.forEach(function (itm) {
                                    item.data.push(itm)
                                });
                            },
                            getExistData: function () {
                                var existData = [];
                                item.data.forEach(function (shop) {
                                    if (shop.hasOwnProperty('shop_id')) {
                                        existData.push(shop.shop_id);
                                    }
                                });
                                return existData;
                            }
                        });
                    },

                    /**
                     * 编辑器事件：富文本
                     */
                    onRichText: function (item) {
                        $umeditor = UM.getEditor('ume-editor', {
                            initialFrameWidth: 375,
                            initialFrameHeight: 400
                        });
                        $umeditor.ready(function () {
                            // 写入编辑器内容
                            $umeditor.setContent(item.params.content);
                            $umeditor.addListener('contentChange', function () {
                                item.params.content = $umeditor.getContent();
                            });
                        });
                    },

                    /**
                     * 提交后端保存
                     * @returns {boolean}
                     */
                    onSubmit: function () {
                        if (this.diyData.items.length <= 0) {
                            layer.msg('至少存在一个组件', {anim: 6});
                            return false;
                        }
                        $.post('', {data: JSON.stringify(this.diyData)}, function (result) {
                            result.code === 1 ? $.show_success(result.msg, result.url)
                                : $.show_error(result.msg);
                        });
                    }

                }
            });
        }

    };

    window.diyPhone = diyPhone;

})(window);