var tool = require("../../utils/tool.js")

// pages/确认订单/确认订单.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    'course_info':[],
    'form_phone': '',
    'count':1,
    'total_price': 0,
    'form_name':''
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
    console.log(this.data)
    var postdata = {
      'type':1,
      'course_id':this.data.course_info.id,
      'name': this.data.form_name,
      'phone': this.data.form_phone,
      'goods_num': this.data.count
    }
    tool.post('Order/add_order',postdata,function(result){
      var info = result.data;
      console.log(info)
      if(info.status == '1'){
        var res = JSON.parse(info.data);
        console.log(res)
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
              url: '/pages/my_order/index'
            })
          },
          fail: function (res) {
            console.log(res);
          }
        })
      }else{
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
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  }
})