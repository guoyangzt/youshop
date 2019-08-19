const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    list: [], // 充值记录
    isLoading: true, // 是否正在加载中
    page: 1, // 当前页码
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad(options) {
    let _this = this;
    // 获取账单详情列表
    _this.getRechargeLog();
    // 设置列表容器高度
    _this.setListHeight();
  },

  /**
   * 获取账单详情列表
   */
  getRechargeLog(isPage, page) {
    let _this = this;
    App._get('balance.log/lists', {
      page: page || 1
    }, function(result) {
      let resList = result.data.list,
        dataList = _this.data.list;
      if (isPage == true) {
        _this.setData({
          'list.data': dataList.data.concat(resList.data),
          isLoading: false,
        });
      } else {
        _this.setData({
          list: resList,
          isLoading: false,
        });
      }
    });
  },

  /**
   * 设置列表容器高度
   */
  setListHeight: function() {
    let _this = this,
      systemInfo = wx.getSystemInfoSync();
    _this.setData({
      scrollHeight: systemInfo.windowHeight * 0.98
    });
  },

  /**
   * 下拉到底加载数据
   */
  bindDownLoad() {
    let _this = this;
    // 已经是最后一页
    if (_this.data.page >= _this.data.list.last_page) {
      _this.setData({
        no_more: true
      });
      return false;
    }
    // 加载下一页列表
    _this.getRechargeLog(true, ++_this.data.page);
  },

})