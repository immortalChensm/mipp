var tool = require("../../utils/tool.js")
var WxParse = require('../../wxParse/wxParse.js')
const app = getApp()
Page({
  onReady: function (res) {
    this.videoContext = wx.createVideoContext('myVideo')
  },
  data: {
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
    autoplays: false,
    bools: true,
    is_follow:false
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
  /**
   * 购买课程
   */
  buyCourse:function(info){
    wx.setStorageSync('cart_info', this.data.course_info);
    var postdata = info.detail;
    postdata.session_key = wx.getStorageSync('session_key');
    // console.log(postdata)
    tool.post('Base/getPhone', postdata, function (result) {
      // console.log(result)
      var info = result.data;
      wx.setStorageSync('phone', info.data.phoneNumber);
      wx.navigateTo({
        url: '/pages/confirm_order/index'
      })
    });
  },
  toYuyue: function (info) {
    wx.setStorageSync('cart_info', this.data.course_info);
    var postdata = info.detail;
    postdata.session_key = wx.getStorageSync('session_key');
    // console.log(postdata)
    tool.post('Base/getPhone', postdata, function (result) {
      // console.log(result)
      var info = result.data;
      wx.setStorageSync('phone', info.data.phoneNumber);
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
      'request':options
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
      that.setData({
        comments: res.data.data
      })
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
    //检查登录
    if (!app.checkLogin()) {
      app.doLogin();
      return false;
    }
    //获取课程信息
    this.getCourseInfo();
    //获取关联老师的信息
    this.getTeacherInfo();
    //获取评价信息
    this.getCommentInfo();
    //是否关注
    this.check_follow();
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
   * 用户点击右上角分享
   */
  onShareAppMessage: function (options) {
    var that = this;

　　return {
  　　　　title: that.data.course_info.name,        // 默认是小程序的名称(可以写slogan等)
  　　　　//path: '/pages/course_detail/index',        // 默认是当前页面，必须是以‘/’开头的完整路径
  　　　　imgUrl: '',     //自定义图片路径，可以是本地文件路径、代码包文件路径或者网络图片路径，支持PNG及JPG，不传入 imageUrl 则使
  　　　　success: function (res) {
    　　　　　　// 转发成功之后的回调
    　　　　　　if (res.errMsg == 'shareAppMessage:ok') {
    　　　　　　}
  　　　　},
  　　　　fail: function (res) {
    　　　　　　// 转发失败之后的回调
    　　　　　　if (res.errMsg == 'shareAppMessage:fail cancel') {
      　　　　　　　　// 用户取消转发
    　　　　　　} else if (res.errMsg == 'shareAppMessage:fail') {
      　　　　　　　　// 转发失败，其中 detail message 为详细失败信息
    　　　　　　}
  　　　　}
　　}
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
          is_follow:info.data
        })
      }
    });
  },
  /**
   * 收藏
   */
  collect:function(event){
    var that = this;
    if (!that.data.is_follow){
      var id = event.currentTarget.dataset['id'];
      tool.post('User/follow', { relation_id: id, type: 2 }, function (result) {
        that.setData({
          is_follow:true
        });
        tool.jsalert(result.data.msg);
      })
    }else{
      tool.jsalert('您已收藏',2);
    }
  }
})