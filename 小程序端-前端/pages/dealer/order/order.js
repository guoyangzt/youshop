const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    isLoading: true,
    dataType: -1,
    page: 1,
    no_more: false,
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
    // 获取订单列表
    this.getOrderList();
  },

  /**
   * 获取订单列表
   */
  getOrderList: function(isNextPage, page) {
    let _this = this;
    App._get('user.dealer.order/lists', {
      settled: _this.data.dataType,
      page: page || 1,
    }, function(result) {
      // 创建页面数据
      _this.setData(_this.createData(result.data, isNextPage));
    });
  },

  /**
   * 创建页面数据
   */
  createData: function(data, isNextPage) {
    data['isLoading'] = false;
    // 列表数据
    let dataList = this.data.list;
    if (isNextPage == true && (typeof dataList !== 'undefined')) {
      data.list.data = dataList.data.concat(data.list.data)
    }
    // 设置当前页面标题
    wx.setNavigationBarTitle({
      title: data.words.order.title.value
    })
    // 当前用户id
    data['user_id'] = App.getUserId();
    // 导航栏数据
    data['tabList'] = [{
      value: -1,
      text: data.words.order.words.all.value,
    }, {
      value: 0,
      text: data.words.order.words.unsettled.value,
    }, {
      value: 1,
      text: data.words.order.words.settled.value,
    }];
    return data;
  },

  /**
   * 设置swiper的高度
   */
  setSwiperHeight: function() {
    // 获取系统信息(拿到屏幕宽度)
    let systemInfo = wx.getSystemInfoSync(),
      rpx = systemInfo.windowWidth / 750, // 计算rpx
      tapHeight = Math.floor(rpx * 82), // tap高度
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
      dataType: e.target.dataset.current,
      list: {},
      page: 1,
      no_more: false,
      isLoading: true,
    }, function() {
      // 获取订单列表
      _this.getOrderList();
    });
  },

  /**
   * 下拉到底加载数据
   */
  triggerDownLoad: function() {
    // 已经是最后一页
    if (this.data.page >= this.data.list.last_page) {
      this.setData({
        no_more: true
      });
      return false;
    }
    // 获取订单列表
    this.getOrderList(true, ++this.data.page);
  },

})