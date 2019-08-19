const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    // 选项卡标示
    dataType: 'not_use',

    // 列表高度
    swiperHeight: 0,

    // 优惠券列表
    list: [],

    // show
    notcont: false
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    // 设置swiper的高度
    this.setSwiperHeight();
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {
    // 获取优惠券列表
    this.getCouponList();
  },

  /**
   * 获取优惠券列表
   */
  getCouponList: function() {
    let _this = this;
    App._get('user.coupon/lists', {
      data_type: _this.data.dataType
    }, function(result) {
      _this.setData({
        list: result.data.list,
        notcont: !result.data.list.length
      });
    });
  },

  /**
   * 设置swiper的高度
   */
  setSwiperHeight: function() {
    // 获取系统信息(拿到屏幕宽度)
    let systemInfo = wx.getSystemInfoSync(),
      rpx = systemInfo.windowWidth / 750, // 计算rpx
      tapHeight = Math.floor(rpx * 80) + 1, // tap高度
      swiperHeight = systemInfo.windowHeight - tapHeight; // swiper高度
    this.setData({
      swiperHeight
    });
  },

  /** 
   * 点击tab切换 
   */
  swichNav: function(e) {
    let _this = this;
    _this.setData({
      list: {},
      dataType: e.target.dataset.current
    }, function() {
      // 获取优惠券列表
      _this.getCouponList();
    });
  },

});