const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    // 优惠券列表
    list: [],

    // show
    notcont: false
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    // 当前页面参数
    this.data.options = options;
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
    App._get('coupon/lists', {}, function(result) {
      _this.setData({
        list: result.data.list,
        notcont: !result.data.list.length
      });
    });
  },

  /**
   * 立即领取
   */
  receive: function(e) {
    let _this = this,
      couponId = e.currentTarget.dataset.couponId;

    App._post_form('user.coupon/receive', {
      coupon_id: couponId
    }, function(result) {
      App.showSuccess(result.msg);
      // 获取优惠券列表
      _this.getCouponList();
    });

  },


});