var tool = require("../../utils/tool.js")
const app = getApp()

Page({
  backpage:app.backpage,
  data: {
    navdata:{
      title: '首页',  
      backtype: 0
    },
    banners: [],
    course_types: [],
    course_stick:[],
    teacher_stick:[],
    indicatorDots: false,
    autoplay: true,
    interval: 3000,
    duration: 1000,
    circular:true,
    autoplays: false
  },
  onLoad: function (options) {
    var that = this;
    var navdata = that.data.navdata;
    app.getBarHeight(function(info){
      navdata.tabbarh = (info.statusBarHeight + 15) * info.pixelRatio;
      console.log(info)
      that.setData({
        navdata:navdata
      })
    })
  },
  onShow:function(){
    //获取banner
    this.getBanner();
    //获取课程分类
    this.getCourseTypes();
    //获取推荐课程
    this.getStickCourse();
    //获取推荐老师
    this.getStickTeacher();
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
      var info = res.data.data
      var new_info = [];
      var block_arr = [];
      for(var i=0;i<info.length;i++){
        block_arr.push(info[i]);
        if ((i+1)%8 == 0){
          new_info.push(block_arr);
          block_arr = []
        }
      }
      if (block_arr.length > 0) new_info.push(block_arr);
      that.setData({
        course_types: new_info
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
    wx.navigateTo({
      url: '/pages/stick_course/index'
    })
  },
  moreTeacher: function (event) {
    wx.navigateTo({
      url: '/pages/stick_teacher/index'
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
    var link = event.currentTarget.dataset['link'];
    wx.navigateTo({
      url: link
    })
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
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
    //重新加载
    this.onLoad();
    this.onShow();
    wx.stopPullDownRefresh();
  }
})