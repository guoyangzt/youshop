const App = getApp();

Page({

  data: {
    // 页面标题
    page: {},

    // 页面元素
    items: {},
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    let _this = this;
    // 页面id
    _this.data.page_id = options.page_id;
    // 加载页面数据
    _this.getPageData();
  },

  /**
   * 加载页面数据
   */
  getPageData: function(callback) {
    let _this = this;
    App._get('page/index', {
      page_id: _this.data.page_id
    }, function(result) {
      // 设置顶部导航栏栏
      _this.setPageBar(result.data.page);
      _this.setData(result.data);
      // 回调函数
      typeof callback === 'function' && callback();
    });
  },

  /**
   * 设置顶部导航栏
   */
  setPageBar: function(page) {
    // 设置页面标题
    wx.setNavigationBarTitle({
      title: page.params.title
    });
    // 设置navbar标题、颜色
    wx.setNavigationBarColor({
      frontColor: page.style.titleTextColor === 'white' ? '#ffffff' : '#000000',
      backgroundColor: page.style.titleBackgroundColor
    })
  },

  /**
   * 分享当前页面
   */
  onShareAppMessage: function() {
    let _this = this;
    // 构建页面参数
    let params = App.getShareUrlParams({
      'page_id': _this.data.page_id
    });
    return {
      title: _this.data.page.params.share_title,
      path: "/pages/custom/index?" + params
    };
  },

  /**
   * 下拉刷新
   */
  onPullDownRefresh: function() {
    // 获取首页数据
    this.getPageData(function() {
      wx.stopPullDownRefresh();
    });
  }

});