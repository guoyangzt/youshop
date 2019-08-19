const wxParse = require("../../../wxParse/wxParse.js");

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
    params: Object
  },

  ready: function() {
    let content = this.data.params.content;
    // 富文本转码
    if (content.length > 0) {
      wxParse.wxParse('contentT', 'html', content, this, 0);
    }
  },


})