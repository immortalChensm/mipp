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
  * 去支付
  */
  payOrder: function (e) {
    var order_id = e.currentTarget.dataset['id'];
    tool.post('Order/pre_pay_order', { order_id: order_id }, function (result) {
      var info = result.data;
      console.log(info)
      if (info.status == '1') {
        var res = JSON.parse(info.data);
        console.log(res)
        wx.requestPayment({
          timeStamp: res.timeStamp,
          nonceStr: res.nonceStr,
          package: res.package,
          signType: res.signType,
          paySign: res.paySign,
          success: function (res) {
            wx.navigateTo({
              url: '/pages/my_order/index'
            })
          },
          fail: function (res) {
            console.log(res);
          }
        })
      } else {
        tool.jsalert(info.msg);
      }
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