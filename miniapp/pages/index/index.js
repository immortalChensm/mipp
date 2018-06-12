var tool = require("../../utils/tool.js")
const app = getApp()

Page({
  data: {
    banners: [],
    course_types: [],
    indicatorDots: true,
    autoplay: true,
    interval: 4000,
    duration: 1000,
    autoplays: false
  },
  onLoad: function (options) {
    //获取banner
    this.getBanner();
    //获取课程分类
    this.getCourseTypes();

    console.log(wx.getStorageSync('userinfo'))
  },
  getBanner: function () {
    var that = this;
    tool.get('Banner/banner', function (res) {
      //console.log(res.data.data)
      that.setData({ banners: res.data.data });
    })
  },
  getCourseTypes: function () {
    var that = this;
    tool.get('Course/course_types', function (res) {
      //console.log(res)
      that.setData({
        course_types: res.data.data
      })
    })
  },
  toBannerLink: function (event) {
	return false;
    var link = event.currentTarget.dataset['link'];
    wx.navigateTo({
      url: link
    })
  }
})