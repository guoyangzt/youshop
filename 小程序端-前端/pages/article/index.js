const App = getApp();

Page({
  /**
   * 页面的初始数据
   */
  data: {
    // 分类列表
    categoryList: [],
    // 文章列表
    articleList: [],
    // 当前的分类id (0则代表首页)
    category_id: 0,

    scrollHeight: null,

    no_more: false, // 没有更多数据
    isLoading: true, // 是否正在加载中
    page: 1, // 当前页码
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    let _this = this;
    // 设置文章列表高度
    _this.setListHeight();
    // Api：获取文章首页
    _this.getIndexData();
  },

  /**
   * Api：获取文章列表
   */
  getIndexData() {
    let _this = this;
    // 获取文章首页
    App._get('article/index', {}, function(result) {
      _this.setData({
        categoryList: result.data.categoryList
      });
    });
    // Api：获取文章列表
    _this.getArticleList();
  },

  /**
   * Api：切换导航栏
   */
  onSwitchTab: function(e) {
    let _this = this;
    // 第一步：切换当前的分类id
    _this.setData({
      category_id: e.currentTarget.dataset.id,
      articleList: {},
      page: 1,
      no_more: false,
      isLoading: true,
    });
    // 第二步：更新当前的文章列表
    _this.getArticleList();
  },

  /**
   * Api：获取文章列表
   */
  getArticleList(isPage, page) {
    let _this = this;
    App._get('article/lists', {
      page: page || 1,
      category_id: _this.data.category_id
    }, function(result) {
      let resList = result.data.list,
        dataList = _this.data.articleList;
      if (isPage == true) {
        _this.setData({
          'articleList.data': dataList.data.concat(resList.data),
          isLoading: false,
        });
      } else {
        _this.setData({
          articleList: resList,
          isLoading: false,
        });
      }
    });
  },

  /**
   * 跳转文章详情页
   */
  onTargetDetail(e) {
    wx.navigateTo({
      url: './detail/index?article_id=' + e.currentTarget.dataset.id
    });
  },

  /**
   * 分享当前页面
   */
  onShareAppMessage() {
    return {
      title: '文章首页',
      path: "/pages/article/index?" + App.getShareUrlParams()
    };
  },

  /**
   * 下拉到底加载数据
   */
  bindDownLoad() {
    // 已经是最后一页
    if (this.data.page >= this.data.articleList.last_page) {
      this.setData({
        no_more: true
      });
      return false;
    }
    // 加载下一页列表
    this.getArticleList(true, ++this.data.page);
  },

  /**
   * 设置文章列表高度
   */
  setListHeight() {
    let systemInfo = wx.getSystemInfoSync(),
      rpx = systemInfo.windowWidth / 750, // 计算rpx
      tapHeight = Math.floor(rpx * 98), // tap高度
      scrollHeight = systemInfo.windowHeight - tapHeight; // swiper高度
    console.log(
      systemInfo.windowHeight
    );
    this.setData({
      scrollHeight
    });
  },
})