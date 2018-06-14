// pages/个人中心/center.js
var tool = require("../../utils/tool.js")
const app = getApp()
Page({
  data: {
    user:''
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onShow: function () {
    var that = this;
    var openid = wx.getStorageSync('openid');
    tool.post('User/index', { openid: openid}, function (res) {
      // console.log(res.data.data); return false;
      that.setData({
        user: res.data.data
      })
    })
  },
  toOrder: function () {
    wx.navigateTo({
      url: '/pages/my_order/index'
    })
  },
  toFollow: function () {
    wx.navigateTo({
      url: '/pages/follow/index'
    })
  },
  toFaq: function () {
    wx.navigateTo({
      url: '/pages/faq/index'
    })
  },
  toAbout: function () {
    wx.navigateTo({
      url: '/pages/about/index'
    })
  },
  toApplyTeacher: function () {
    wx.navigateTo({
      url: '/pages/apply_teacher/index'
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
  // onShow: function () {
  
  // },

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