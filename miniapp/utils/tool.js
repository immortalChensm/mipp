
var API_URL = 'https://edu.mppstore.com/Api/'

//post请求
function post(url,data,suc,fail){
    request('POST',url,data,suc,fail);
}

//get请求
function get(url,suc,fail){
    request('GET',url,null,suc,fail);
}

//request
function request(method,url,data,suc,fail){
	var request_obj = {
	        url: API_URL+url+'?ajax=1',
	        method: method, // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
		    success: suc,
		    fail: fail
	    }
	if(method == 'POST'){
		request_obj.header = {'Content-Type': 'application/x-www-form-urlencoded'};
    var userinfo = wx.getStorageSync('userinfo');
    if(userinfo.openId){
      data.openid = userinfo.openId;
    }
    request_obj.data = data;
	}else if(method == 'GET'){
		request_obj.header = {'Content-Type': 'application/json'};
    var userinfo = wx.getStorageSync('userinfo');
    if (userinfo.openId) {
      request_obj.data = {openid: userinfo.openId};
    }
	}
  wx.request(request_obj);
}

//消息弹出框
function jsalert(msg,tiptype){
    var toast_type = 'success';
    switch (tiptype){
    	case 1:toast_type = 'success';break;
    	case 2:toast_type = 'none';break;
    	case 3:toast_type = 'load';break;
    }
    wx.showToast({
      title: msg,
      icon: toast_type,
      duration: 1200,
    })
}

//消息确认框
function jsconfirm(msg,suc,fail,sucbtntext,failbtntext) {
    sucbtntext = sucbtntext == null || sucbtntext == 'undefined' ? '确认' : sucbtntext;
    failbtntext = failbtntext == null || failbtntext == 'undefined' ? '取消' : failbtntext;
    if (typeof (suc) != 'function'){
       suc = function(){
    	   wx.hideToast();
       }
    }
    if (typeof (fail) != 'function'){
	    fail = function(){
		    wx.hideToast();
	    }
    }
    wx.showModal({
	    title: '提示信息',
	    content: msg,
	    showCancel: true,
	    cancelText: failbtntext,
	    confirmText: sucbtntext,
	    success: suc,
	    fail: fail,
    })
}

//上传图片
function uploadfile(path,backfun){
  //启动上传等待中...
  wx.showToast({
    title: '正在上传...',
    icon: 'loading',
    mask: true,
    duration: 10000
  })
  // console.log(path)
  wx.uploadFile({
    url: API_URL + 'Upload/upImage',
    filePath: path,
    name: 'upfile',
    header: {
      "Content-Type": "multipart/form-data"
    },
    success: function (res) {
      var data = JSON.parse(res.data);
      wx.hideToast();
      backfun(data)
    },
    fail: function (res) {
      wx.hideToast();
      wx.showModal({
        title: '错误提示',
        content: '上传图片失败',
        showCancel: false
      })
    }
  });
}

module.exports = {
  get: get,
  post: post,
  jsalert: jsalert,
  jsconfirm: jsconfirm,
  uploadfile: uploadfile
}