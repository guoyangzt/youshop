const App = getApp();

// 枚举类：发货方式
import DeliveryTypeEnum from '../../utils/enum/DeliveryType.js';

// 枚举类：支付方式
import PayTypeEnum from '../../utils/enum/order/PayType'


Page({

  /**
   * 页面的初始数据
   */
  data: {

    // 配送方式
    DeliveryTypeEnum,

    // 支付方式
    PayTypeEnum,

    order_id: null,
    order: {},
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad(options) {
    let _this = this;
    _this.data.order_id = options.order_id;
    // 获取订单详情
    _this.getOrderDetail(options.order_id);
  },

  /**
   * 获取订单详情
   */
  getOrderDetail(order_id) {
    let _this = this;
    App._get('user.order/detail', {
      order_id
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
      url: '../goods/index?goods_id=' + goods_id
    });
  },

  /**
   * 取消订单
   */
  cancelOrder(e) {
    let _this = this;
    let order_id = _this.data.order_id;
    wx.showModal({
      title: "提示",
      content: "确认取消订单？",
      success(o) {
        if (o.confirm) {
          App._post_form('user.order/cancel', {
            order_id
          }, result => {
            wx.navigateBack();
          });
        }
      }
    });
  },

  /**
   * 确认收货
   */
  receipt(e) {
    let _this = this;
    let order_id = _this.data.order_id;
    wx.showModal({
      title: "提示",
      content: "确认收到商品？",
      success(o) {
        if (o.confirm) {
          App._post_form('user.order/receipt', {
            order_id
          }, result => {
            _this.getOrderDetail(order_id);
          });
        }
      }
    });
  },

  /**
   * 申请售后
   */
  onApplyRefund(e) {
    wx.navigateTo({
      url: './refund/apply/apply?order_goods_id=' + e.currentTarget.dataset.id,
    })
  },

  /**
   * 跳转到门店详情
   */
  onTargetShop(e) {
    wx.navigateTo({
      url: '../shop/detail/index?shop_id=' + e.currentTarget.dataset.id,
    })
  },

  /**
   * 点击付款按钮
   */
  onPayOrder(e) {
    let _this = this;
    // 显示支付方式弹窗
    _this.onTogglePayPopup();
  },

  /**
   * 选择支付方式
   */
  onSelectPayType(e) {
    let _this = this;
    // 记录formId
    App.saveFormId(e.detail.formId);
    // 隐藏支付方式弹窗
    _this.onTogglePayPopup();
    if (!_this.data.showPayPopup) {
      // 发起付款请求
      _this.payment(e.currentTarget.dataset.value);
    }
  },

  /**
   * 显示/隐藏支付方式弹窗
   */
  onTogglePayPopup() {
    this.setData({
      showPayPopup: !this.data.showPayPopup
    });
  },

  /**
   * 发起付款请求
   */
  payment(payType) {
    let _this = this,
      orderId = _this.data.order_id;
    // 显示loading
    wx.showLoading({
      title: '正在处理...',
    });
    App._post_form('user.order/pay', {
      order_id: orderId,
      payType
    }, result => {
      if (result.code === -10) {
        App.showError(result.msg);
        return false;
      }

      // 发起微信支付
      if (result.data.pay_type == PayTypeEnum.WECHAT.value) {
        App.wxPayment({
          payment: result.data.payment,
          success() {
            _this.getOrderDetail(orderId);
          },
          fail() {
            App.showError(result.msg.success);
          },
        });
      }

      // 余额支付
      if (result.data.pay_type == PayTypeEnum.BALANCE.value) {
        App.showSuccess(result.msg.success, () => {
          _this.getOrderDetail(orderId);
        });
      }

    }, null, () => {
      wx.hideLoading();
    });
  },

});