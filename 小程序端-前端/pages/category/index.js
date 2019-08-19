const App = getApp();

Page({
  data: {
    // 搜索框样式
    searchColor: "rgba(0,0,0,0.4)",
    searchSize: "15",
    searchName: "搜索商品",

    // 列表高度
    scrollHeight: 0,

    // 一级分类：指针
    curNav: true,
    curIndex: 0,

    // 分类列表
    list: [],

    // show
    notcont: false
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad() {
    let _this = this;
    // 设置分类列表高度
    _this.setListHeight();
    // 获取分类列表
    _this.getCategoryList();
  },

  /**
   * 设置分类列表高度
   */
  setListHeight() {
    let _this = this;
    wx.getSystemInfo({
      success: function(res) {
        _this.setData({
          scrollHeight: res.windowHeight - 47,
        });
      }
    });
  },

  /**
   * 获取分类列表
   */
  getCategoryList() {
    let _this = this;
    App._get('category/index', {}, result => {
      let data = result.data;
      _this.setData({
        list: data.list,
        templet: data.templet,
        curNav: data.list.length > 0 ? data.list[0].category_id : true,
        notcont: !data.list.length
      });
    });
  },

  /**
   * 一级分类：选中分类
   */
  selectNav(e) {
    let _this = this;
    _this.setData({
      curNav: e.target.dataset.id,
      curIndex: parseInt(e.target.dataset.index),
      scrollTop: 0
    });
  },

  /**
   * 设置分享内容
   */
  onShareAppMessage() {
    let _this = this;
    return {
      title: _this.data.templet.share_title,
      path: '/pages/category/index?' + App.getShareUrlParams()
    };
  }

});