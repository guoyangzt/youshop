const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    // 售后单id
    order_refund_id: null,

    // 订单商品详情
    detail: {},

    // 物流公司索引
    expressIndex: -1,
  },

  disable: false,

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    // 记录页面参数
    this.data.order_refund_id = options.order_refund_id;

    // 获取售后单详情
    this.getRefundDetail();
  },

  /**
   * 获取售后单详情
   */
  getRefundDetail: function() {
    let _this = this;
    App._get('sharing.refund/detail', {
      order_refund_id: this.data.order_refund_id
    }, function(result) {
      _this.setData(result.data);
    });
  },

  /**
   * 跳转商品详情
   */
  onGoodsDetail: function (e) {
    // 记录formId
    App.saveFormId(e.detail.formId);
    wx.navigateTo({
      url: '../../../goods/index?goods_id=' + e.detail.target.dataset.id
    });
  },

  /**
   * 凭证图片预览
   */
  previewImages: function(e) {
    let imageUrls = [];
    this.data.detail.image.forEach(function(item) {
      imageUrls.push(item.file_path);
    });
    wx.previewImage({
      current: imageUrls[e.target.dataset.index],
      urls: imageUrls
    })
  },

  /**
   * 选择物流公司 picker
   */
  onExpressChange: function(e) {
    this.setData({
      expressIndex: e.detail.value
    })
  },

  /**
   * 表单提交
   */
  onSubmit: function(e) {
    let _this = this,
      values = e.detail.value;

    // 记录formId
    App.saveFormId(e.detail.formId);

    // 判断是否重复提交
    if (_this.disable === true) {
      return false;
    }

    // 表单提交按钮设为禁用 (防止重复提交)
    _this.disable = true;

    wx.showLoading({
      title: '正在处理...',
      mask: true
    });

    // 提交到后端
    values['order_refund_id'] = _this.data.order_refund_id;
    App._post_form('sharing.refund/delivery', values, function(result) {
      App.showSuccess(result.msg, function() {
        // 获取售后单详情
        _this.getRefundDetail();
      });
    }, false, function() {
      wx.hideLoading();
      // 解除禁用
      _this.disable = false;
    });

  },

})