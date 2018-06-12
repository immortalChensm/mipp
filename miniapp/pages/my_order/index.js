// pages/我的订单/myorder.js
Page({

  /**
   * 页面的初始数据
   */
  data: {


   /** 全部-待付款-待评价-已完成 -切换初始数据**/
    selecttaba:true,
    selecttabb: false,
    selecttabc: false,
    selecttabd: false
  },
  /** 全部-待付款-待评价-已完成 -切换**/
  orderTab1:function(){
    var that =this;
    that.setData({
      selecttaba: true,
      selecttabb: false,
      selecttabc: false,
      selecttabd: false
    })
  },
  orderTab2:function() {
    var that = this;
    that.setData({
      selecttaba: false,
      selecttabb: true,
      selecttabc: false,
      selecttabd: false
    })
  },
  orderTab3:function() {
    var that = this;
    that.setData({
      selecttaba: false,
      selecttabb: false,
      selecttabc: true,
      selecttabd: false
    })
  },
  orderTab4:function () {
    var that = this;
    that.setData({
      selecttaba: false,
      selecttabb: false,
      selecttabc: false,
      selecttabd: true
    })
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