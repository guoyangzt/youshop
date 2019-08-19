const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    userInfo: {},
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad(options) {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow() {
    let _this = this;
    // 获取当前用户信息
    _this.getUserDetail();
  },

  /**
   * 获取当前用户信息
   */
  getUserDetail: function() {
    let _this = this;
    App._get('user/detail', {}, function(result) {
      _this.setData(result.data);
    });
  },

  /**
   * 跳转充值页面
   */
  onTargetRecharge(e) {
    // 记录formId
    App.saveFormId(e.detail.formId);
    wx.navigateTo({
      url: '../recharge/index'
    })
  },

  /**
   * 跳转充值记录页面
   */
  onTargetRechargeOrder(e) {
    // 记录formId
    App.saveFormId(e.detail.formId);
    wx.navigateTo({
      url: '../recharge/order/index'
    })
  },

  /**
   * 跳转账单详情页面
   */
  onTargetBalanceLog(e) {
    // 记录formId
    App.saveFormId(e.detail.formId);
    wx.navigateTo({
      url: '../wallet/balance/log'
    })
  },

})