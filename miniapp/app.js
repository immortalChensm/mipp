var tool = require("./utils/tool.js")
App({
  onLaunch: function (options) {
	  var that = this;
	  wx.checkSession({
		  success: function(){
        // wx.clearStorage();
		    //session_key 未过期，并且在本生命周期一直有效
		  },
		  fail: function(){
			//重新登录
		  	wx.login({
		      success: function(res) {
		        if (res.code) {
		          //发起网络请求
		          tool.post('Base/getSessionKey',{code: res.code},function(result){
                var returninfo = result.data;
                if (returninfo.status == '1'){
                  wx.setStorageSync('openid', returninfo.data.openid);
                  wx.setStorageSync('session_key', returninfo.data.session_key);
                  wx.navigateTo({
                    url: '/pages/auth/index'
                  })
                }else{
                  tool.jsalert('登录失败',2)
                }
		          })
		        } else {
		          console.log('登录失败！' + res.errMsg)
		        }
		      }
		    });
		  }
	  })
	},
  onShow:function(){
    var session_key = wx.getStorageSync('session_key');
    if (!session_key) {
      //发起网络请求
      wx.login({
        success: function (res) {
          if (res.code) {
            //发起网络请求
            tool.post('Base/getSessionKey', { code: res.code }, function (result) {
              var returninfo = result.data;
              if (returninfo.status == '1') {
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

    }
  },
	globalData: {
		apiurl:'http://educate.com/Api/',
	},
})
