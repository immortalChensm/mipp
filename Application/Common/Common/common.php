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
 * 获取字符串内容中的图片元素
 * @param unknown $note
 * @return unknown
 */
function getpic($note){
	preg_match_all('<img.+?src=\"(.+?)\".+?>',$note,$ereg);//正则表达式把图片的整个都获取出来了
	return $ereg[1];
}


/**
 * 将键值为数组的值序列化以便存入数组
 * @param unknown $data
 * @return boolean|string
 */
function input_data($data){
	if (empty($data)) return false;
	if(is_array($data)){
		foreach ($data as $key=>$val){
			if(is_array($val)){
				foreach ($val as $k=>$v){
					if(empty($v)) unset($val[$k]);
				}
				$data[$key] = serialize($val);
			}
		}
	}
	return $data;
}

/**
 * 反序列化数组中的序列化字段|反序列化字符串
 * @param unknown $data
 * @return boolean|mixed
 */
function output_data($data){
	if (empty($data)) return false;
	if(is_array($data)){
		foreach ($data as $key=>$val){
			if(is_serialized($val)){
				$data[$key] = unserialize($val);
			}
		}
	}else{
		is_serialized($data) && $data = unserialize($data);
	}
	return $data;
}

/**
 * 判断字符串是不是序列化的
 * @param unknown $data
 * @return boolean
 */
function is_serialized( $data ) {
	$data = trim( $data );
	if ( 'N;' == $data ) return true;
	if ( !preg_match( '/^([adObis]):/', $data, $badions ) ) return false;
	switch ( $badions[1] )
	{
		case 'a' : ;
		case 'o' : ;
		case 's' :if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) ) return true;break;
		case 'b' : ;
		case 'i' : ;
		case 'd' :if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) ) return true;break;
	}
	return false;
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