var tool = require("../../utils/tool.js")
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    request_data:{},
    showall:false,
    showpay: false,
    showcomment: false,
    showcomplete: false,
    list_status:'',
    list_page:1,
    list_info:[],
    cancel_click:false,
    pay_click:false,
    comment_click:false,
    type:''
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    //检查登录
    if (!app.checkLogin()) {
      app.doLogin();
      return false;
    }
    if (options){
      this.setData({
        request_data: options
      })
    }
  },
  seletab:function(e){
    var type = e.currentTarget.dataset['type'];
    wx.navigateTo({
      url: '/pages/my_order/index?type=' + type,
    })
  },
  getOrderList: function () {
    var that = this;
    tool.post('Order/index', { status: this.data.list_status, p: this.data.list_page }, function (result) {
      var info = result.data;
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
 * 去评价
 */
  toComment: function (e) {
    if (this.data.comment_click) {
      return false;
    } else {
      this.setData({
        comment_click: true
      })
    }
    var order_id = e.currentTarget.dataset['id'];
    wx.navigateTo({
      url: '/pages/comment/index?order_id=' + order_id
    })
  }, 
  /**
  * 去支付
  */
  payOrder: function (e) {
    if(this.data.pay_click){
      return false;
    }else{
      this.setData({
        pay_click:true
      })
    }
    var that = this;
    var order_id = e.currentTarget.dataset['id'];
    tool.post('Order/pre_pay_order', {order_id:order_id}, function (result) {
      var info = result.data;
      if (info.status == '1') {
        var res = JSON.parse(info.data);
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
            that.setData({
              pay_click: false
            })
          }
        })
      } else {
        tool.jsalert(info.msg,2);
        that.setData({
          pay_click: false
        })
      }
    })
  }, 
  /**
 * 取消订单
 */
  cancelOrder: function (e) {
    var that = this;
    tool.jsconfirm('确定要取消此订单吗？',function(){
      var order_id = e.currentTarget.dataset['id'];
      tool.post('Order/del', { id: order_id }, function (result) {
        var info = result.data;
        if (info.status == '1') {
          tool.jsalert(info.msg)
          that.getOrderList();
        } else {
          tool.jsalert(info.msg, 2);
        }
      })
    });
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
    wx.showToast({
      title: '',
      icon:'loading',
      duration:100000
    })
    var request_data = this.data.request_data;
    var type = request_data.type ? request_data.type : this.data.type;
    this.setData({
      showall: false,
      showpay: false,
      showcomment: false,
      showcomplete: false,
      list_status: '',
      list_page: 1,
      list_info: [],
      cancel_click: false,
      pay_click: false,
      comment_click: false,
      type: type
    })
    console.log(request_data)
    var set_data = {};
    switch (type) {
      case 'all': set_data = { showall: true , list_status: '' }; break;
      case 'pay': set_data = { showpay: true , list_status: '1' }; break;
      case 'comment': set_data = { showcomment: true , list_status: '2'}; break;
      case 'complete': set_data = { showcomplete: true , list_status: '3'}; break;
      default: set_data = { showall: true }
    }
    this.setData(set_data);
    this.getOrderList()
    wx.hideToast()
;  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function (e) {
    // console.log(e);
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
    wx.switchTab({
      url: '/pages/user_center/index'
    })
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