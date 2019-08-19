const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    // 页面参数
    options: null,

    // 待评价商品列表
    goodsList: [],

    // 表单数据
    formData: [],
  },

  submitDisable: false,

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    // 记录页面参数
    this.data.options = options;

    // 获取待评价商品列表
    this.getGoodsList();
  },

  /**
   * 获取待评价商品列表
   */
  getGoodsList: function() {
    let _this = this;
    App._get('user.comment/order', {
      order_id: this.data.options.order_id
    }, function(result) {
      let goodsList = result.data.goodsList;
      _this.setData({
        goodsList,
        formData: _this.initFormData(goodsList)
      });
    });
  },

  /**
   * 初始化form数据
   */
  initFormData: function(goodsList) {
    let data = [];
    goodsList.forEach(function(item) {
      data.push({
        goods_id: item.goods_id,
        order_goods_id: item.order_goods_id,
        score: 10,
        content: '',
        image_list: [
          // 'http://tmp/wxe1997e687ecca54e.o6zAJs38WC0RISx_rydS4v4D778c.VzVJOgmUHlH3fd47776794bd803898289bebee12d94c.jpg',
          // 'http://tmp/wxe1997e687ecca54e.o6zAJs38WC0RISx_rydS4v4D778c.u8PUZLBNG2ELa7692fe0b9dfebf762cf0cb3677a42d7.jpg',
          // 'http://tmp/wxe1997e687ecca54e.o6zAJs38WC0RISx_rydS4v4D778c.8PjhMmysqokY55a19834d4135fbf72d4e653010d375e.jpg'
        ],
        uploaded: []
      });
    });
    return data;
  },

  /**
   * 设置评分
   */
  setScore: function(e) {
    let dataset = e.currentTarget.dataset;
    this.setData({
      ['formData[' + dataset.index + '].score']: dataset.score
    });
  },

  /**
   * 输入评价内容
   */
  contentInput: function(e) {
    let index = e.currentTarget.dataset.index;
    this.setData({
      ['formData[' + index + '].content']: e.detail.value
    });
  },

  /**
   * 选择图片
   */
  chooseImage: function(e) {
    let _this = this,
      index = e.currentTarget.dataset.index,
      imageList = _this.data.formData[index].image_list;
    // 选择图片
    wx.chooseImage({
      count: 6 - imageList.length,
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
      success: function(res) {
        _this.setData({
          ['formData[' + index + '].image_list']: imageList.concat(res.tempFilePaths)
        });
      }
    });
  },

  /**
   * 删除图片
   */
  deleteImage: function(e) {
    let dataset = e.currentTarget.dataset,
      image_list = this.data.formData[dataset.index].image_list;
    image_list.splice(dataset.imageIndex, 1);
    this.setData({
      ['formData[' + dataset.index + '].image_list']: image_list
    });
  },

  /**
   * 表单提交
   */
  submit: function() {
    let _this = this,
      formData = _this.data.formData;

    // 判断是否重复提交
    if (_this.submitDisable === true) {
      return false;
    }
    // 表单提交按钮设为禁用 (防止重复提交)
    _this.submitDisable = true;

    wx.showLoading({
      title: '正在处理...',
      mask: true
    });

    // form提交执行函数
    let fromPostCall = function(formData) {
      console.log('fromPostCall');
      console.log(formData);

      App._post_form('user.comment/order', {
          order_id: _this.data.options.order_id,
          formData: JSON.stringify(formData)
        }, function(result) {
          if (result.code === 1) {
            App.showSuccess(result.msg, function() {
              wx.navigateBack();
            });
          } else {
            App.showError(result.msg);
          }
        },
        false,
        function() {
          wx.hideLoading();
          _this.submitDisable = false;
        });
    };

    // 统计图片数量
    let imagesLength = 0;
    formData.forEach(function(item, formIndex) {
      item.content !== '' && (imagesLength += item.image_list.length);
    });

    // 判断是否需要上传图片
    imagesLength > 0 ? _this.uploadFile(imagesLength, formData, fromPostCall) : fromPostCall(formData);
  },

  /**
   * 上传图片
   */
  uploadFile: function(imagesLength, formData, callBack) {
    // POST 参数
    let params = {
      wxapp_id: App.getWxappId(),
      token: wx.getStorageSync('token')
    };
    // 文件上传
    let i = 0;
    formData.forEach(function(item, formIndex) {
      if (item.content !== '') {
        item.image_list.forEach(function(filePath, fileKey) {
          wx.uploadFile({
            url: App.api_root + 'upload/image',
            filePath: filePath,
            name: 'iFile',
            formData: params,
            success: function(res) {
              let result = typeof res.data === "object" ? res.data : JSON.parse(res.data);
              if (result.code === 1) {
                item.uploaded[fileKey] = result.data.file_id;
              }
            },
            complete: function() {
              i++;
              if (imagesLength === i) {
                // 所有文件上传完成
                console.log('upload complete');
                // 执行回调函数
                callBack && callBack(formData);
              }
            }
          });
        });
      }
    });

  },


})