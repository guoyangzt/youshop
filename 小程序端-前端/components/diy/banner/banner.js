const App = getApp();

Component({

  options: {

  },

  /**
   * 组件的属性列表
   * 用于组件自定义设置
   */
  properties: {
    itemIndex: String,
    itemStyle: Object,
    dataList: Object,
    params: Object
  },

  /**
   * 私有数据,组件的初始数据
   * 可用于模版渲染
   */
  data: {
    // banner轮播组件属性
    indicatorDots: true, // 是否显示面板指示点	
    autoplay: true, // 是否自动切换
    duration: 800, // 滑动动画时长

    imgHeights: [], // 图片的高度
    imgCurrent: 0, // 当前banne所在滑块指针
  },

  /**
   * 组件的方法列表
   * 更新属性和数据的方法与更新页面数据的方法类似
   */
  methods: {

    /**
     * 计算图片高度
     */
    _imagesHeight: function(e) {
      // 获取图片真实宽度
      let imgwidth = e.detail.width,
        imgheight = e.detail.height,
        // 宽高比
        ratio = imgwidth / imgheight;
      // 计算的高度值
      let viewHeight = 750 / ratio,
        imgHeights = this.data.imgHeights;
      // 把每一张图片的高度记录到数组里
      imgHeights.push(viewHeight);
      this.setData({
        imgHeights,
      });
    },

    /**
     * 记录当前指针
     */
    _bindChange: function(e) {
      this.setData({
        imgCurrent: e.detail.current
      });
    },

    /**
     * 跳转到指定页面
     */
    navigationTo: function(e) {
      App.navigationTo(e.currentTarget.dataset.url);
    },

  }

})