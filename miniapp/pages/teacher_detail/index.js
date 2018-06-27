// pages/老师介绍/teacherinfo.js
var tool = require("../../utils/tool.js")
var WxParse = require('../../wxParse/wxParse.js')
var app = getApp()
Page({
  /**
   * 页面的初始数据
   */
  data: {
    request:null,
    bools: 1,
    selected1: true,
    selected2: false,
    selected3: false,
    tlist: [],
    id:0
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
  onLoad: function (options){
    this.setData({
      request:options
    })
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onLoad: function (option) {
    var that = this;
    var id = option.id;
    tool.post('Teacher/details', {id:id}, function (res) {
      // console.log(res.data.data); return false;
      var checked = false;
      if (res.data.data.user_follow) checked = true;
      that.setData({
        tlist: res.data.data,
        checked: checked
      })
      WxParse.wxParse('content', 'html', res.data.data.content, that, 5);
    })
  },
  openAddress:function(){
    var lng = parseFloat(this.data.tlist.lng);
    var lat = parseFloat(this.data.tlist.lat);
    wx.openLocation({
      latitude: lat,
      longitude: lng,
      scale: 28
    })
  },
  //分享
  onShareAppMessage: function (res) {
    if (res.from === 'button') {
      // 来自页面内转发按钮
      console.log(res.target)
    }
    return app.shareApp();
  },
  /**
 * 收藏
 */
  collect: function (event) {
    var that = this;
    var id = event.currentTarget.dataset['id'];
    var openid = wx.getStorageSync('openid');
    // console.log(id);
    tool.post('User/follow', { relation_id: id, type: 1,openid: openid}, function (res) {
      if(res.data.status =='1'){
        tool.jsalert(res.data.msg);
        that.setData({
          checked: true,
          follow_num:res.data.data
        })
      }else {
        tool.jsalert(res.data.msg,2);
        // that.setData({
        //   checked: false
        // })
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