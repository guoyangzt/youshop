const App = getApp();

// 枚举类：充值类型
const RechargeTypeEnum = require('../../../utils/enum/recharge/order/RechargeType.js');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    userInfo: {}, // 用户信息
    setting: {}, // 充值设置

    // recharge_type: '', // 充值类型
    selectedPlanId: 0, // 当前选中的套餐id
    inputValue: '', // 自定义金额

    disabled: false, //按钮禁用
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad(options) {
    let _this = this;
    // 获取充值中心数据
    _this.getRechargeIndex();
  },

  /**
   * 获取充值中心数据
   */
  getRechargeIndex() {
    let _this = this;
    App._get('recharge/index', {}, function(result) {
      _this.setData(result.data);
    });
  },

  /**
   * 选择充值套餐
   */
  onSelectPlan(e) {
    let _this = this;
    _this.setData({
      selectedPlanId: e.currentTarget.dataset.id,
      inputValue: ''
    });
  },

  /**
   * 绑定金额输入框
   */
  bindMoneyInput(e) {
    let _this = this;
    _this.setData({
      inputValue: e.detail.value,
      selectedPlanId: 0
    })
  },

  /**
   * 立即充值
   */
  onSubmit(e) {
    let _this = this;

    // 记录formid
    App.saveFormId(e.detail.formId);

    // 按钮禁用
    _this.setData({
      disabled: true
    });
    // 提交到后端
    App._post_form('recharge/submit', {
      planId: _this.data.selectedPlanId,
      customMoney: _this.data.inputValue
    }, (result) => {

      // 发起微信支付
      App.wxPayment({
        payment: result.data.payment,
        success() {
          App.showSuccess(result.msg.success, () => {
            wx.navigateBack();
          });
        },
        fail(res) {
          App.showError(result.msg.error);
        },
        complete(res) {

        }
      });

    }, false, () => {
      // 解除禁用
      _this.setData({
        disabled: false
      });
    });
  },



})