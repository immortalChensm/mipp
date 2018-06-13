var tool = require("../../utils/tool.js")
const app = getApp()
Page({
  onReady: function (res) {
    this.videoContext = wx.createVideoContext('myVideo')
  },
  data: {
    bools: true,
    src: 'http://wxsnsdy.tc.qq.com/105/20210/snsdyvideodownload?filekey=30280201010421301f0201690402534804102ca905ce620b1241b726bc41dcff44e00204012882540400&bizid=1023&hy=SH&fileparam=302c020101042530230204136ffd93020457e3c4ff02024ef202031e8d7f02030f42400204045a320a0201000400',
    'request':null,
    'course_info':[],
    'teacher_info': [],
    'comments': [],
  },
  //课程详情和评价点击事件
  tabShow:function(){
    var that = this;
    if (that.data.bools == 1) {
      that.data.bools = 2;
      that.setData({
        showkecd: true
      })
    } else {
      that.data.bools = 1;
      that.setData({
        showkecd: false
      })
    }
  },


  buyCourse:function(){
    wx.navigateTo({
      url: '/pages/confirm_order/index'
    })
  },
  toYuyue: function () {
    wx.navigateTo({
      url: '/pages/yuyue/index'
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      'request':options
    })
    //获取课程信息
    this.getCourseInfo();
    //获取关联老师的信息
    this.getTeacherInfo();
    //获取评价信息
    this.getCommentInfo();
  },
  /**
   * 获取课程信息
   */
  getCourseInfo: function () {
    var that = this;
    tool.post('Course/course_info',{course_id:that.data.request.id}, function (res) {
      //console.log(res)
      that.setData({
        course_info: res.data.data
      })
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
  onShareAppMessage: function () {
  
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
   * 收藏
   */
  collect:function(event){
    var id = event.currentTarget.dataset['id'];
    tool.post('User/collect',{relation_id:id,type:2},function(result){
      tool.jsalert(result.data.msg);
    })
  }
})