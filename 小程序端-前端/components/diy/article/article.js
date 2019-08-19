Component({

  options: {
    addGlobalClass: true,
  },

  /**
   * 组件的属性列表
   * 用于组件自定义设置
   */
  properties: {
    itemIndex: String,
    // itemStyle: Object,
    params: Object,
    dataList: Object
  },

  /**
   * 组件的方法列表
   * 更新属性和数据的方法与更新页面数据的方法类似
   */
  methods: {

    /**
     * 跳转文章详情页
     */
    onTargetDetail(e) {
      wx.navigateTo({
        url: '/pages/article/detail/index?article_id=' + e.currentTarget.dataset.id
      });
    },

  }

})