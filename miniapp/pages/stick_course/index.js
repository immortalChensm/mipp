var tool = require("../../utils/tool.js")
var app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    course_list:[],
    cur_page:1,
    data_end:false
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

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
    //获取推荐的课程列表
    this.getCourseList();
  },
  /**
   * 获取推荐的课程列表
   */
  getCourseList:function(){
    var that = this;
    var postdata = {};
    postdata.p = this.data.cur_page;
    tool.post('Course/course_stick', postdata, function (result) {
      var info = result.data;
      console.log(info)
      if (info.status == '1') {
        that.setData({
          course_list: that.data.course_list.concat(info.data)
        })
      } else {
        that.setData({
          data_end: true
        })
      }
    })
  },
  toCourseDetail: function (event) {
    var course_id = event.currentTarget.dataset['id'];
    wx.navigateTo({
      url: '/pages/course_detail/index?id=' + course_id
    })
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
    //重新加载
    this.onLoad();
    this.onShow();
    wx.stopPullDownRefresh();
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  //滚动底部监听
  onReachBottom: function () { //下拉刷新
    var cur_page = this.data.cur_page;
    if (!this.data.data_end) {
      this.setData({
        cur_page: cur_page + 1
      })
      wx.showToast({
        title: '加载中',
        icon: 'loading',
        duration: 1000,
      });
      this.getCourseList();
    }
  },
  /**
 * 分享
 */
  onShareAppMessage: function (res) {
    if (res.from === 'button') {
      // 来自页面内转发按钮
      console.log(res.target)
    }
    return app.shareApp();
  }
})