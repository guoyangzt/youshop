const App = getApp();

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
    itemStyle: Object,
    dataList: Object
  },

  /**
   * 组件的方法列表
   * 更新属性和数据的方法与更新页面数据的方法类似
   */
  methods: {

    /**
     * 跳转门店详情页
     */
    _onTargetDetail(e) {
      // 记录formid
      App.saveFormId(e.detail.formId);
      wx.navigateTo({
        url: '/pages/shop/detail/index?shop_id=' + e.detail.target.dataset.id,
      });
    },
  }

})