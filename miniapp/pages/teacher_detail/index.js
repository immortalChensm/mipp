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
  // scShow() {
  //   var that = this;
  //   if (that.data.bools == 1) {
  //     that.data.bools = 2;
  //     that.setData({
  //       checked: true
  //     })
  //   } else {
  //     that.data.bools = 1;
  //     that.setData({
  //       checked: false
  //     })
  //   }
  // },
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
      var checked = false;
      if (res.data.data.user_follow) checked = true
      that.setData({
        tlist: res.data.data,
        checked: checked
      })
      WxParse.wxParse('content', 'html', res.data.data.content, that, 5);
    })
  },
  //分享
  onShareAppMessage: function (res) {
    if (res.from === 'button') {
      // 来自页面内转发按钮
      console.log(res.target)
    }
    return {
      title: '去喝茶',
      path: '/pages/index/index',
      success: function (res) {
        // 转发成功
      },
      fail: function (res) {
        // 转发失败
      }
    }
  },
  /**
 * 收藏
 */
  collect: function (event) {
    var that = this;
    var id = event.currentTarget.dataset['id'];
    var openid = wx.getStorageSync('openid');
    console.log(id);
    tool.post('User/follow', { relation_id: id, type: 1,openid: openid}, function (res) {
      tool.jsalert(res.data.msg);
      if(res.data.status =='1'){
        that.setData({
          checked: true
        })
      }else {
        that.setData({
          checked: false
        })
      }
    })
  },
  /**
   * 去课程详情
   */
  toCourseDetail: function (event) {
    var course_id = event.currentTarget.dataset['id'];
    wx.navigateTo({
      url: '/pages/course_detail/index?id=' + course_id
    })
  },
})