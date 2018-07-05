var tool = require("../../utils/tool.js")
var app = getApp()
Page({
  backpage: app.backpage,
  /**
   * 页面的初始数据
   */
  data: {
    navdata: {
      title: '申请老师',
      backtype: 1
    },
    is_edit:true,
    lng:null,
    lat:null,
    address:'',
    qualification:'',
    fcard:'',
    bcard:'',
    show_fcard:false,
    show_bcard:false,
    show_qualification: false,
    teacher_info:[]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    //获取老师信息
    this.getTeacherInfo();
  },
  /**
   * 获取老师信息
   */
  getTeacherInfo:function(){
    var that = this;
    tool.post('Teacher/apply_teacher',{act:'show'},function(result){
      console.log(result)
      var info = result.data;
      if(info.status == '1'){
        var teacher_info = info.data;
        var is_edit = teacher_info.status == 3?false:true;
        var show_fcard = teacher_info.fcard ? true :false;
        var show_bcard = teacher_info.bcard ? true : false;
        var show_qualification = teacher_info.qualification ? true : false;
        that.setData({
          is_edit: is_edit,
          lng: teacher_info.lng,
          lat: teacher_info.lat,
          address: teacher_info.address,
          qualification: teacher_info.qualification,
          fcard: teacher_info.fcard,
          bcard: teacher_info.bcard,
          teacher_info: teacher_info,
          show_fcard: show_fcard,
          show_bcard: show_fcard,
          show_qualification: show_qualification
        })
      }
    })
  },
  /**
   * 申请表单提交
   */
  formSubmit:function(e){
    if(!this.data.is_edit) return false;
    var form_data = e.detail.value;
    console.log(form_data)
    form_data.act = 'save';
    tool.post('Teacher/apply_teacher', form_data,function(result){
      var info = result.data;
      if(info.status == '1'){
        tool.jsalert(info.msg)
        setTimeout(function(){
          wx.switchTab({
            url: '/pages/user_center/index',
          })
        },1200)
      }else{
        tool.jsalert(info.msg,2)
      }
    }, null, 2)
  },
  /**
   * 选择地理位置
   */
  chooseAddress: function (e) {
    if (!this.data.is_edit) return false;
    var that = this;
    wx.chooseLocation({
      success: function (res) {
        console.log(res);
        if (res.errMsg == "chooseLocation:ok"){
          var address = res.address + res.name
          that.setData({
            lng: res.longitude,
            lat: res.latitude,
            address: address
          })
        }
      }
    })
  },
  /**
 * 选择图片
 */
  upImage: function (e) {
    if (!this.data.is_edit) return false;
    var that = this;
    var update_data = {};
    var upfield = e.currentTarget.dataset['field'];
    wx.chooseImage({
      count:1,
      success: function(res) {
        if (res.errMsg == "chooseImage:ok"){
          tool.uploadfile(res.tempFilePaths[0],function(data){
            if(data.status == '1'){
              update_data[upfield] = data.data.image_url;
              update_data['show_' + upfield] = true;
              that.setData(update_data)
            }else{
              tool.jsalert(data.msg,2);
            }
          })
        }
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
  onShareAppMessage: function (res) {
    if (res.from === 'button') {
      // 来自页面内转发按钮
      console.log(res.target)
    }
    return app.shareApp();
  }
})