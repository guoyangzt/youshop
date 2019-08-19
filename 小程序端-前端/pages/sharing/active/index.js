const App = getApp();
const Sharing = require('../../../utils/extend/sharing.js');
const Dialog = require('../../../components/dialog/dialog');

// 工具类
const util = require('../../../utils/util.js');

// 记录规格的数组
const goodsSpecArr = [];

Page({
  data: {
    detail: {}, // 拼单详情
    goodsList: [], // 更多拼团列表
    setting: {}, // 拼团设置
    is_join: false, // 当前用户是否已参团
    is_creator: false, // 当前是否为创建者(团长)

    goods_price: 0, // 商品价格
    line_price: 0, // 划线价格
    stock_num: 0, // 库存数量

    goods_num: 1, // 商品数量
    goods_sku_id: 0, // 规格id
    goodsMultiSpec: {}, // 多规格信息

    countDownList: [],
    actEndTimeList: []
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad(option) {
    let _this = this;

    // 获取拼团详情
    _this.getActiveDetail(option.active_id);
    // 获取拼团设置
    _this.getSetting();
  },

  /**
   * 获取拼团详情
   */
  getActiveDetail(active_id) {
    let _this = this;
    App._get('sharing.active/detail', {
      active_id
    }, result => {
      // 创建当前页面数据
      _this.createPageData(result.data);
    });
  },

  /**
   * 创建页面数据
   */
  createPageData(data) {
    let _this = this;
    // 商品详情
    let goodsDetail = data.goods;
    // 当前用户是否已参团
    data['is_join'] = _this.checkUserIsJoin(data.detail.users);
    console.log(data['is_join']);
    // 当前用户是否为创建者
    data['is_creator'] = !!(data.detail.creator_id == App.getUserId())
    // 拼团结束时间
    data['actEndTimeList'] = [data.detail.end_time.text];

    // 商品价格/划线价/库存
    data.goods_sku_id = goodsDetail.goods_sku.spec_sku_id;
    data.sharing_price = goodsDetail.goods_sku.sharing_price;
    data.goods_price = goodsDetail.goods_sku.goods_price;
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
      data.goodsMultiSpec = _this._initGoodsDetailData(goodsDetail.goods_multi_spec);
    }
    // 赋值页面数据
    _this.setData(data);
    // 执行倒计时函数
    _this.onCountDown();
  },

  /**
   * 初始化商品多规格
   */
  _initGoodsDetailData(data) {
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
    let spec_list = _this.data.goodsMultiSpec.spec_list,
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
        skuCoverImage: skuItem.form.image_id > 0 ? skuItem.form.image_path : _this.data.goods.goods_image
      });
    }
  },

  /**
   * 验证当前用户是否已参团
   */
  checkUserIsJoin(users) {
    let user_id = App.getUserId();
    if (!user_id) {
      return false;
    }
    let isJoin = false;
    users.forEach((item) => {
      user_id == item.user_id && (isJoin = true);
    });
    return isJoin;
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
   * 小于10的格式化函数
   */
  timeFormat(param) {
    return param < 10 ? '0' + param : param;
  },

  /**
   * 倒计时函数
   */
  onCountDown() {
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
      } else { //活动已结束，全部设置为'00'
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
    })
    setTimeout(this.onCountDown, 1000);
  },

  /**
   * 查看拼团规则
   */
  onTargetRules() {
    wx.navigateTo({
      url: '../rules/index',
    })
  },

  /**
   * 显示拼团规则
   */
  onToggleRules() {
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
   * 跳转商品详情页
   */
  onTargetGoods(e) {
    let goodsId = e.currentTarget.dataset.id > 0 ? e.currentTarget.dataset.id : this.data.detail.goods_id;
    wx.navigateTo({
      url: '../goods/index?goods_id=' + goodsId,
    });
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
   * 加入购物车and立即购买
   */
  onCheckout(e) {
    let _this = this;
    // 立即购买
    wx.navigateTo({
      url: '../checkout/index?' + util.urlEncode({
        order_type: 20,
        active_id: _this.data.detail.active_id,
        goods_id: _this.data.goods.goods_id,
        goods_num: _this.data.goods_num,
        goods_sku_id: _this.data.goods_sku_id,
      })
    });
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
   * 分享当前页面
   */
  onShareAppMessage() {
    let _this = this;
    // 构建页面参数
    let params = App.getShareUrlParams({
      'active_id': _this.data.detail.active_id
    });
    return {
      title: _this.data.goods.goods_name,
      path: "/pages/sharing/active/index?" + params
    };
  },

  /**
   * 确认购买弹窗
   */
  onToggleTrade(e) {
    this.setData({
      showBottomPopup: !this.data.showBottomPopup
    });
  },

  /**
   * 立即下单
   */
  onTriggerOrder(e) {
    let _this = this;
    _this.onToggleTrade();
  },

  /**
   * 跳转到拼团首页
   */
  onTargetIndex(e) {
    wx.navigateTo({
      url: '../index/index',
    })
  },

})