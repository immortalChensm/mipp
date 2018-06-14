// pages/常见问题/problem.js
var tool = require("../../utils/tool.js")
const app = getApp()
var WxParse = require('../../wxParse/wxParse.js')
Page({
  data: {
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    var that = this;
    tool.post('System/index', { type: 1 }, function (res) {
      WxParse.wxParse('content', 'html', res.data.data.content, that, 5);
    })
  },
})