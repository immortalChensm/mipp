// pages/附近老师/nearbyteacher.js
var tool = require("../../utils/tool.js")
var app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    bools: 1,
    boolss: 1,
    cur_page: 1,
    cur_type:'',
    cur_sale:1,
    cur_distance:'',
    showkecd:false,
    showkecda:false,
    teacher_list: [],
    teacher_type:[],
    default_type: '距离',

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onLoad: function (options) {
    //获取附近老师
    this.getStickTeacher();
    //获取类型
    this.getTypeTeacher();
  },

  // 全部课程筛选点击事件
  showok() {
    var that = this;
    if (that.data.bools == 1) {
      that.data.bools = 2;
      that.setData({
        showkecd: true
      })
    } else {
      that.data.bools = 1;
      that.setData({
        showkecd: false

      })
    }
  },
  showxl:function(){
    var that = this;
    if (that.data.boolss == 1) {
      that.data.boolss = 2;
      that.setData({
        showkecda: true
      })
    } else {
      that.data.boolss = 1;
      that.setData({
        showkecda: false
      })
    }
  }, 

  getStickTeacher: function () {
    var that = this;
    var postdata = {};
    postdata.p = this.data.cur_page;
    postdata.distance = this.data.cur_distance;
    wx.getLocation({
      success: function (res) {
        postdata.lng = res.longitude;
        postdata.lat = res.latitude;
        tool.post('Teacher/list', postdata, function (res) {
          // console.log(res); return false;
          that.setData({
            teacher_list: res.data.data
          })
        })
      },
    })
  },
  getTypeTeacher:function(){
    var that = this;
    tool.post('Teacher/teach_types',{}, function (res) {
      // console.log(res.data.data); return false;
      that.setData({
        teacher_type: res.data.data
      })
    })
  },
  // 根据条件获取列表
  searchList: function (event) {
    var opt_type = event.currentTarget.dataset['type'];
    var val = event.currentTarget.dataset['val'];
    this.setData({
      cur_page: 1,
      showkecd: true,
    })
    switch (opt_type) {
      case 'distance':
        var name = event.currentTarget.dataset['name'];
        var id = event.currentTarget.dataset['val'];
        this.setData({
          cur_distance: id,
          default_type: name,
          showkecd: false,
        });
        break;
    }
    this.getStickTeacher()
  },

  toTeacherDetail: function (event) {
    var teacher_id = 1;//event.currentTarget.dataset['id'];
    wx.navigateTo({
      url: '/pages/teacher_detail/index?id=' + teacher_id
    })
  },
})