const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {
    // 获取推广二维码
    this.getPoster();
  },

  /**
   * 获取推广二维码
   */
  getPoster: function() {
    let _this = this;
    wx.showLoading({
      title: '加载中',
    });
    App._get('user.dealer.qrcode/poster', {}, function(result) {
      // 设置当前页面标题
      wx.setNavigationBarTitle({
        title: result.data.words.qrcode.title.value
      })
      _this.setData(result.data);
    }, null, function() {
      wx.hideLoading();
    });
  },

  previewImage: function() {
    wx.previewImage({
      current: this.data.qrcode,
      urls: [this.data.qrcode]
    })
  },

})