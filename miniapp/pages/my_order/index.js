var tool = require("../../utils/tool.js")
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    showall:true,
    showpay: false,
    showcomment: false,
    showcomplete: false,
    list_status:'',
    list_page:1,
    list_info:[],
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var wait_pay_count = wx.getStorageSync('wait_pay_count');
    if (wait_pay_count > 0){
      this.selepay();
    }else{
      this.seleall();
    }
  },
  seleall: function () {
    var that = this;
    that.setData({
      list_status: '',
      showall: true,
      showpay: false,
      showcomment: false,
      showcomplete: false
    })
    that.getOrderList()
  },
  selepay: function () {
    var that = this;
    that.setData({
      list_status: '1',
      showall: false,
      showpay: true,
      showcomment: false,
      showcomplete: false
    })
    that.getOrderList()
  },
  selecomment: function () {
    var that = this;
    that.setData({
      list_status: '2',
      showall: false,
      showpay: false,
      showcomment: true,
      showcomplete: false
    })
    that.getOrderList()
  },
  selecomplete: function () {
    var that = this;
    that.setData({
      list_status: '3',
      showall: false,
      showpay: false,
      showcomment: false,
      showcomplete: true
    })
    that.getOrderList()
  },
  getOrderList: function () {
    var that = this;
    tool.post('Order/index', { status: this.data.list_status, p: this.data.list_page }, function (result) {
      var info = result.data;
      console.log(info)
      if(info.status == '1'){
        that.setData({
          list_info: info.data
        })
      }else{
        tool.jsalert(info.msg,2);
        that.setData({
          list_info: []
        })
      }
    })
  },
  toOrderDetail:function(e){
    var order_id = e.currentTarget.dataset['id'];
    wx.navigateTo({
      url: '/pages/order_detail/index?order_id='+order_id,
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