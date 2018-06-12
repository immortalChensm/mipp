// pages/老师介绍/teacherinfo.js

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