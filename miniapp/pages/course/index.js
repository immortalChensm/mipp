var tool = require("../../utils/tool.js")
var app = getApp()
Page({
  /**
    * 页面的初始数据
    */
  data: {
    hidden_type: true,
    hidden_sale_count: true,
    hidden_price: true,
    sele_type: false,
    sele_sale_count: false,
    sele_price: false,
    cur_type: '',
    cur_price: '',
    cur_sale_count: '',
    cur_page: 1,
    default_type: '全部课程',
    course_list: [],
    course_types: [],
    is_add: true,
    data_end:false
  },
  switchType: function () {
    var that = this
    if (that.data.hidden_type) {
      that.setData({
        hidden_type: false,
        sele_type: true,
        hidden_sale_count: true,
        hidden_price: true,
        sele_sale_count: false,
        sele_price: false,
      })
    } else {
      that.setData({
        hidden_type: true,
        sele_type: false
      })
    }
  },
  switchSaleCount: function () {
    var that = this
    if (that.data.hidden_sale_count) {
      that.setData({
        hidden_sale_count: false,
        sele_sale_count: true,
        hidden_type: true,
        hidden_price: true,
        sele_type: false,
        sele_price: false,
      })
    } else {
      that.setData({
        hidden_sale_count: true,
        sele_sale_count: false
      })
    }
  },
  switchPrice: function () {
    var that = this
    if (that.data.hidden_price) {
      that.setData({
        hidden_price: false,
        sele_price: true,
        hidden_type: true,
        hidden_sale_count: true,
        sele_type: false,
        sele_sale_count: false
      })
    } else {
      that.setData({
        hidden_price: true,
        sele_price: false
      })
    }
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function () {
    //看是否携带参数
    var course_type = wx.getStorageSync('course_type');
    wx.removeStorageSync('course_type');
    if (course_type) {
      this.setData({
        cur_type: course_type
      })
    }
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function (options) {
    var course_type = wx.getStorageSync('course_type');
    wx.removeStorageSync('course_type');
    if (course_type) {
      this.setData({
        cur_type: course_type,
        is_add: false,
        data_end:false,
        cur_page: 1
      })
    }
    //获取课程分类
    this.courseType();
    //加载课程列表
    this.courseList();
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
  //滚动底部监听
  onReachBottom: function () { //下拉刷新
    var cur_page = this.data.cur_page;
    if (!this.data.data_end){
      this.setData({
        cur_page: cur_page + 1,
        is_add: true
      })
      wx.showToast({
        title: '加载中',
        icon: 'loading',
        duration: 1000,
      });
      this.courseList();
    }
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
   * 获取课程列表
   */
  courseList: function () {
    var that = this;
    var postdata = {};
    postdata.sort_sale_count = this.data.cur_sale_count;
    postdata.sort_price = this.data.cur_price;
    postdata.type = this.data.cur_type;
    postdata.p = this.data.cur_page;
    tool.post('Course/course_list', postdata, function (result) {
      var info = result.data;
      //console.log(info)
      if (info.status == '1') {
        var list = that.data.is_add ? that.data.course_list.concat(info.data) : info.data;
        that.setData({
          course_list: list
        })
      } else {
        if (that.data.is_add){
          that.setData({
            data_end: true
          })
        }else{
          that.setData({
            course_list: []
          })
        }

        console.log(info.msg, 2)
      }
    })
  },
  //根据条件获取列表
  searchList: function (event) {
    var opt_type = event.currentTarget.dataset['type'];
    var val = event.currentTarget.dataset['val'];
    this.setData({
      cur_page: 1,
      hidden_type: true,
      hidden_sale_count: true,
      hidden_price: true,
      is_add: false,
      data_end:false
    })
    switch (opt_type) {
      case 'type':
        var name = event.currentTarget.dataset['name'];
        this.setData({
          cur_type: val,
          default_type: name,
          sele_type: false,
        });
        break;
      case 'sale_count':
        this.setData({
          cur_sale_count: val,
          cur_price: '',
          sele_sale_count: false,
        });
        break;
      case 'price':
        this.setData({
          cur_price: val,
          cur_sale_count: '',
          sele_price: false,
        });
        break;
    }
    this.courseList();
  },
  toCourseDetail: function (event) {
    var course_id = event.currentTarget.dataset['id'];
    wx.navigateTo({
      url: '/pages/course_detail/index?id=' + course_id
    })
  },
  /**
* 获取课程分类
*/
  courseType: function () {
    var that = this;
    tool.get('Course/course_types', function (result) {
      var info = result.data;
      //console.log(info)
      if (info.status == '1') {
        if (that.data.cur_type != '') {
          for (var i = 0; i < info.data.length; i++) {
            if (info.data[i].id == that.data.cur_type) {
              that.setData({
                default_type: info.data[i].name
              })
            }
          }
        }
        that.setData({
          course_types: info.data
        })
      } else {
        console.log(info.msg, 2)
      }
    })
  },
})