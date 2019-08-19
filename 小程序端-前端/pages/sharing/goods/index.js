const App = getApp();
const Sharing = require('../../../utils/extend/sharing.js');
const wxParse = require("../../../wxParse/wxParse.js");
const Dialog = require('../../../components/dialog/dialog');

// 工具类
const util = require('../../../utils/util.js');

// 记录规格的数组
const goodsSpecArr = [];

Page({

  /**
   * 页面的初始数据
   */
  data: {

    indicatorDots: true, // 是否显示面板指示点
    autoplay: true, // 是否自动切换
    interval: 3000, // 自动切换时间间隔
    duration: 800, // 滑动动画时长

    currentIndex: 1, // 轮播图指针
    floorstatus: false, // 返回顶部
    showView: true, // 显示商品规格

    detail: {}, // 商品详情信息
    sharing_price: 0, // 拼团价格
    goods_price: 0, // 单买价
    line_price: 0, // 划线价格
    stock_num: 0, // 库存数量

    order_type: 20, // 下单类型 10=>单独购买 20=>拼团
    goods_num: 1, // 商品数量
    goods_sku_id: 0, // 规格id
    cart_total_num: 0, // 购物车商品总数量
    goodsMultiSpec: {}, // 多规格信息

    countDownList: [], // 时间记录
    actEndTimeList: [],

    // 分享按钮组件
    share: {
      show: false,
      cancelWithMask: true,
      cancelText: '关闭',
      actions: [{
        name: '生成商品海报',
        className: 'action-class',
        loading: false
      }, {
        name: '发送给朋友',
        openType: 'share'
      }],
      // 商品海报
      showPopup: false,
    },

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad(e) {
    let _this = this,
      scene = App.getSceneData(e);
    // 商品id
    _this.data.goods_id = e.goods_id ? e.goods_id : scene.gid;
    // 获取商品信息
    _this.getGoodsDetail();
    // 获取拼团设置
    _this.getSetting();
  },

  /**
   * 获取拼团设置
   */
  getSetting() {
    let _this = this;
    Sharing.getSetting(setting => {
      _this.setData({
        setting
      });
    });
  },

  /**
   * 获取商品信息
   */
  getGoodsDetail() {
    let _this = this;
    App._get('sharing.goods/detail', {
      goods_id: _this.data.goods_id
    }, result => {
      // 初始化商品详情数据
      let data = _this._initGoodsDetailData(result.data);
      _this.setData(data);
      // 执行倒计时函数
      _this.countDown();
    });
  },

  /**
   * 初始化商品详情数据
   */
  _initGoodsDetailData(data) {
    let _this = this;
    // 商品详情
    let goodsDetail = data.detail;
    // 富文本转码
    if (goodsDetail.content.length > 0) {
      wxParse.wxParse('content', 'html', goodsDetail.content, _this, 0);
    }
    // 商品价格/划线价/库存
    data.goods_sku_id = goodsDetail.goods_sku.spec_sku_id;
    data.goods_price = goodsDetail.goods_sku.goods_price;
    data.sharing_price = goodsDetail.goods_sku.sharing_price;
    data.line_price = goodsDetail.goods_sku.line_price;
    data.stock_num = goodsDetail.goods_sku.stock_num;
    // 商品封面图(确认弹窗)
    data.skuCoverImage = goodsDetail.goods_image;
    // 多规格商品封面图(确认弹窗)
    if (goodsDetail.spec_type == 20 && goodsDetail.goods_sku['image']) {
      data.skuCoverImage = goodsDetail.goods_sku['image']['file_path'];
    }
    // 初始化商品多规格
    if (goodsDetail.spec_type == 20) {
      data.goodsMultiSpec = _this.initManySpecData(goodsDetail.goods_multi_spec);
    }
    // 记录倒计时时间
    data['actEndTimeList'] = [];
    if (data.activeList.length > 0) {
      data.activeList.forEach(item => {
        data['actEndTimeList'].push(item.end_time.text);
      });
    }
    return data;
  },

  /**
   * 初始化商品多规格
   */
  initManySpecData(data) {
    for (let i in data.spec_attr) {
      for (let j in data.spec_attr[i].spec_items) {
        if (j < 1) {
          data.spec_attr[i].spec_items[0].checked = true;
          goodsSpecArr[i] = data.spec_attr[i].spec_items[0].item_id;
        }
      }
    }
    return data;
  },

  /**
   * 点击切换不同规格
   */
  onSwitchSpec(e) {
    let _this = this,
      attrIdx = e.currentTarget.dataset.attrIdx,
      itemIdx = e.currentTarget.dataset.itemIdx,
      goodsMultiSpec = _this.data.goodsMultiSpec;

    // 记录formid
    App.saveFormId(e.detail.formId);

    for (let i in goodsMultiSpec.spec_attr) {
      for (let j in goodsMultiSpec.spec_attr[i].spec_items) {
        if (attrIdx == i) {
          goodsMultiSpec.spec_attr[i].spec_items[j].checked = false;
          if (itemIdx == j) {
            goodsMultiSpec.spec_attr[i].spec_items[itemIdx].checked = true;
            goodsSpecArr[i] = goodsMultiSpec.spec_attr[i].spec_items[itemIdx].item_id;
          }
        }
      }
    }
    _this.setData({
      goodsMultiSpec
    });
    // 更新商品规格信息
    _this._updateSpecGoods();
  },

  /**
   * 更新商品规格信息
   */
  _updateSpecGoods() {
    let _this = this,
      specSkuId = goodsSpecArr.join('_');

    // 查找skuItem
    let spec_list = this.data.goodsMultiSpec.spec_list,
      skuItem = spec_list.find((val) => {
        return val.spec_sku_id == specSkuId;
      });

    // 记录goods_sku_id
    // 更新商品价格、划线价、库存
    if (typeof skuItem === 'object') {
      _this.setData({
        goods_sku_id: skuItem.spec_sku_id,
        goods_price: skuItem.form.goods_price,
        sharing_price: skuItem.form.sharing_price,
        line_price: skuItem.form.line_price,
        stock_num: skuItem.form.stock_num,
        skuCoverImage: skuItem.form.image_id > 0 ? skuItem.form.image_path : _this.data.detail.goods_image
      });
    }
  },

  /**
   * 设置轮播图当前指针 数字
   */
  setCurrent(e) {
    let _this = this;
    _this.setData({
      currentIndex: e.detail.current + 1
    });
  },

  /**
   * 返回顶部
   */
  onScrollTop(e) {
    let _this = this;
    App.saveFormId(e.detail.formId);
    _this.setData({
      scrollTop: 0
    });
  },

  /**
   * 显示/隐藏 返回顶部按钮
   */
  scroll(e) {
    let _this = this;
    _this.setData({
      floorstatus: e.detail.scrollTop > 200
    })
  },

  /**
   * 增加商品数量
   */
  onIncGoodsNumber(e) {
    let _this = this;
    App.saveFormId(e.detail.formId);
    _this.setData({
      goods_num: ++_this.data.goods_num
    })
  },

  /**
   * 减少商品数量
   */
  onDecGoodsNumber(e) {
    let _this = this;
    App.saveFormId(e.detail.formId);
    if (_this.data.goods_num > 1) {
      _this.setData({
        goods_num: --_this.data.goods_num
      });
    }
  },

  /**
   * 自定义输入商品数量
   */
  onInputGoodsNum(e) {
    let _this = this,
      iptValue = e.detail.value;
    _this.setData({
      goods_num: util.isPositiveInteger(iptValue) ? iptValue : 1
    });
  },

  /**
   * 确认购买
   */
  onCheckout() {
    let _this = this;
    // 立即购买
    wx.navigateTo({
      url: '../checkout/index?' + util.urlEncode({
        order_type: _this.data.order_type,
        goods_id: _this.data.goods_id,
        goods_num: _this.data.goods_num,
        goods_sku_id: _this.data.goods_sku_id,
      }),
      success() {
        // 关闭弹窗
        _this.onToggleTrade();
      }
    });

  },

  /**
   * 浏览商品图片
   */
  onPreviewImages(e) {
    let index = e.currentTarget.dataset.index,
      imageUrls = [];
    _this.data.detail.image.forEach(item => {
      imageUrls.push(item.file_path);
    });
    wx.previewImage({
      current: imageUrls[index],
      urls: imageUrls
    })
  },

  /**
   * 预览Sku规格图片
   */
  onPreviewSkuImage(e) {
    let _this = this;
    wx.previewImage({
      current: _this.data.image_path,
      urls: [_this.data.image_path]
    })
  },

  /**
   * 跳转到评论
   */
  onTargetToComment(e) {
    let _this = this;
    App.saveFormId(e.detail.formId);
    wx.navigateTo({
      url: './comment/comment?goods_id=' + _this.data.goods_id
    })
  },

  /**
   * 分享当前页面
   */
  onShareAppMessage() {
    let _this = this;
    // 构建页面参数
    let params = App.getShareUrlParams({
      'goods_id': _this.data.goods_id
    });
    return {
      title: _this.data.detail.goods_name,
      path: "/pages/sharing/goods/index?" + params
    };
  },

  /**
   * 显示分享选项
   */
  onClickShare(e) {
    let _this = this;
    // 记录formId
    App.saveFormId(e.detail.formId);
    _this.setData({
      'share.show': true
    });
  },

  /**
   * 关闭分享选项
   */
  onCloseShare() {
    let _this = this;
    _this.setData({
      'share.show': false
    });
  },

  /**
   * 点击生成商品海报
   */
  onClickShareItem(e) {
    let _this = this;
    if (e.detail.index === 0) {
      // 显示商品海报
      _this._showPoster();
    }
    _this.onCloseShare();
  },

  /**
   * 切换商品海报
   */
  onTogglePopup() {
    let _this = this;
    _this.setData({
      'share.showPopup': !_this.data.share.showPopup
    });
  },

  /**
   * 显示商品海报图
   */
  _showPoster() {
    let _this = this;
    wx.showLoading({
      title: '加载中',
    });
    App._get('sharing.goods/poster', {
      goods_id: _this.data.goods_id
    }, result => {
      _this.setData(result.data, () => {
        _this.onTogglePopup();
      });
    }, null, () => {
      wx.hideLoading();
    });
  },

  /**
   * 保存海报图片
   */
  onSavePoster(e) {
    let _this = this;
    // 记录formId
    App.saveFormId(e.detail.formId);
    wx.showLoading({
      title: '加载中',
    });
    // 下载海报图片
    wx.downloadFile({
      url: _this.data.qrcode,
      success(res) {
        wx.hideLoading();
        // 图片保存到本地
        wx.saveImageToPhotosAlbum({
          filePath: res.tempFilePath,
          success(data) {
            wx.showToast({
              title: '保存成功',
              icon: 'success',
              duration: 2000
            });
            // 关闭商品海报
            _this.onTogglePopup();
          },
          fail(err) {
            console.log(err.errMsg);
            if (err.errMsg === 'saveImageToPhotosAlbum:fail auth deny') {
              wx.showToast({
                title: "请允许访问相册后重试",
                icon: "none",
                duration: 1000
              });
              setTimeout(() => {
                wx.openSetting();
              }, 1000);
            }
          },
          complete(res) {
            console.log('complete');
            // wx.hideLoading();
          }
        })
      }
    })
  },

  /**
   * 确认购买弹窗
   */
  onToggleTrade(e) {
    let _this = this;
    if (typeof e === 'object') {
      // 记录formId
      e.detail.hasOwnProperty('formId') && App.saveFormId(e.detail.formId);
    }
    _this.setData({
      showBottomPopup: !_this.data.showBottomPopup
    });
  },

  /**
   * 显示拼团规则
   */
  onToggleRules(e) {
    // 记录formId
    App.saveFormId(e.detail.formId);
    // 显示拼团规则
    let _this = this;
    Dialog({
      title: '拼团规则',
      message: _this.data.setting.basic.rule_detail,
      selector: '#zan-base-dialog',
      buttons: [{
        text: '关闭',
        color: 'red',
        type: 'cash'
      }]
    });
  },

  /**
   * 返回主页
   */
  onNavigationHome(e) {
    App.saveFormId(e.detail.formId);
    wx.switchTab({
      url: '../../index/index',
    })
  },

  /**
   * 立即下单
   */
  onTriggerOrder(e) {
    console.log(App.saveFormId(e.detail.formId))
    let _this = this;
    // 设置当前购买类型
    _this.setData({
      order_type: e.currentTarget.dataset.type
    }, () => {
      _this.onToggleTrade();
    });
  },
  /**
   * 小于10的格式化函数
   */
  timeFormat(param) {
    return param < 10 ? '0' + param : param;
  },

  /**
   * 倒计时函数
   */
  countDown() {
    // 获取当前时间，同时得到活动结束时间数组
    let newTime = new Date().getTime();
    let endTimeList = this.data.actEndTimeList;
    let countDownArr = [];

    // 对结束时间进行处理渲染到页面
    endTimeList.forEach(o => {
      let endTime = new Date(util.format_date(o)).getTime();
      let obj = null;
      // 如果活动未结束，对时间进行处理
      if (endTime - newTime > 0) {
        let time = (endTime - newTime) / 1000;
        // 获取天、时、分、秒
        let day = parseInt(time / (60 * 60 * 24));
        let hou = parseInt(time % (60 * 60 * 24) / 3600);
        let min = parseInt(time % (60 * 60 * 24) % 3600 / 60);
        let sec = parseInt(time % (60 * 60 * 24) % 3600 % 60);
        obj = {
          day: day,
          hou: this.timeFormat(hou),
          min: this.timeFormat(min),
          sec: this.timeFormat(sec)
        }
      } else {
        //活动已结束，全部设置为'00'
        obj = {
          day: '00',
          hou: '00',
          min: '00',
          sec: '00'
        }
      }
      countDownArr.push(obj);
    })
    // 渲染，然后每隔一秒执行一次倒计时函数
    this.setData({
      countDownList: countDownArr
    });
    setTimeout(this.countDown, 1000);
  },

  /**
   * 跳转到拼单页面
   */
  onTargetActive(e) {
    wx.navigateTo({
      url: '../active/index?active_id=' + e.currentTarget.dataset.id,
    })
  },
})