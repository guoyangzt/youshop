const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    options: {},

    express: {},
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    // 获取物流动态
    this.getExpressDynamic(options.order_id);
  },

  /**
   * 获取物流动态
   */
  getExpressDynamic: function(order_id) {
    let _this = this;
    App._get('user.order/express', {
        order_id
      }, function(result) {
        _this.setData(result.data);
      },
      function() {
        wx.navigateBack();
      });
  },

})