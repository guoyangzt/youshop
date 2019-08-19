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
    params: Object,
    dataList: Object
  },

  methods: {

    /**
     * 领取优惠券
     */
    receiveTap: function(e) {
      let _this = this,
        dataset = e.currentTarget.dataset;
      if (!dataset.state) {
        return false;
      }
      App._post_form('user.coupon/receive', {
        coupon_id: dataset.couponId
      }, function(result) {
        App.showSuccess(result.msg);
        _this.setData({
          ['dataList[' + dataset.index + '].state']: {
            value: 0,
            text: '已领取'
          }
        });
      });
    }

  }

})