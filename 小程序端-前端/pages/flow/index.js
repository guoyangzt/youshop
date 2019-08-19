const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    goods_list: [], // 商品列表
    // order_total_num: 0,
    // 商品总金额
    // order_total_price: 0,

    action: 'complete',
    checkedAll: false,

    // 商品总价格
    cartTotalPrice: '0.00'
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
    // 获取购物车列表
    this.getCartList();
  },

  /**
   * 获取购物车列表
   */
  getCartList: function() {
    let _this = this;
    App._get('cart/lists', {}, function(result) {
      _this.setData({
        goods_list: result.data.goods_list,
        order_total_price: result.data.order_total_price,
        action: 'complete',
        checkedAll: false,
        cartTotalPrice: '0.00'
      });
    });
  },

  /**
   * 选择框选中
   */
  radioChecked: function(e) {
    let _this = this,
      index = e.currentTarget.dataset.index,
      checked = !_this.data.goods_list[index].checked;
    _this.setData({
      ['goods_list[' + index + '].checked']: checked
    }, function() {
      // 更新购物车已选商品总价格
      _this.updateTotalPrice();
    });
  },

  /**
   * 选择框全选
   */
  radioCheckedAll: function(e) {
    let _this = this,
      goodsList = this.data.goods_list;
    goodsList.forEach(function(item) {
      item.checked = !_this.data.checkedAll;
    });
    _this.setData({
      goods_list: goodsList,
      checkedAll: !_this.data.checkedAll
    }, function() {
      // 更新购物车已选商品总价格
      _this.updateTotalPrice();
    });
  },

  /**
   * 切换编辑/完成
   */
  switchAction: function(e) {
    let _this = this;
    _this.setData({
      action: e.currentTarget.dataset.action
    });
  },

  /**
   * 删除商品
   */
  deleteHandle: function() {
    let _this = this,
      cartIds = _this.getCheckedIds();
    if (!cartIds.length) {
      App.showError('您还没有选择商品');
      return false;
    }
    wx.showModal({
      title: "提示",
      content: "您确定要移除选择的商品吗?",
      success: function(e) {
        e.confirm && App._post_form('cart/delete', {
          goods_sku_id: cartIds
        }, function(result) {
          // 获取购物车列表
          _this.getCartList();
        });
      }
    });
  },

  /**
   * 获取已选中的商品
   */
  getCheckedIds: function() {
    let arrIds = [];
    this.data.goods_list.forEach(function(item) {
      if (item.checked === true) {
        arrIds.push(item.goods_id + '_' + item.goods_sku_id);
      }
    });
    return arrIds;
  },

  /**
   * 更新购物车已选商品总价格
   */
  updateTotalPrice: function() {
    let _this = this;
    let cartTotalPrice = 0;
    _this.data.goods_list.forEach(function(item) {
      if (item.checked === true) {
        cartTotalPrice = _this.mathadd(cartTotalPrice, item.total_price);
      }
    });
    _this.setData({
      cartTotalPrice: Number(cartTotalPrice).toFixed(2)
    });
  },

  /**
   * 递增指定的商品数量
   */
  addCount: function(e) {
    let _this = this,
      index = e.currentTarget.dataset.index,
      goodsSkuId = e.currentTarget.dataset.skuId,
      goods = _this.data.goods_list[index];
    // order_total_price = _this.data.order_total_price;
    // 后端同步更新
    wx.showLoading({
      title: '加载中',
      mask: true
    });
    App._post_form('cart/add', {
      goods_id: goods.goods_id,
      goods_num: 1,
      goods_sku_id: goodsSkuId
    }, function() {
      // 商品数量
      goods.total_num++;
      // 商品总价格
      goods.total_price = _this.mathadd(goods.total_price, goods.goods_price);
      // console.log(goods.total_price);
      // 更新商品信息
      _this.setData({
        ['goods_list[' + index + ']']: goods
      }, function() {
        // 更新购物车总价格
        _this.updateTotalPrice();
      });
    }, null, function() {
      wx.hideLoading();
    });
  },

  /**
   * 递减指定的商品数量
   */
  minusCount: function(e) {
    let _this = this,
      index = e.currentTarget.dataset.index,
      goodsSkuId = e.currentTarget.dataset.skuId,
      goods = _this.data.goods_list[index];
    // order_total_price = _this.data.order_total_price;

    if (goods.total_num > 1) {
      // 后端同步更新
      wx.showLoading({
        title: '加载中',
        mask: true
      })
      App._post_form('cart/sub', {
        goods_id: goods.goods_id,
        goods_sku_id: goodsSkuId
      }, function() {
        // 商品数量
        goods.total_num--;
        if (goods.total_num > 0) {
          // 商品总价格
          goods.total_price = _this.mathsub(goods.total_price, goods.goods_price);
          // console.log(goods.total_price);
          // 更新商品信息
          _this.setData({
            ['goods_list[' + index + ']']: goods
          }, function() {
            // 更新购物车总价格
            _this.updateTotalPrice();
          });
        }
      }, null, function() {
        wx.hideLoading();
      });

    }
  },

  /**
   * 购物车结算
   */
  submit: function() {
    let _this = this,
      cartIds = _this.getCheckedIds();
    if (!cartIds.length) {
      App.showError('您还没有选择商品');
      return false;
    }
    wx.navigateTo({
      url: '../flow/checkout?order_type=cart&cart_ids=' + cartIds
    });
  },

  /**
   * 加法
   */
  mathadd: function(arg1, arg2) {
    return (Number(arg1) + Number(arg2)).toFixed(2);
  },

  /**
   * 减法
   */
  mathsub: function(arg1, arg2) {
    return (Number(arg1) - Number(arg2)).toFixed(2);
  },

  /**
   * 去购物
   */
  goShopping: function() {
    wx.switchTab({
      url: '../index/index',
    });
  },

})