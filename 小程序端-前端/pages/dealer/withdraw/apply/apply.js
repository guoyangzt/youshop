const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    isData: false,

    words: {},
    payment: 20,
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
    // 获取分销商提现信息
    this.getDealerWithdraw();
  },

  /**
   * 获取分销商提现信息
   */
  getDealerWithdraw: function() {
    let _this = this;
    App._get('user.dealer/withdraw', {}, function(result) {
      let data = result.data;
      data.isData = true;
      // 设置当前页面标题
      wx.setNavigationBarTitle({
        title: data.words.withdraw_apply.title.value
      });
      //  默认提现方式
      data['payment'] = data.settlement.pay_type[0];
      _this.setData(data);
    });
  },

  /**
   * 提交申请 
   */
  formSubmit: function(e) {
    let _this = this,
      values = e.detail.value,
      words = _this.data.words.withdraw_apply.words;

    // 记录formId
    App.saveFormId(e.detail.formId);

    // 验证可提现佣金
    if (_this.data.dealer.money <= 0) {
      App.showError('当前没有' + words.capital.value);
      return false;
    }
    // 验证提现金额
    if (!values.money || values.money.length < 1) {
      App.showError('请填写' + words.money.value);
      return false;
    }
    // 按钮禁用
    _this.setData({
      disabled: true
    });
    // 提现方式
    values['pay_type'] = _this.data.payment;
    // 数据提交
    App._post_form('user.dealer.withdraw/submit', {
      data: JSON.stringify(values)
    }, function(result) {
      // 提交成功
      // console.log(result);
      App.showError(result.msg, function() {
        wx.navigateTo({
          url: '../list/list',
        })
      });
    }, null, function() {
      // 解除按钮禁用
      _this.setData({
        disabled: false
      });
    });
  },

  /**
   * 切换提现方式
   */
  toggleChecked: function(e) {
    this.setData({
      payment: e.currentTarget.dataset.payment
    });
  },

})