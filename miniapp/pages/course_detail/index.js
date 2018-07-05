var tool = require("../../utils/tool.js");
var WxParse = require('../../wxParse/wxParse.js');
const app = getApp()
Page({
  backpage: app.backpage,
  onReady: function (res) {
    this.videoContext = wx.createVideoContext('myVideo')
  },
  data: {
    navdata: {
      title: '课程详情',
      backtype: 1
    },
    request:null,
    course_info:[],
    teacher_info: [],
    comments: [],
    course_content:'',
    hide_content:false,
    indicatorDots: true,
    autoplay: false,
    interval: 3000,
    duration: 1000,
    // autoplays: false,
    bools: true,
    is_follow:false,
    has_phone:false,
    buy_click:false,
    yuyue_click: false,
    follow_id:'',
    show_telebox:false,
    hide_video_a:true,
    hide_video_b: false,
    auto_play: false
  },
  //课程详情和评价点击事件
  tabShow:function(event){
    var that = this;
    var type = event.currentTarget.dataset['type'];
    if(type == 'content'){
      that.setData({
        hide_content:false
      })
    }else{
      that.setData({
        hide_content: true
      })
    }
  },

  /***视频播放**/
  edu_video_auto: function (){

    this.setData({
      hide_video_a: false,
      hide_video_b: true,
      auto_play:true
    })
  
  },

  /**
   * 购买课程
   */
  buyCourse:function(info){
    if(this.data.buy_click){
      return false;
    }else{
      this.setData({
        buy_click:true
      })
    }
    wx.setStorageSync('cart_info', this.data.course_info);
    var postdata = info.detail;
    postdata.session_key = wx.getStorageSync('session_key');
    // console.log(postdata)
    tool.post('Base/getPhone', postdata, function (result) {
      // console.log(result)
      var info = result.data;
      if (info.data.phoneNumber) wx.setStorageSync('phone', info.data.phoneNumber);
      wx.navigateTo({
        url: '/pages/confirm_order/index'
      })
    });
  },
  toYuyue: function (info) {
    if (this.data.yuyue_click) {
      return false;
    } else {
      this.setData({
        yuyue_click: true
      })
    }
    wx.setStorageSync('cart_info', this.data.course_info);
    var postdata = info.detail;
    postdata.session_key = wx.getStorageSync('session_key');
    // console.log(postdata)
    tool.post('Base/getPhone', postdata, function (result) {
      // console.log(result)
      var info = result.data;
      if (info.data.phoneNumber ) wx.setStorageSync('phone', info.data.phoneNumber);
      wx.navigateTo({
        url: '/pages/yuyue/index'
      })
    });
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      'request':options,
    })
  
  },
  /**
   * 获取课程信息
   */
  getCourseInfo: function () {
    var that = this;
    tool.post('Course/course_info',{course_id:that.data.request.id}, function (res) {
      //console.log(content)
      that.setData({
        course_info: res.data.data,
      })
      WxParse.wxParse('content', 'html', res.data.data.content, that, 5)
    })
  },
  /**
   * 获取关联老师的信息
   */
  getTeacherInfo: function () {
    var that = this;
    tool.post('Teacher/teacher_info', { course_id: that.data.request.id }, function (res) {
      //console.log(res)
      that.setData({
        teacher_info: res.data.data
      })
    })
  },
  /**
 * 获取评价信息
 */
  getCommentInfo: function () {
    var that = this;
    tool.post('Course/comments', { course_id: that.data.request.id }, function (res) {
      //console.log(res)
      if (res.data.status == '1'){
        that.setData({
          comments: res.data.data
        })
      }
    })
  },
  /**
 * 打开地图
 */
  openAddress: function () {
    var lng = parseFloat(this.data.teacher_info.lng);
    var lat = parseFloat(this.data.teacher_info.lat);
    wx.openLocation({
      latitude: lat,
      longitude: lng,
      scale: 28
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },



  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    
    wx.showToast({
      title: '',
      icon:'loading',
      duration:10000
    })
    if(wx.getStorageSync('phone')){
      this.setData({
        has_phone:true
      })
    }
    //转发页面左上角加去首页的按钮
    if(this.data.request.is_share == '1'){
      this.setData({
        navdata: {
          title: '课程详情',
          backtype:2
        }
      })
    }
    this.setData({
      buy_click:false,
      yuyue_click:false,
   
    })
    //获取课程信息
    this.getCourseInfo();
    //获取关联老师的信息
    this.getTeacherInfo();
    //获取评价信息
    this.getCommentInfo();
    //是否关注
    this.check_follow();
    wx.hideToast();
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 分享
   */
  onShareAppMessage: function (res) {
    if (res.from === 'button') {
      // 来自页面内转发按钮
      console.log(res.target)
    }
    return app.shareApp(
      this.data.course_info.name,
      '/pages/course_detail/index?is_share=1&id=' + this.data.course_info.id,
      this.data.course_info.pics[0]
    );
  },
  /**
   * 拨打电话
   */
  calling: function (event) {
    var phone = event.currentTarget.dataset['phone'];
    wx.makePhoneCall({
      phoneNumber: phone,  
      success: function () {
        console.log("拨打电话成功！")
      },
      fail: function () {
        console.log("拨打电话失败！")
      }
    })
  },
  /**
   * 检查是否关注
   */
  check_follow:function(){
    var that = this;
    tool.post('Follow/check_follow', { type: 2, rel_id: that.data.request.id},function(result){
      var info = result.data;
      if(info.status == '1'){
        that.setData({
          is_follow:true,
          follow_id:info.data
        })
      }else{
        that.setData({
          is_follow: false
        })
      }
    });
  },
  /**
   * 收藏
   */
  collect:function(event){
    var that = this;
    var id = event.currentTarget.dataset['id'];
    // console.log(this.data.follow_id)
    if (that.data.is_follow) {
      tool.post('Follow/cancel', { id: that.data.follow_id }, function (result) {
        var info = result.data;
        if (info.status == '1') {
          tool.jsalert(info.msg);
          that.setData({
            is_follow: false,
            follow_id: ''
          });
        } else {
          tool.jsalert(info.msg, 2);
        }
      })
    } else {
      tool.post('User/follow', { relation_id: id, type: 2 }, function (result) {
        var info = result.data;
        if (info.status == '1') {
          tool.jsalert(info.msg);
          that.setData({
            is_follow: true,
            follow_id:info.data
          });
        } else {
          tool.jsalert(info.msg, 2);
        }
      })
    }
  },
  show_telebox:function(){
    this.setData({
      show_telebox:true
    })
  },
  cancel_telebox:function(){
    this.setData({
      show_telebox:false
    })
  }
})