// pages/老师介绍/teacherinfo.js
var tool = require("../../utils/tool.js")
var WxParse = require('../../wxParse/wxParse.js')
var app = getApp
Page({

  /**
   * 页面的初始数据
   */
  data: {
    bools: 1,
    selected1: true,
    selected2: false,
    selected3: false,
    tlist: [],
  },
//收藏点击
  scShow() {
    var that = this;
    if (that.data.bools == 1) {
      that.data.bools = 2;
      that.setData({
        checked: true
      })
    } else {
      that.data.bools = 1;
      that.setData({
        checked: false
      })
    }
  },
//老师--授课--评价 切换
  selectTab1:function(e){
    var that = this;
      that.setData({
        selected1: true,
        selected2: false,
        selected3: false,
      })
  },
  selectTab2: function (e) {
    var that = this;
    that.setData({
      selected1: false,
      selected2: true,
      selected3: false,
    })
  },
  selectTab3: function (e) {
    var that = this;
    that.setData({
      selected1: false,
      selected2: false,
      selected3: true,
    })
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function (id) {
    var that = this;
    tool.post('Teacher/details', {id:1}, function (res) {
      // console.log(res.data.data); return false;
      that.setData({
        tlist: res.data.data
      })
      WxParse.wxParse('profile', 'html', res.data.data.profile, that, 5);
      WxParse.wxParse('content', 'html', res.data.data.content, that, 5);
    })
  }
})