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
    cancel_click:false,
    pay_click:false,
    comment_click:false
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    console.log(options)
    //检查登录
    if (!app.checkLogin()) {
      app.doLogin();
      return false;
    }
    var type = options.type;
    switch(type){
      case 'all': this.seleall();break;
      case 'pay': this.selepay(); break;
      case 'comment': this.selecomment(); break;
      case 'complete': this.selecomplete(); break;
      default: this.seleall()
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
    var order_id = e.currentTarget.dataset['id'];
    tool.post('Order/pre_pay_order', {order_id:order_id}, function (result) {
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
        tool.jsalert(info.msg,2);
        this.setData({
          pay_click: false
        })
      }
    })
  }, 
  /**
 * 取消订单
 */
  cancelOrder: function (e) {
    if (this.data.cancel_click) {
      return false;
    } else {
      this.setData({
        cancel_click: true
      })
    }
    var that = this;
    var order_id = e.currentTarget.dataset['id'];
    tool.post('Order/del',{id:order_id},function(result){
      var info = result.data;
      if(info.status == '1'){
        tool.jsalert(info.msg)
        that.getOrderList();
      }else{
        tool.jsalert(info.msg,2);
        this.setData({
          cancel_click: false
        })
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
    this.setData({
      cancel_click: false,
      pay_click: false,
      comment_click: false
    })
  },

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