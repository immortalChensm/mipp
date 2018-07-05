var tool = require("../../utils/tool.js")
const app = getApp()
Page({
  backpage: app.backpage,
  /**
   * 页面的初始数据
   */
  data: {
    navdata: {
      title: '金牌老师',
      backtype: 1
    },

    //初始数据
      teacher_list: [],
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
    this.setData({
      cur_page: 1,
      data_end: false,
      is_add:false
    })
    //获取推荐老师
    this.getStickTeacher();
  },
  /**
   * 获取推荐老师
   */
  getStickTeacher: function () {
    var that = this;
    wx.getLocation({
      success: function (res) {
        var postdata = {};
        postdata.lng = res.longitude;
        postdata.lat = res.latitude;
        postdata.p = that.data.cur_page;
        tool.post('Teacher/teacher_stick', postdata, function (result) {
          var info = result.data;
          //console.log(info)
          if (info.status == '1') {
              if(that.data.is_add){
                that.setData({
                  teacher_list: that.data.teacher_list.concat(info.data)
                })
              }else{
                that.setData({
                  teacher_list: info.data
                })
              }
          } else {
            that.setData({
              data_end: true
            })
          }
        })
      },
    })

  },
  /***
   * 去教师详情
   */
  toTeacherDetail: function (event) {
    var teacher_id = event.currentTarget.dataset['id'];
    wx.navigateTo({
      url: '/pages/teacher_detail/index?id=' + teacher_id
    })
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  //滚动底部监听
  onReachBottom: function () { //下拉刷新
    var cur_page = this.data.cur_page;
    if (!this.data.data_end) {
      this.setData({
        cur_page: cur_page + 1,
        is_add:true
      })
      wx.showToast({
        title: '加载中',
        icon: 'loading',
        duration: 1000,
      });
      this.getStickTeacher();
    }
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
    this.onShow();
    wx.stopPullDownRefresh();
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