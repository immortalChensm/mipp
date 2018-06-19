var tool = require("../../utils/tool.js")
const app = getApp()

Page({
  data: {
    banners: [],
    course_types: [],
    course_stick:[],
    teacher_stick:[],
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
    //获取推荐课程
    this.getStickCourse();
    //获取推荐老师
    this.getStickTeacher();
    //console.log(wx.getStorageSync('userinfo'))
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
  getStickCourse: function () {
    var that = this;
    tool.get('Course/course_stick', function (res) {
      //console.log(res)
      that.setData({
        course_stick: res.data.data
      })
    })
  }, 
  getStickTeacher: function () {
    var that = this;
    wx.getLocation({
      success: function(res) {
        //console.log(res)
        tool.post('Teacher/teacher_stick', {lng:res.longitude, lat:res.latitude}, function (res) {
          //console.log(res)
          that.setData({
            teacher_stick: res.data.data
          })
        })
      },
    })
    
  },  
  toCourseList: function (event) {
    var type = event.currentTarget.dataset['type'];
    wx.setStorageSync('course_type',type);
    wx.switchTab({
      url: '/pages/course/index'
    })
  },
  moreCourse: function (event) {
    wx.switchTab({
      url: '/pages/course/index'
    })
  },
  moreTeacher: function (event) {
    wx.switchTab({
      url: '/pages/near_teacher/index'
    })
  },
  toCourseDetail: function (event) {
    var course_id = event.currentTarget.dataset['id'];
    wx.navigateTo({
      url: '/pages/course_detail/index?id=' + course_id
    })
  },
  toTeacherDetail: function (event) {
    var teacher_id = event.currentTarget.dataset['id'];
    wx.navigateTo({
      url: '/pages/teacher_detail/index?id=' + teacher_id
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