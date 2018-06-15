// pages/附近老师/nearbyteacher.js
var tool = require("../../utils/tool.js")
var app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    cur_page: 2,
    cur_type:'',
    cur_sale:1,
    cur_distance:'',
    hidden_showa:false,
    hidden_showb:false,
    teacher_list: [],
    teacher_type:[],
    default_type: '距离',
    type_name: '茶艺',
    showkecda: false,
    showkecdb: false,
    showkecdab:false,
    showkecdbc:false,
    hidden_typea: true,
    hidden_typeb: true,
    cur_status:2

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
  showa: function () {
    var that = this;
    if (that.data.hidden_typea) {
      that.setData({
        showkecda: true,
        showkecdab: true,
        showkecdb: false,
        showkecdc: false,
        hidden_typea: false,
        hidden_typeb: true,
        hidden_typec: true,


      })
    } else {
      that.setData({
        hidden_typea: true,
        showkecda: false,
        showkecdab: false
      })
    }
  },
  showb: function () {
    var that = this;
    if (that.data.hidden_typeb) {
      that.setData({
        showkecda: false,
        showkecdbc: true,
        showkecdb: true,
        showkecdc: false,
        hidden_typea: true,
        hidden_typeb: false,
        hidden_typec: true,

      })
    } else {
      that.setData({
        hidden_typeb: true,
        showkecdb: false,
        showkecdbc: false
      })
    }
  },

  getStickTeacher: function () {
    var that = this;
    var postdata = {};
    postdata.distance = this.data.cur_distance; 
    postdata.teach_type = this.data.cur_type;
    postdata.sale = this.data.cur_sale;
    wx.getLocation({
      success: function (res) {
        postdata.lng = res.longitude;
        postdata.lat = res.latitude;
        tool.post('Teacher/teacher_list', postdata, function (res) {
          if (res.data.status =='1') {
          that.setData({
              teacher_list: res.data.data
          })
          }else{
            that.setData({
              teacher_list: []
            })
          }
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
      showkecda: true,
      showkecdb: true,
    })
    switch (opt_type) {
      case 'distance':
        var name = event.currentTarget.dataset['name'];
        var id = event.currentTarget.dataset['val'];
        this.setData({
          cur_distance: id,
          default_type: name,
          showkecdb: false,
          showkecda:false,
          showkecdab: false,
          hidden_typea: true
          // bools: 1,
          
        });
        break;
      case 'type':
        var name = event.currentTarget.dataset['name'];
        var id = event.currentTarget.dataset['val'];
        this.setData({
          cur_type: id,
          type_name: name,

          showkecdbc: false,
          showkecdb: false,
          hidden_typeb:true,
          showkecda:false
          // boolss: 1
        });
        break;
      case 'sale':
        var name = event.currentTarget.dataset['name'];
        var id = event.currentTarget.dataset['val'];
        this.setData({
          cur_sale: id=='1'?'2':'1',
          showkecdb: false,
          showkecda: false,
          bools: 1,
          boolss: 1
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

  //滚动底部监听
  onReachBottom: function () { //下拉刷新
    var that = this;
    var cur_page = that.data.cur_page;
    wx.showToast({
      title: '加载中',
      icon: 'loading',
      duration: 3000
    });
    console.log(that.data.cur_page);
    tool.post('Teacher/teacher_list', { "p": ++cur_page }, function (res) {
        if (res.data.status == '1') {
          that.setData({
            teacher_list: that.data.teacher_list.concat(res.data.data),
            cur_page:cur_page,
          })
        }
        setTimeout(function () { wx.hideToast(); }, 1000);
    })

  }
})