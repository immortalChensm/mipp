var tool = require("../../utils/tool.js")
const app = getApp()
// pages/确认订单/确认订单.js
Page({
  backpage: app.backpage,
  /**
   * 页面的初始数据
   */
  data: {
    navdata: {
      title: '确认订单',
      imgurl: app.global_data.backimg,
      pageurl: '',
      showbtn: 1
    },
    course_info:[],
    form_phone: '',
    count:1,
    total_price: 0,
    form_name:'',
    pay_click:false
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var course_info = wx.getStorageSync('cart_info');
    var phone = wx.getStorageSync('phone');
    var total_price = this.data.count*course_info.price;
    this.setData({
      'course_info':course_info,
      'form_phone':phone,
      'total_price': total_price
    })
  },
  add: function () {
    var now_count = this.data.count + 1 ;
    var total_price = this.data.course_info.price*now_count;
    this.setData({
      'count': now_count,
      'total_price': total_price
    })
  },
  reduce:function(){
    var now_count = this.data.count <= 1 ? 1 : this.data.count-1;
    var total_price = this.data.course_info.price * now_count;
    this.setData({
      'count': now_count,
      'total_price': total_price
    })
  },
  setFormName: function (e) {
    this.setData({
      'form_name': e.detail.value
    })
  },
  setFormPhone: function (e) {
    this.setData({
      'form_phone': e.detail.value
    })
  },
  /**
   * 添加订单
   */
  add_order:function(){
    if (this.data.pay_click){
      return false;
    }else{
      this.setData({
        pay_click: true
      })
    }
    var postdata = {
      'type':1,
      'course_id':this.data.course_info.id,
      'name': this.data.form_name,
      'phone': this.data.form_phone,
      'goods_num': this.data.count
    }
    tool.post('Order/add_order',postdata,function(result){
      var info = result.data;
      if(info.status == '1'){
        var res = JSON.parse(info.data);
        wx.requestPayment({
          timeStamp: res.timeStamp,
          nonceStr: res.nonceStr,
          package: res.package,
          signType: res.signType,
          paySign: res.paySign,
          success: function (res) {
            // wx.showModal({
            //   title: '支付成功',
            //   content: '',
            // })
            wx.navigateTo({
              url: '/pages/my_order/index?type=comment'
            })
          },
          fail: function (res) {
            wx.navigateTo({
              url: '/pages/my_order/index?type=pay'
            })
          }
        })
      }else{
        tool.jsalert(info.msg,2);
        this.setData({
          pay_click: false
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
      pay_click:false
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