// pages/关于我们/about.js
var tool = require("../../utils/tool.js")
var WxParse = require('../../wxParse/wxParse.js')
const app = getApp()
Page({
  data: {
    info: ''
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    var that = this;
    tool.post('System/index', { type: 2 }, function (res) {
      // that.setData({
      //  // info: res.data.data.content
      // })
       WxParse.wxParse('content', 'html', res.data.data.content, that, 5);
  
    })
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  }
})