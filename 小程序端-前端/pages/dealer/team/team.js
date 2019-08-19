const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    isLoading: true,
    dataType: 1,
    page: 1,
    no_more: false,
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
    // 获取我的团队列表
    this.getTeamList();
  },

  /**
   * 获取我的团队列表
   */
  getTeamList: function(isNextPage, page) {
    let _this = this;
    App._get('user.dealer.team/lists', {
      level: _this.data.dataType,
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
      title: data.words.team.title.value
    });
    // 团队总人数
    data['team_total'] = data.dealer.first_num;
    // 导航栏数据
    data['tabList'] = [{
      value: 1,
      text: data.words.team.words.first.value,
      total: data.dealer.first_num
    }];
    if (data.setting.level >= 2) {
      data['tabList'].push({
        value: 2,
        text: data.words.team.words.second.value,
        total: data.dealer.second_num
      });
      data['team_total'] += data.dealer.second_num;
    }
    if (data.setting.level == 3) {
      data['tabList'].push({
        value: 3,
        text: data.words.team.words.third.value,
        total: data.dealer.third_num
      });
      data['team_total'] += data.dealer.third_num;
    }
    // 设置swiper的高度
    this.setSwiperHeight(data.setting.level > 1);
    return data;
  },

  /**
   * 下拉到底加载数据
   */
  triggerDownLoad: function() {
    // console.log(this.data.list);
    // 已经是最后一页
    if (this.data.page >= this.data.list.last_page) {
      this.setData({
        no_more: true
      });
      return false;
    }
    this.getTeamList(true, ++this.data.page);
  },

  /**
   * 设置swiper的高度
   */
  setSwiperHeight: function(isTap) {
    // 获取系统信息(拿到屏幕宽度)
    let systemInfo = wx.getSystemInfoSync(),
      rpx = systemInfo.windowWidth / 750, // 计算rpx
      tapHeight = isTap ? Math.floor(rpx * 82) : 0, // tap高度
      peopleHeight = Math.floor(rpx * 65), // people高度
      swiperHeight = systemInfo.windowHeight - tapHeight - peopleHeight; // swiper高度
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
      // 获取我的团队列表
      _this.getTeamList();
    });
  },

})