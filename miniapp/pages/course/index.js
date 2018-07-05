var tool = require("../../utils/tool.js")
var app = getApp()
Page({
  backpage: app.backpage,
  /**
    * 页面的初始数据
    */
  data: {
    navdata: {
      title: '课程列表',
      backtype: 0
    },
    hidden_type: true,
    hidden_sale_count: true,
    hidden_price: true,
    sele_type: false,
    sele_sale_count: false,
    sele_price: false,
    showkecd:false,
    cur_type: '',
    cur_price: '',
    cur_sale_count: '',
    cur_page: 1,
    default_type: '全部课程',
    course_list: [],
    course_types: [],
    is_add: true,
    data_end:false,
    is_load:true
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
        showkecd: true
        
      })
    } else {
      that.setData({
        hidden_type: true,
        sele_type: false,
        showkecd: false
      })
    }
  },
  switchSaleCount: function () {
    var that = this
    that.setData({
      hidden_sale_count: true,
      hidden_type: true,
      hidden_price: true,
      sele_type: false,
      sele_price: false,
      cur_price: '',
      showkecd: false
    })
    if (!that.data.sele_sale_count) {
      that.setData({
        sele_sale_count: true,
        cur_sale_count: 1
      })
    } else {
      that.setData({
        sele_sale_count: false,
        cur_sale_count: 2
      })
    }
    this.courseList();
  },
  switchPrice: function () {
    var that = this
    that.setData({
      hidden_sale_count: true,
      hidden_type: true,
      hidden_price: true,
      sele_type: false,
      sele_sale_count: false,
      cur_sale_count: '',
      showkecd: false
    })
    if (!that.data.sele_price) {
      that.setData({
        sele_price: true,
        cur_price: 1
      })
    } else {
      that.setData({
        sele_price: false,
        cur_price: 2
      })
    }
    this.courseList();
  },
  /**
   * 取消筛选
   */
  cancel_sele:function(){
    this.setData({
      hidden_type: true,
      hidden_sale_count: true,
      hidden_price: true,
      sele_type: false,
      sele_sale_count: false,
      sele_price: false,
      showkecd: false
    })
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
    wx.showToast({
      title: '',
      duration:10000,
      icon:'loading'
    })
   this.setData({
     hidden_type: true,
     hidden_sale_count: true,
     hidden_price: true,
     sele_type: false,
     sele_sale_count: false,
     sele_price: false,
     showkecd: false,
     cur_type: '',
     cur_price: '',
     cur_sale_count: '',
     cur_page: 1,
     default_type: '全部课程',
     is_add: false,
     data_end: false,
     is_load:true
   });
    var course_type = wx.getStorageSync('course_type');
    wx.removeStorageSync('course_type');
    this.setData({
      cur_type: course_type,
      is_add: false,
      data_end: false,
      cur_page: 1,
      showkecd: false,
      hidden_type: true,
      hidden_sale_count: true,
      hidden_price: true
    })

    //获取课程分类
    this.courseType();
    //加载课程列表
    this.courseList();
    
    wx.hideToast();
    this.setData({
      is_load: false
    })
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
    this.onLoad();
    this.onShow();
    wx.stopPullDownRefresh();
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
      data_end:false,
      showkecd:false,
      sele_price:false
    })
    switch (opt_type) {
      case 'type':
        var name = event.currentTarget.dataset['name'];
        this.setData({
          cur_type: val,
          default_type: name,
          sele_type: false
        });
        break;
      case 'sale_count':
        
        break;
      case 'price':
        this.setData({
          cur_price: val,
          cur_sale_count: ''
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