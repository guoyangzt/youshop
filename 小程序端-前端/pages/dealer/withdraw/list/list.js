const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    applyStatus: [],
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
    // 获取提现明细列表
    this.getWithdrawList();
  },

  /**
   * 获取提现明细列表
   */
  getWithdrawList: function(isNextPage, page) {
    let _this = this;
    App._get('user.dealer.withdraw/lists', {
      status: _this.data.dataType,
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
      title: data.words.withdraw_list.title.value
    })
    // 导航栏数据
    data['tabList'] = [{
        value: -1,
        text: data.words.withdraw_list.words.all.value,
      }, {
        value: 10,
        text: data.words.withdraw_list.words.apply_10.value,
      }, {
        value: 20,
        text: data.words.withdraw_list.words.apply_20.value,
      },
      {
        value: 40,
        text: data.words.withdraw_list.words.apply_40.value,
      },
      {
        value: 30,
        text: data.words.withdraw_list.words.apply_30.value,
      }
    ];
    // 审核状态
    data['applyStatus'] = {};
    data['tabList'].forEach(function(item) {
      data['applyStatus'][item.value] = item.text;
    });
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
    let _this = this,
      current = e.target.dataset.current;
    if (_this.data.dataType == current) {
      return false;
    }
    _this.setData({
      dataType: current,
      list: {},
      page: 1,
      no_more: false,
      isLoading: true,
    }, function() {
      // 获取提现明细列表
      _this.getWithdrawList();
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
    // 获取提现明细列表
    this.getWithdrawList(true, ++this.data.page);
  },

  /**
   * 查看驳回原因
   */
  triggerReasonMsg: function(e) {
    wx.showModal({
      // title: this.data.applyStatus['30'] + '原因',
      title: '友情提示',
      content: e.currentTarget.dataset.reason,
      showCancel: false
    });
  },

})