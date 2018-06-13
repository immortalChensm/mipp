var tool = require("../../utils/tool.js")
var app = getApp()
Page({
  /**
    * 页面的初始数据
    */
  data: {
    hidden_type:true,
    hidden_sale_count: true,
    hidden_price: true,
    sele_type: false,
    sele_sale_count: false,
    sele_price: false,
    default_type:'全部课程',
    course_list: [],
    course_types: [],
    course_sort:'',
    course_type: '',
    course_page: 1
  },
  switchType:function(){
    var that = this
    if(that.data.hidden_type){
      that.setData({
        hidden_type:false,
        sele_type:true,
        hidden_sale_count: true,
        hidden_price: true,
        sele_sale_count: false,
        sele_price: false,
      })
    }else{
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
  onLoad: function (options) {
    //加载课程列表
    this.courseList();
    //获取课程分类
    this.courseType();
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
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  },
  /**
   * 获取课程列表
   */
  courseList:function(){
    var that = this;
    var postdata = {};
    postdata.sort_type = this.data.course_sort;
    postdata.type = this.data.course_type;
    postdata.p = this.data.course_page;
    tool.post('Course/course_list',postdata,function(result){
      var info = result.data;
      //console.log(info)
      if(info.status == '1'){
        that.setData({
          course_list:info.data
        })
      }else{
        console.log(info.msg,2)
      }
    })
  },
      /**
   * 获取课程分类
   */
  courseType:function() {
    var that = this;
    tool.get('Course/course_types', function (result) {
      var info = result.data;
      //console.log(info)
      if (info.status == '1') {
        that.setData({
          course_types: info.data
        })
      } else {
        console.log(info.msg, 2)
      }
    })
  },
})