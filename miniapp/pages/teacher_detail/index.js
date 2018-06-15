// pages/老师介绍/teacherinfo.js
var tool = require("../../utils/tool.js")
var app = getApp
Page({

  /**
   * 页面的初始数据
   */
  data: {
    
    bools: 1,
    selected1: true,
    selected2: false,
    selected3: false
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
  onShow: function () {
    tool.post('Teacher/detail', {id:1}, function (res) {
      console.log(res); return false;
      that.setData({
        teacher_list: res.data.data
      })
    })
  }

})