// component/header.js
Component({
  /**
   * 组件的属性列表
   */
  properties: {
    fixed: Boolean,
    color: {
      type: String,
      value: '#000'
    },
    backgroundColor: {
      type: String,
      value: '#fff'
    },
    back: {
      type: null,
      value: false
    }
  },

  /**
   * 组件的初始数据
   */
  data: {
    isx: /iphone10|iphone x/i.test(wx.getSystemInfoSync().model),//44
    isAndroid: (function(){
      var info = wx.getSystemInfoSync()// wx.getSystemInfoSync().system
      if (/android/i.test(info.system) && info.statusBarHeight < 30){
        return true;
      }
      return false;
    })(),
    isBigAndroid:(function(){
      var info = wx.getSystemInfoSync()// wx.getSystemInfoSync().system
      if (/android/i.test(info.system) && info.statusBarHeight >= 30) {
        return true;
      }
      return false;
    })()//40
  },
  /**
   * 组件的方法列表
   */
  methods: {
    backFunction: function (e) {
      console.log(this.data.back)
      if (this.data.back == '1'){
        wx.navigateBack({
          delta: this.data.back
        });
      }else if(this.data.back == '2'){
        wx.switchTab({
          url: '/pages/index/index'
        });
      }
    }
  }
})
