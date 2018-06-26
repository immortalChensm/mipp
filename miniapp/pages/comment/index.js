var tool = require("../../utils/tool.js")
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    request:null,
    teacher_star:1,
    teacher_content: '',
    course_star:1,
    course_content: '',
    save_status:false
  },
  /**
   * 
   */
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
  saveCmt:function(){
    var that = this;
    // if (that.data.save_status) return false;
    // that.setData({
    //   save_status:true
    // })
    var postdata = {
      order_id:this.data.request.order_id,
      teacher_star: this.data.teacher_star,
      teacher_content: this.data.teacher_content,
      course_star: this.data.course_star,
      course_content: this.data.course_content
    }
    tool.post('Order/add_comment',postdata,function(result){
      var info = result.data;
      if(info.status == '1'){
        tool.jsalert(info.msg);
        setTimeout(function(){
          wx.navigateTo({
            url: '/pages/my_order/index?status=3'
          })
        },1200);
      }else{
        tool.jsalert(info.msg,2);
      }
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      request:options
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