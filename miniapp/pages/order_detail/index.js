var tool = require("../../utils/tool.js")
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    request: null,
    order_info:[]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      'request': options
    })
    console.log(options)
    //获取订单详情
    this.getOrderInfo();
  },
    /**
   * 获取订单详情
   */
  getOrderInfo:function(){
    var that = this;
    tool.post('Order/detail',{id:this.data.request.order_id},function(result){
      var info = result.data;
      console.log(info);
      that.setData({
        order_info:info.data
      })
    })
  },
  /**
   * 去评价
   */
  toComment: function (e) {
    var order_id = e.currentTarget.dataset['id'];
    wx.navigateTo({
      url: '/pages/comment/index?order_id='+order_id
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
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  }
})