<?php 
/**
 * 邮件发送
 * @param $to    接收人
 * @param string $subject   邮件标题
 * @param string $content   邮件内容(html模板渲染后的内容)
 * @throws Exception
 * @throws phpmailerException
 */
function send_email($to,$subject='',$content=''){
    require_once THINK_PATH."Library/Vendor/phpmailer/PHPMailerAutoload.php";
    $mail = new PHPMailer;
    $config = tpCache('smtp');
	$mail->CharSet    = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码	
    $mail->isSMTP();
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 0;
    //调试输出格式
	//$mail->Debugoutput = 'html';
    //smtp服务器
    $mail->Host = $config['smtp_server'];
    //端口 - likely to be 25, 465 or 587
    $mail->Port = $config['smtp_port'];
	if($mail->Port === 465) $mail->SMTPSecure = 'ssl';// 使用安全协议	
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    //用户名
    $mail->Username = $config['smtp_user'];
    //密码
    $mail->Password = $config['smtp_pwd'];
    //Set who the message is to be sent from
    $mail->setFrom($config['smtp_user']);
    //回复地址
    //$mail->addReplyTo('replyto@example.com', 'First Last');
    //接收邮件方
    if(is_array($to)){
    	foreach ($to as $v){
    		$mail->addAddress($v);
    	}
    }else{
    	$mail->addAddress($to);
    }
    //标题
    $mail->Subject = $subject;
    //HTML内容转换
    $mail->msgHTML($content);
    //Replace the plain text body with one created manually
    //$mail->AltBody = 'This is a plain-text message body';
    //添加附件
    //$mail->addAttachment('images/phpmailer_mini.png');
    //send the message, check for errors
    if (!$mail->send()) {
        return false;
    } else {
        return true;
    }

}
   /**
    * 发送短信
    * @param $mobile  手机号码
    * @param $code    验证码
    * @return bool    短信发送成功返回true失败返回false
    */
function sendSMS($mobile, $code)
{
	//时区设置：亚洲/上海
	date_default_timezone_set('Asia/Shanghai');
	//这个是你下面实例化的类
	vendor('Alidayu.TopClient');
	//这个是topClient 里面需要实例化一个类所以我们也要加载 不然会报错
	vendor('Alidayu.ResultSet');
	//这个是成功后返回的信息文件
	vendor('Alidayu.RequestCheckUtil');
	//这个是错误信息返回的一个php文件
	vendor('Alidayu.TopLogger');
	//这个也是你下面示例的类
	vendor('Alidayu.AlibabaAliqinFcSmsNumSendRequest');

	$c = new \TopClient;
	$config = F('sms','',TEMP_PATH);
	//短信内容：公司名/名牌名/产品名
	$product = $config['sms_product'];
	//App Key的值 这个在开发者控制台的应用管理点击你添加过的应用就有了
	$c->appkey = $config['sms_appkey'];
	//App Secret的值也是在哪里一起的 你点击查看就有了
	$c->secretKey = $config['sms_secretKey'];
	//这个是用户名记录那个用户操作
	$req = new \AlibabaAliqinFcSmsNumSendRequest;
	//代理人编号 可选
	$req->setExtend("123456");
	//短信类型 此处默认 不用修改
	$req->setSmsType("normal");
	//短信签名 必须
	$req->setSmsFreeSignName("注册验证");
	//短信模板 必须
	$req->setSmsParam("{\"code\":\"$code\",\"product\":\"$product\"}");
	//短信接收号码 支持单个或多个手机号码，传入号码为11位手机号码，不能加0或+86。群发短信需传入多个号码，以英文逗号分隔，
	$req->setRecNum("$mobile");
	//短信模板ID，传入的模板必须是在阿里大鱼“管理中心-短信模板管理”中的可用模板。
	$req->setSmsTemplateCode($config['sms_templateCode']); // templateCode

	$c->format='json';
	//发送短信
	$resp = $c->execute($req);
	//短信发送成功返回True，失败返回false
	//if (!$resp)
	if ($resp && $resp->result)   // if($resp->result->success == true)
	{
		// 从数据库中查询是否有验证码
		$data = M('sms_log')->where("code = '$code' and add_time > ".(time() - 60*60))->find();
		// 没有就插入验证码,供验证用
		empty($data) && M('sms_log')->add(array('mobile' => $mobile, 'code' => $code, 'add_time' => time(), 'session_id' => SESSION_ID));
		return true;
	}
	else
	{
		return false;
	}
}
/**
 * 查询快递
 * @param $postcom  快递公司编码
 * @param $getNu  快递单号
 * @return array  物流跟踪信息数组
 */
function queryExpress($postcom , $getNu){
	$url = "http://wap.kuaidi100.com/wap_result.jsp?rand=".time()."&id={$postcom}&fromWeb=null&postid={$getNu}";
	//$resp = httpRequest($url,'GET');
	$resp = file_get_contents($url);
	if (empty($resp)) {
		return array('status'=>0, 'message'=>'物流公司网络异常，请稍后查询');
	}
	preg_match_all('/\\<p\\>&middot;(.*)\\<\\/p\\>/U', $resp, $arr);
	if (!isset($arr[1])) {
		return array( 'status'=>0, 'message'=>'查询失败，参数有误' );
	}else{
		foreach ($arr[1] as $key => $value) {
			$a = array();
			$a = explode('<br /> ', $value);
			$data[$key]['time'] = $a[0];
			$data[$key]['context'] = $a[1];
		}
		return array( 'status'=>1, 'message'=>'ok','data'=> array_reverse($data));
	}
}
/**
 * 获取缓存或者更新缓存
 * @param string $config_key 缓存文件名称
 * @param array $data 缓存数据  array('k1'=>'v1','k2'=>'v3')
 * @return array or string or bool
 */
function tpCache($config_key,$data = array()){
	$param = explode('.', $config_key);
	if(empty($data)){
		//如$config_key=shop_info则获取网站信息数组
		//如$config_key=shop_info.logo则获取网站logo字符串
		$config = F($param[0],'',TEMP_PATH);//直接获取缓存文件
		if(empty($config)){
			//缓存文件不存在就读取数据库
			$res = D('config')->where("inc_type='$param[0]'")->select();
			if($res){
				foreach($res as $k=>$val){
					$config[$val['name']] = $val['value'];
				}
				F($param[0],$config,TEMP_PATH);
			}
		}
		if(count($param)>1){
			return $config[$param[1]];
		}else{
			return $config;
		}
	}else{
		//更新缓存
		$result =  D('config')->where("inc_type='$param[0]'")->select();
		if($result){
			foreach($result as $val){
				$temp[$val['name']] = $val['value'];
			}
			foreach ($data as $k=>$v){
				$newArr = array('name'=>$k,'value'=>trim($v),'inc_type'=>$param[0]);
				if(!isset($temp[$k])){
					M('config')->add($newArr);//新key数据插入数据库
				}else{
					if($v!=$temp[$k])
						M('config')->where("name='$k'")->save($newArr);//缓存key存在且值有变更新此项
				}
			}
			//更新后的数据库记录
			$newRes = D('config')->where("inc_type='$param[0]'")->select();
			foreach ($newRes as $rs){
				$newData[$rs['name']] = $rs['value'];
			}
		}else{
			foreach($data as $k=>$v){
				$newArr[] = array('name'=>$k,'value'=>trim($v),'inc_type'=>$param[0]);
			}
			M('config')->addAll($newArr);
			$newData = $data;
		}
		return F($param[0],$newData,TEMP_PATH);
	}
}

/**
 * 获取字符串内容中的图片元素
 * @param unknown $note
 * @return unknown
 */
function getpic($note){
	preg_match_all('<img.+?src=\"(.+?)\".+?>',$note,$ereg);//正则表达式把图片的整个都获取出来了
	return $ereg[1];
}

/**
 * 字符串敏感词过滤
 * @param unknown $str
 * @param string $replace_char
 * @return boolean|mixed
 */
function sense_filter($str,$replace_char = '***'){
	if(empty($str)) return false;
	$sense_words = D('SenseWord')->getField('keyword',true);
	foreach ($sense_words as $val){
		$str = str_replace($val, $replace_char, $str);
	}
	return $str;
}

/**
 * 创建邀请码
 * @param number $strlength
 * @return multitype:number |string
 */
function create_invite_code($strlength = 6){
	if (!function_exists('create_rand_num')) {
		function create_rand_num($min,$max,$len){
			$select = array();
			do {
				$index = mt_rand($min, $max);
				if(in_array($index, $select)){
					continue;
				}else{
					$select[] = $index;
				}
			}while(count($select) <= 6);
			return $select;
		}
	}
	$length = C('INVITE_CODE_LENGTH');
	$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$index_array = create_rand_num(0, strlen($str)-2, $strlength);
	$char_array = array();
	foreach ($index_array as $val){
		$char_array[] = substr($str, $val,1);
	}
	$code = implode('', $char_array);
	if(D('Users')->where(array('my_code'=>$code))->find()){
		$this->create_invite_code();
	}else{
		return $code;
	}
}
/**
 * 创建职位唯一代号
 * @param number $strlength
 * @return multitype:number |string
 */
function create_position_code($strlength = 6){
	function get_random_index($min,$max,$len){
		$select = array();
		do {
			$index = mt_rand($min, $max);
			if(in_array($index, $select)){
				continue;
			}else{
				$select[] = $index;
			}
		}while(count($select) <= 6);
		return $select;
	}
	$length = C('INVITE_CODE_LENGTH');
	$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$index_array = get_random_index(0, strlen($str)-2, $strlength);
	$char_array = array();
	foreach ($index_array as $val){
		$char_array[] = substr($str, $val,1);
	}
	$code = implode('', $char_array);
	if(D('Position')->where(array('position_code'=>$code))->find()){
		create_position_code();
	}else{
		return $code;
	}
}
/**
 * 获取配置文件
 * @return multitype:Ambigous <>
 */
function getWebConfig(){
	$config = D('Config')->select();
	$config_data = array();
	foreach ($config as $val){
		$config_data[$val['name']] = $val['value'];
	}
	return $config_data;
}

/**
 * 发送模板消息
 * @param array $data 模板消息参数
 * @param string $type 类型
 * @return string
 */
function send_tmp_msg($data,$type){
	vendor('WxTmpMsg.TmpMsg');
	vendor ( 'Wechat.Wechat' );
	$wechat = new \Wechat(array('appid' => C('APPID'),'appsecret'=> C('APPSECRET')));
	$token = $wechat->checkAuth();
	$tmpmsg = new \TmpMsg($token);
	$tmpmsg->$type($data);
}
/**
 * 推送IOS消息
 * @param unknown $device_id 设备ID
 * @param unknown $content 消息内容
 * @param unknown $redirect_url 跳转链接
 */
function sendIosMsg($device_id,$content,$redirect_url=''){
	vendor('AppMessage.AppMessage');
	$AppMessage = new \AppMessage(C('APPMSG_KEY'), C('APPMSG_SECRET'));
	$AppMessage->sendIOSUnicast($device_id, $content, $redirect_url);
	//ob_end_clean();
}

/**
 * 推送Android消息
 * @param unknown $device_id 设备ID
 * @param unknown $title 标题
 * @param unknown $content 消息内容
 * @param unknown $redirect_url 跳转链接
 */
function sendAndroidMsg($device_id,$title,$content,$redirect_url=''){
	vendor('AppMessage.AppMessage');
	$AppMessage = new \AppMessage(C('APPMSGKEY'), C('APPMSGSECRET'));
	$AppMessage->sendAndroidUnicast($device_id, $title, $content, $redirect_url);
	//ob_end_clean();
}

/**
 * 添加金钱记录
 * @param unknown $to
 */
function add_money_log($user_id,$type,$cash,$get_time){
	D('MoneyLog')->add(array(
		'user_id' => (int)$user_id,
		'type' => (int)$type,
		'cash' => (float)$cash,
		'get_time' => (int)$get_time
	));
}

/**
 * 添加消息记录
 * @param unknown $user_id
 * @param unknown $name
 * @param unknown $phone
 * @param unknown $title
 * @param unknown $content
 * @return Ambigous <\Think\mixed, boolean, unknown, string>
 */
function add_mes_log($user_id,$name,$phone,$title,$content){
	if(!empty($user_id) && !empty($content)){
		return D('Message')->add(array('user_id'=>$user_id,'name'=>$name,'phone'=>$phone,'title'=>$title,'content'=>$content));
	}
}

/**
 * 寻找上级中的区域代理
 * @param $user_id 用户ID
 * @param $code 用户推荐码
 */
 function check_agent($user_id,$code = ''){
     if(empty($user_id) && empty($code)) return false;
     if($user_id){
         $udata = D('Users')->where(array('id'=>$user_id))->find();
     }else{
         $udata = D('Users')->where(array('my_code'=>$code))->find();
     }
     if(empty($udata)) return false;
     if($udata['type'] == '1'){
         return $udata;
     }else{
         return check_agent('',$udata['invite_code']);
     }
 }
 /**
  * 将二维数组转为键值数组
  * @param unknown $data_arr
  * @param string $key
  * @param string $field
  * @return boolean|multitype:unknown
  */
 function getKeyValue($data_arr,$key='id',$field=''){
 	if(empty($data_arr)) return  false;
 	$result_arr = array();
 	foreach ($data_arr as $val){
 		$result_arr[$val[$key]] = $field ? $val[$field] : $val;
 	}
 	return $result_arr;
 }
?>