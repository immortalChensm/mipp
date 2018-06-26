var tool = require("../../utils/tool.js")

Page({

  /**
   * 页面的初始数据
   */
  data: {
    'course_info': [],
    'form_phone': '',
    'form_name': ''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var course_info = wx.getStorageSync('cart_info');
    var phone = wx.getStorageSync('phone');
    this.setData({
      'course_info': course_info,
      'form_phone': phone
    })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },
  /**
   * 添加订单
   */
  yuyue_order: function () {
    console.log(this.data)
    var postdata = {
      'type': 2,
      'course_id': this.data.course_info.id,
      'name': this.data.form_name,
      'phone': this.data.form_phone,
      'goods_num': 1
    }
    tool.post('Order/add_order', postdata, function (result) {
      var info = result.data;
      console.log(info)
      if (info.status == '1') {
        wx.navigateTo({
          url: '/pages/yuyue_suc/index'
        })
      } else {
        tool.jsalert(info.msg);
      }
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