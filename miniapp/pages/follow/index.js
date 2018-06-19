var tool = require("../../utils/tool.js")
var app = getApp()
Page({
  data: {
    bools: 1, //课程--老师切换初始状态
    startX: 0, //开始坐标
    startY: 0,
    follow_teacher:[],
    follow_course:[],
    show_teacher:true,
    show_course:false
  },
  onLoad: function () {
    //关注的老师
    this.getFollowTeacher();
  },
  //关注的老师
  getFollowTeacher:function(){
    var that = this;
    wx.getLocation({
      success: function (res) {
        var postdata = {};
        postdata.lng = res.longitude;
        postdata.lat = res.latitude;
        tool.post('Follow/follow_teacher', postdata,function (result) {
          var info = result.data;
          if (info.status == '1') {
            that.setData({
              follow_teacher: info.data,
              show_course: false,
              show_teacher: true
            })
          } else {
            that.setData({
              follow_teacher: null,
              show_course: false,
              show_teacher: true
            })
          }
        })
      },
    })
  },
  //关注的课程
  getFollowCourse: function () {
    var that = this;
    tool.get('Follow/follow_course', function (result) {
      var info = result.data;
      if(info.status == '1'){
        that.setData({
          follow_course:info.data,
          show_course:true,
          show_teacher:false
        })
      }else{
        that.setData({
          follow_course: null,
          show_course: true,
          show_teacher: false
        })
      }
    })
  },
  //手指触摸动作开始 记录起点X坐标--老师
  touchTeacherStart: function (e) {
    //开始触摸时 重置所有删除
    var that = this;
    var follow_teacher = that.data.follow_teacher;
    for (var i = 0; i < follow_teacher.length; i++) {
      if (follow_teacher[i].isTouchMove)//只操作为true的
        follow_teacher[i].isTouchMove = false;
    }
    this.setData({
      follow_teacher: follow_teacher,
      startX: e.changedTouches[0].clientX,
      startY: e.changedTouches[0].clientY,
    })
    //console.log(this.data.follow_teacher)
  },
  //滑动事件处理 -- 老师
  touchTeacherMove: function (e) {
    var that = this,
    index = e.currentTarget.dataset.index,//当前索引
    startX = that.data.startX,//开始X坐标
    startY = that.data.startY,//开始Y坐标
    touchMoveX = e.changedTouches[0].clientX,//滑动变化坐标
    touchMoveY = e.changedTouches[0].clientY,//滑动变化坐标
    angle = that.angle({ X: startX, Y: startY }, { X: touchMoveX, Y: touchMoveY });//获取滑动角度
    //console.log(index, startX, startY, touchMoveX, touchMoveY, angle)
    var follow_teacher = that.data.follow_teacher;
    for (var i = 0; i < follow_teacher.length;i++){
      //滑动超过30度角 return
      if (Math.abs(angle) > 30) return;
      if (i == index) {
        if (touchMoveX > startX) {//右滑
          follow_teacher[i].isTouchMove = false
        }else{ //左滑
          follow_teacher[i].isTouchMove = true
        }
        this.setData({
          follow_teacher: follow_teacher
        })
      }
    }
  },
  //手指触摸动作开始 记录起点X坐标--课程
  touchCourseStart: function (e) {
    //开始触摸时 重置所有删除
    var that = this;
    var follow_course = that.data.follow_course;
    for (var i = 0; i < follow_course.length; i++) {
      if (follow_course[i].isTouchMove)//只操作为true的
        follow_course[i].isTouchMove = false;
    }
    this.setData({
      follow_course: follow_course,
      startX: e.changedTouches[0].clientX,
      startY: e.changedTouches[0].clientY,
    })
    //console.log(this.data.follow_course)
  },
  //滑动事件处理 -- 课程
  touchCourseMove: function (e) {
    var that = this,
      index = e.currentTarget.dataset.index,//当前索引
      startX = that.data.startX,//开始X坐标
      startY = that.data.startY,//开始Y坐标
      touchMoveX = e.changedTouches[0].clientX,//滑动变化坐标
      touchMoveY = e.changedTouches[0].clientY,//滑动变化坐标
      angle = that.angle({ X: startX, Y: startY }, { X: touchMoveX, Y: touchMoveY });//获取滑动角度
    //console.log(index, startX, startY, touchMoveX, touchMoveY, angle)
    var follow_course = that.data.follow_course;
    for (var i = 0; i < follow_course.length; i++) {
      //滑动超过30度角 return
      if (Math.abs(angle) > 30) return;
      if (i == index) {
        if (touchMoveX > startX) {//右滑
          follow_course[i].isTouchMove = false
        } else { //左滑
          follow_course[i].isTouchMove = true
        }
        this.setData({
          follow_course: follow_course
        })
      }
    }
  },
  /**
   * 计算滑动角度
   * @param {Object} start 起点坐标
   * @param {Object} end 终点坐标
   */
  angle: function (start, end) {
    var _X = end.X - start.X,
      _Y = end.Y - start.Y
    //返回角度 /Math.atan()返回数字的反正切值
    return 360 * Math.atan(_Y / _X) / (2 * Math.PI);
  },
  //取消关注事件
  del: function (e) {
    var that = this;
    var type = e.currentTarget.dataset['type'];
    var id = e.currentTarget.dataset['id'];
    var index = e.currentTarget.dataset['index'];
    tool.post('Follow/cancel',{id:id},function(result){
      var info = result.data;
      if(info.status == '1'){
        if(type == 1){
          var follow_teacher = that.data.follow_teacher;
          follow_teacher.splice(index,1);
          that.setData({
            follow_teacher: follow_teacher
          })
        }else{
          var follow_course = that.data.follow_course;
          follow_course.splice(index, 1);
          that.setData({
            follow_course: follow_course
          })
        }
        tool.jsalert(info.msg);
      }else{
        tool.jsalert(info.msg,2);
      }
    })
  },
  //去老师详情
  toTeacherDetail:function(e){
    var id = e.currentTarget.dataset['id'];
    wx.navigateTo({
      url: '/pages/teacher_detail/index?id='+id
    })
  },
  //去课程详情
  toCourseDetail:function(e){
    var id = e.currentTarget.dataset['id'];
    wx.navigateTo({
      url: '/pages/course_detail/index?id=' + id
    })
  }
})