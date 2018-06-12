<?php
return array(
   // 'API_SIGN'	=>	array(
						// 'wechat' => 'fd4002886ad2b079',
						// 'android' => '39c82e6f9ff1dece',
						// 'ios' => '22177cedecfb12df',//substr(md5('ios'.'recruit'), 16),
					// ), // 接口调用的签名
    'API_SIGN'	=> 'fd4002886ad2b079',
    'LOAD_EXT_FILE' =>'common',
	//短信接口
	'SMS_USERNAME' => 'BG1RTWL_anpinwang',//账号
	'SMS_PASSWORD' => 'Anpinwang666',//密码
	'SMS_SLEEP_TIME' => 60,//发送冷却时间
	'DEFAULT_ACTION' => array(//默认action，不走签名和登录
			'yzcode','sendsms','login','upload_image','forgot_password','user_abount','magreement','upload_video'
	),
	'NOLOGIN_ACTION' => array(//无需登录的action
			'entry','entry_details','entry_other','register','point','point_details','position','position_details',
			'password_retrieval','index','questions','questions_list','more_comment','customer_service','cashback','recommend_code','connect_openid','code_by_openid'
	),
	'INVITE_CODE_LENGTH' => 6,
	
);