// pages/评价/evaluate.js

Page({

  /**
   * 页面的初始数据
   */
  data: {
    request:null,
    teacher_star:4,
    course_star:1
  },

  setCmtContent: function (e) {
    var val = e.detail.value;
    var type = e.currentTarget.dataset['type'];
    if(type == 'course'){
      this.setData({
        course_content:val
      })
    }
    if (type == 'teacher') {
      this.setData({
        teacher_content: val
      })
    }
  },
  /**
   * 设置课程评价星数
   */
  setCourseStar: function (e) {
    this.setData({
      course_star: e.currentTarget.dataset['count']
    })
  },
    /**
   * 设置老师评价星数
   */
  setTeacherStar: function (e) {
    this.setData({
      teacher_star: e.currentTarget.dataset['count']
    })
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
  
  }
})