// pages/老师介绍/teacherinfo.js
var tool = require("../../utils/tool.js")
var WxParse = require('../../wxParse/wxParse.js')
var app = getApp()
Page({
  backpage: app.backpage,
  /**
   * 页面的初始数据
   */
  data: {
    navdata: {
      title: '教师详情',
      backtype:1
    },
    request:null,
    bools: 1,
    selected1: true,
    selected2: false,
    selected3: false,
    tlist: [],
    id:0,
    checed:false,
    follow_num:'',
    follow_id:''
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
  onShow: function (option) {
    wx.showToast({
      title: '',
      icon:'loading',
      duration:10000
    })
    var that = this;
    var id = this.data.request.id;
    //转发页面左上角加去首页的按钮
    if (this.data.request.is_share == '1') {
      this.setData({
        navdata: {
          title: '教师详情',
          backtype: 2
        }
      })
    }
    tool.post('Teacher/details', {id:id}, function (res) {
      // console.log(res.data.data); return false;
      var checked = false;
      if (res.data.data.user_follow) checked = true;
      that.setData({
        tlist: res.data.data,
        checked: checked,
        follow_num: res.data.data.follow,
        follow_id: res.data.data.user_follow
      })
      WxParse.wxParse('content', 'html', res.data.data.content, that, 5);
      wx.hideToast();
    })
  },
  /**
   * 打开地图
   */
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
    return app.shareApp(
      this.data.tlist.name,
      '/pages/teacher_detail/index?is_share=1&id=' + this.data.tlist.id,
      this.data.tlist.headimgurl
    );
  },
  /**
 * 收藏
 */
  collect: function (event) {
    var that = this;
    var id = event.currentTarget.dataset['id'];
    // console.log(id);
    if(that.data.checked){
      tool.post('Follow/cancel', { id: that.data.follow_id }, function (result) {
        var info = result.data;
        if (info.status == '1') {
          tool.jsalert(info.msg);
          that.setData({
            checked: false,
            follow_id: '',
            follow_num:that.data.follow_num-1
          });
        } else {
          tool.jsalert(info.msg, 2);
        }
      },null,2)
    }else{
      tool.post('User/follow', { relation_id: id, type: 1 }, function (res) {
        var info = res.data;
        if (info.status == '1') {
          tool.jsalert(res.data.msg);
          that.setData({
            checked: true,
            follow_num: info.data.follow_num,
            follow_id: info.data.follow_id
          })
        } else {
          tool.jsalert(res.data.msg, 2);
        }
      }, null, 2)
    }
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