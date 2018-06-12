<?php
return array(
     'URL_HTML_SUFFIX'       =>  '',  // URL伪静态后缀设置
    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => 'Public:dispatch_jump',
    //默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Public:dispatch_jump',
	'POWER_GROUP' => array('system'=>'系统设置','power'=>'权限管理','position'=>'职位管理','member'=>'会员管理',
    			'enroll'=>'报名管理','work'=>'入职管理','sale'=>'分销管理','cash'=>'财务管理','active'=>'抽奖管理',
				'article'=>'文章管理','website'=>'网点管理','question'=>'问答管理','service'=>'客服设置','message'=>'消息记录',
				'image'=>'图片管理','statistic'=>'数据统计'
    	),
	'API_SIGN' => 'fd4002886ad2b079',
	'UPIMG_INTERFACE' => 'http://'.$_SERVER['HTTP_HOST'].'/Api/Upload/upload_image',
	'UPVIDEO_INTERFACE' => 'http://'.$_SERVER['HTTP_HOST'].'/Api/Upload/upload_video',
	'UPFILE_INTERFACE' => 'http://'.$_SERVER['HTTP_HOST'].'/Api/Upload/upload_file',
    'SHOW_PAGE_TRACE' =>true, 
);