const App = getApp();

// 枚举类：发货方式
const DeliveryTypeEnum = require('../../../utils/enum/DeliveryType.js');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    // 当前页面参数
    options: {},
    // 配送方式
    deliverys: DeliveryTypeEnum,
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad(options) {
    let _this = this,
      scene = App.getSceneData(options);
    // 记录options
    _this.setData({
      options: scene
    });
    // 获取订单详情
    _this.getOrderDetail();
  },

  /**
   * 获取订单详情
   */
  getOrderDetail() {
    let _this = this;
    App._get('shop.order/detail', {
      order_id: _this.data.options.oid,
      order_type: _this.data.options.oty
    }, result => {
      _this.setData(result.data);
    });
  },

  /**
   * 跳转到商品详情
   */
  onTargetGoods(e) {
    let goods_id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../../goods/index?goods_id=' + goods_id
    });
  },

  /**
   * 跳转到门店详情
   */
  onTargetShop(e) {
    wx.navigateTo({
      url: '../../shop/detail/index?shop_id=' + e.currentTarget.dataset.id,
    })
  },

  /**
   * 确认核销
   */
  onSubmitExtract() {
    let _this = this;
    wx.showModal({
      title: "提示",
      content: "确认核销该订单吗？",
      success(o) {
        if (o.confirm) {
          App._post_form('shop.order/extract', {
            order_id: _this.data.options.oid,
            order_type: _this.data.options.oty
          }, result => {
            App.showSuccess(result.msg, () => {
              // 获取订单详情
              _this.getOrderDetail();
            });
          });
        }
      }
    });
  },

})