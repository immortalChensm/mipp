var tool = require("../../utils/tool.js")
var app = getApp()
Page({
  data: {

  },
  onLoad: function (option) {

  },

  onShow: function () {

  },
  userLogin:function(info) { // 需要一个参数来额外接收用户数据
    var postdata = info.detail;
    postdata.session_key = wx.getStorageSync('session_key');
    console.log(postdata)
    tool.post('Base/getUserInfo', postdata,function(result){
      // console.log(result)
      var info = result.data;
      wx.setStorageSync('userinfo', info.data);
      wx.navigateBack({
        
      })
    });
  }, 
  /**
   * 分享
   */
  onShareAppMessage: function (res) {
    if (res.from === 'button') {
      // 来自页面内转发按钮
      console.log(res.target)
    }
    return app.shareApp();
  },
})