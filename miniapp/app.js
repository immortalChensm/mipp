var tool = require("./utils/tool.js")
App({
  onLaunch: function (options) {
    //检查登录session
    var that = this;
    wx.checkSession({
      success: function () {
        // wx.clearStorage();
        //session_key 未过期，并且在本生命周期一直有效
      },
      fail: function () {
        //登录
        that.doLogin();
      }
    })
	},
  onShow:function(){
    // if (!this.checkLogin()) {
    //   this.doLogin();
    // }
  },
  /**
   * 检查登录
   */
  checkLogin:function(){
    var openid = wx.getStorageSync('openid');
    if (!openid) {
      return false;
    }else{
      return true;
    }
  },
  /**
   * 执行登录
   */
  doLogin:function(){
    wx.login({
      success: function (res) {
        if (res.code) {
          //发起网络请求
          tool.post('Base/getSessionKey', { code: res.code }, function (result) {
            var returninfo = result.data;
            if (returninfo.status == '1') {
              console.log(returninfo)
              wx.setStorageSync('openid', returninfo.data.openid);
              wx.setStorageSync('session_key', returninfo.data.session_key);
              wx.navigateTo({
                url: '/pages/auth/index'
              })
            } else {
              tool.jsalert('登录失败', 2)
            }
          })
        } else {
          console.log('登录失败！' + res.errMsg)
        }
      }
    });
  },
  /**
   * 分享
   */
  shareApp:function(title,path,image_url,suc,fail){
    var title = title ? title : '【艺术教育平台】好老师成就好学生，好课程铸就新梦想';
    var path = path ? path : '/pages/index/index';
    var image_url = image_url ? image_url : '/assets/images/logo.jpg';
    return {
      title: title,
      path: path,
      imageUrl: image_url,
      success: function (res) {
        // 转发成功
        if (typeof suc == 'function') suc();
      },
      fail: function (res) {
        // 转发失败
        if (typeof fail == 'function') fail();
      }
    };
  }
})
