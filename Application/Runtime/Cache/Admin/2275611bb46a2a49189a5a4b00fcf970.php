<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>C2C艺术教育平台</title>
    <link rel="stylesheet" href="/Public/Admin/css/login-style.css"/>
    <script src="/Public/Admin/js/common.js"></script>
    <style>
        .login-warp{ position: absolute;top: 0;left: 0;right: 0;bottom: 0;margin: auto; }
        .login-top{ position: absolute;top: 0;left: 0;width:100%;height: 55%;background: url(/Public/images/login-bg.png) no-repeat bottom; }
        .login-bottom{ position: absolute;bottom: 0;left: 0;width:100%;height: 45%;background-color:#fff; }
        .login-box{ position: absolute;top: 0;left: 0;right: 0;bottom: 0;margin: auto;width: 574px;height: 424px;background: url(/Public/images/login-boxbg.png) no-repeat center; z-index: 10;}
        .login-boxtop{ width: 100%;height: 138px;line-height: 138px;font-size: 28px;color: #25cc5a;text-align: center }
        .login-top img{ width: 80px;display: block;position: absolute;top: 12%;left: 0;right: 0;margin: auto;}
        .box-form{ width: 360px;height: 220px;margin: 0 auto; }
        .box-form label{ width: 100%;display: block;height: 50px;margin-bottom: 20px;position: relative;}
        .box-form input{ display: block;width: 100%;height: 100%;padding: 0 60px;line-height: 48px;border-radius: 5px;border: 1px solid #e4e4e4; }
        .box-form span{ position: absolute;font-size: 16px;color: #989898;top: 0;bottom: 0;line-height: 50px;width: 60px;text-align: center;margin: auto;}
        .box-form i{ position: absolute;top: 0;bottom: 0;right:10px;margin: auto;width: 24px;height:24px;text-align: center;background-position: center;background-repeat: no-repeat;background-size: contain; }
        .box-form button{ display: block;width: 100%;height: 50px;line-height: 48px;border-radius:5px;text-align: center;color: #fff;font-size: 18px;background-color: #25cc5a;border: none;margin-top: 50px;cursor: pointer; }
    </style>
    <script>    
    function detectBrowser()
    {
	    var browser = navigator.appName
	    if(navigator.userAgent.indexOf("MSIE")>0){ 
		    var b_version = navigator.appVersion
			var version = b_version.split(";");
			var trim_Version = version[1].replace(/[ ]/g,"");
		    if ((browser=="Netscape"||browser=="Microsoft Internet Explorer"))
		    {
		    	if(trim_Version == 'MSIE8.0' || trim_Version == 'MSIE7.0' || trim_Version == 'MSIE6.0'){
		    		alert('请使用IE9.0版本以上进行访问');
		    		return;
		    	}
		    }
	    }
   }
    detectBrowser();
   </script>
</head>
<body>
<div class="edu-fulid">
     <div class="edu-box">
         <div class="edu-login-title">
             <h1>C2C艺术教育平台</h1>
         </div>

         <div class="edu-container">
             <div class="edu-login-action">
                 <p>
                     <label>用户名：</label>
                     <input id="username" type="text" name="username" />
                 </p>
                 <p>
                     <label>密&nbsp;&nbsp;&nbsp;码：</label>
                     <input id="password" type="password" name="password" />
                 </p>
                 <p>
                     <button style="cursor:pointer" onclick="checkLogin()">登录</button>
                 </p>
             </div>
         </div>
     </div>

</div>
<!-- jQuery 2.1.4 -->
<script src="/Public/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="/Public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- iCheck -->
<script src="/Public/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="/Public/js/layer/layer.js"></script>

<script type="text/javascript">
$(function () {
  $('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' // optional
  });
});

jQuery.fn.center = function () {
    this.css("position", "absolute");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft()) + "px");
    return this;
}

function checkLogin(){
    var username = $('#username').val();
    var password = $('#password').val();
    if( username == '' || password ==''){
  	  jsalert('用户名或密码不能为空', 2); //alert('用户名或密码不能为空');
  	  return;
    }
    $.post('<?php echo U("Admin/login");?>?t='+Math.random(),{username:username,password:password},function(res){
			if(res.status==1){
		     	window.top.location.href = res.url;
			}else{
				jsalert(res.info,2);
			}
		})
}
document.onkeydown=function(event){ 
	  e = event ? event :(window.event ? window.event : null); 
	  if(e.keyCode==13){
		  checkLogin(); 
	  } 
} 
</script>
</body>
</html>