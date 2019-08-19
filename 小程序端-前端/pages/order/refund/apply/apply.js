const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    // 订单商品id
    order_goods_id: null,

    // 订单商品详情
    detail: {},

    // 图片列表
    imageList: [],

    // 服务类型
    serviceType: 10,
  },

  disable: false,

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    // 记录页面参数
    this.data.order_goods_id = options.order_goods_id;

    // 获取订单商品详情
    this.getGoodsDetail();
  },

  /**
   * 获取订单商品详情
   */
  getGoodsDetail: function() {
    let _this = this;
    App._get('user.refund/apply', {
      order_goods_id: this.data.order_goods_id
    }, function(result) {
      _this.setData(result.data);
    });
  },

  /**
   * 切换标签
   */
  onSwitchService: function(e) {
    // 记录formId
    App.saveFormId(e.detail.formId);
    this.setData({
      serviceType: e.detail.target.dataset.type
    });
  },

  /**
   * 跳转商品详情
   */
  onGoodsDetail: function(e) {
    // 记录formId
    App.saveFormId(e.detail.formId);
    wx.navigateTo({
      url: '../../../goods/index?goods_id=' + e.detail.target.dataset.id
    });
  },

  /**
   * 选择图片
   */
  chooseImage: function(e) {
    let _this = this,
      index = e.currentTarget.dataset.index,
      imageList = _this.data.imageList;
    // 记录formId
    App.saveFormId(e.detail.formId);
    // 选择图片
    wx.chooseImage({
      count: 6 - imageList.length,
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
      success: function(res) {
        _this.setData({
          imageList: imageList.concat(res.tempFilePaths)
        });
      }
    });
  },

  /**
   * 删除图片
   */
  deleteImage: function(e) {
    let dataset = e.currentTarget.dataset,
      imageList = this.data.imageList;
    imageList.splice(dataset.imageIndex, 1);
    this.setData({
      imageList
    });
  },

  /**
   * 表单提交
   */
  onSubmit: function(e) {
    let _this = this;

    if (!e.detail.value.content) {
      App.showError('申请原因不能为空');
      return false;
    }

    // 判断是否重复提交
    if (_this.disable === true) {
      return false;
    }
    // 表单提交按钮设为禁用 (防止重复提交)
    _this.disable = true;

    wx.showLoading({
      title: '正在处理...',
      mask: true
    });

    // form参数
    let postParams = {
      order_goods_id: _this.data.order_goods_id,
      type: _this.data.serviceType,
      content: e.detail.value.content,
    };

    // form提交执行函数
    let fromPostCall = function(params) {
      console.log('fromPostCall');
      App._post_form('user.refund/apply', params, function(result) {
          if (result.code === 1) {
            App.showSuccess(result.msg, function() {
              // 跳转售后管理页面
              wx.navigateTo({
                url: "../index"
              });
            });
          } else {
            App.showError(result.msg);
          }
        },
        false,
        function() {
          wx.hideLoading();
          _this.disable = false;
        });
    };

    // 统计图片数量
    let imagesLength = _this.data.imageList.length;

    // 判断是否需要上传图片
    imagesLength > 0 ? _this.uploadFile(imagesLength, fromPostCall, postParams) : fromPostCall(postParams);
  },

  /**
   * 上传图片
   */
  uploadFile: function(imagesLength, callBack, formData) {
    let uploaded = [];
    // 文件上传
    let i = 0;
    this.data.imageList.forEach(function(filePath, fileKey) {
      wx.uploadFile({
        url: App.api_root + 'upload/image',
        filePath: filePath,
        name: 'iFile',
        formData: {
          wxapp_id: App.getWxappId(),
          token: wx.getStorageSync('token')
        },
        success: function(res) {
          let result = typeof res.data === "object" ? res.data : JSON.parse(res.data);
          if (result.code === 1) {
            uploaded[fileKey] = result.data.file_id;
          }
        },
        complete: function() {
          i++;
          if (imagesLength === i) {
            // 所有文件上传完成
            console.log('upload complete');
            formData['images'] = uploaded;
            // 执行回调函数
            callBack && callBack(formData);
          }
        }
      });
    });
  },


})