<?php
class TmpMsg{
	private $interface = array(
		'set_industry' => 'https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=%s',//设置行业
		'get_industry' => 'https://api.weixin.qq.com/cgi-bin/template/get_industry?access_token=%s',//获取行业
		'get_tmp_id' => 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=%s',//获取模板长id
		'get_tmp_list' => 'https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=%s',//获取模板列表
		'remove_tmp' => 'https://api,weixin.qq.com/cgi-bin/template/del_private_template?access_token=%s',//删除模板
		'send_tmp_msg' => 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=%s',//发送模板消息
	);
    private $tmp_arr = array(
		'entry' => 'Bv44WOS2VjtYfafZ0J66aJiOJe8XEoCX0tPHoPdm2mg',//面试人员入职通知（导入入职名单） 
		'recommend' => 'Ch-0P_S9hN6GR8BZar2INerQg-IavjNT7N9m1ojUyHE',//推荐成功奖励通知（好友入职奖励）
        'register'=> 'GnVTwH6fxHXC5TfAzxqNbUtpiNYLI-R5El7dJ_ERXms',  //注册成功通知（注册成功）
        'subsidy' => 'I7EziTcBZN-JXbf08Hcyl-A1rLw7olihoDD8oO01Cs0', //招聘返费更新通知（补贴金额解冻）
        'getcash' => 'TAjEYQSvLGXU-fN_dlYOP_U_s9P524lONHLEVgpcSWs', //提现成功通知（提现审核通过）
        'lottery' => 'YNF8RRlTBaMHzWobRpnHd95SdwmNpqb2DtoV9ENZeDk', //兑换成功提醒（抽奖获奖）
        'regular' => 'hOMRWmoDWLu8U3PM5BFv3A_BvFB_tevnWDP7K_EcR6M',  //审核结果通知（导入转正名单）
        'enroll' => 'r0oK1KLj27rJ-Ix7XuHfVFN3FfkIoqdsUhfMjsR0k7U', //报名成功提醒（报名成功）
        'getsalary' => 'mxsBoWAT3nJhsQgF7wuW-Ql5a1jj0JHpkIKaSQustGg',  //工资到账提醒（周薪发放和月结工资都用该模板）
	);

	private function print_token(){
		echo $this->token;
	}
	
	public function __construct($token=''){
		if($token){
			$interface = $this->interface;
			foreach ($interface as $key=>$val){
				$interface[$key] = sprintf($val,$token);
			}
			$this->interface = $interface;
		}else{
			die($this->error_msg());
		}
	}

    public function do_fun($fun_name,$post_data=null){
		if(empty($post_data)){
			return json_decode($this->curl_request($this->interface[$fun_name]),true);
		}else {
			return json_decode($this->curl_request($this->interface[$fun_name],$post_data),true);
		}
	}

    public function sendMsg($to_user,$datas,$type){
		if(empty($to_user)||empty($datas)||!is_array($datas))return false;
		$this->$type($to_user,$datas);
	}
	
	/**
	 * 面试人员入职通知（导入入职名单） 
	 * {{first.DATA}}
     * 姓名：{{keyword1.DATA}}
     * 证件号码：{{keyword2.DATA}}
     * 联系电话：{{keyword3.DATA}}
     * 应聘职位：{{keyword4.DATA}}
     * 入职时间：{{keyword5.DATA}}
	 * {{remark.DATA}}
    **/
	public function entry($datas){
	   $post_data = '{
           "touser":"'.$datas['openid'].'",
           "template_id":"'.$this->tmp_arr['entry'].'",
           "url":"'.$datas['url'].'",
           "data":{
                   "first": {
                       "value":"'.$datas['first'].'",
                       "color":"#173177"
                   },
                   "keyword1":{
                       "value":"'.$datas['keyword1'].'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.$datas['keyword2'].'",
                       "color":"#173177"
                   },
                   "keyword3": {
                       "value":"'.$datas['keyword3'].'",
                       "color":"#173177"
                   },
                   "keyword4": {
                       "value":"'.$datas['keyword4'].'",
                       "color":"#173177"
                   },
                   "keyword5": {
                       "value":"'.$datas['keyword5'].'",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"'.$datas['remark'].'",
                       "color":"#173177"
                   }
               }
            }';
	    return $this->do_fun('send_tmp_msg',$post_data);
	}

	/**
	 * 推荐成功奖励通知（好友入职奖励）
	 * {{first.DATA}}
	 * 推荐奖励金额：{{keyword1.DATA}}  
     * 奖励到账时间：{{keyword2.DATA}}
	 * {{remark.DATA}}
    **/
    public function recommend($datas){
       $post_data = '{
           "touser":"'.$datas['openid'].'",
           "template_id":"'.$this->tmp_arr['recommend'].'",
           "url":"'.$datas['url'].'",
           "data":{
                   "first": {
                       "value":"'.$datas['first'].'",
                       "color":"#173177"
                   },
                   "keyword1":{
                       "value":"'.$datas['keyword1'].'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.$datas['keyword2'].'",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"'.$datas['remark'].'",
                       "color":"#173177"
                   }
               }
            }';
        return $this->do_fun('send_tmp_msg',$post_data);
    }

	/**
	 * 注册成功通知
	 * {{first.DATA}}
	 * 用户名：{{keyword1.DATA}}          
     * 密码：{{keyword2.DATA}} 
	 * {{remark.DATA}}
    **/
    public function register($datas){
       $post_data = '{
           "touser":"'.$datas['openid'].'",
           "template_id":"'.$this->tmp_arr['register'].'",
           "url":"'.$datas['url'].'",
           "data":{
                   "first": {
                       "value":"'.$datas['first'].'",
                       "color":"#173177"
                   },
                   "keyword1":{
                       "value":"'.$datas['keyword1'].'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.$datas['keyword2'].'",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"'.$datas['remark'].'",
                       "color":"#173177"
                   }
               }
            }';
        return $this->do_fun('send_tmp_msg',$post_data);
    }

	/**
	 * 招聘返费更新通知（补贴金额解冻）
	 * {{first.DATA}}
	 * 姓名：{{keyword1.DATA}}
     * 时间：{{keyword2.DATA}}
	 * {{remark.DATA}}
    **/
    public function subsidy($datas){
       $post_data = '{
           "touser":"'.$datas['openid'].'",
           "template_id":"'.$this->tmp_arr['subsidy'].'",
           "url":"'.$datas['url'].'",
           "data":{
                   "first": {
                       "value":"'.$datas['first'].'",
                       "color":"#173177"
                   },
                  "keyword1":{
                       "value":"'.$datas['keyword1'].'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.$datas['keyword2'].'",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"'.$datas['remark'].'",
                       "color":"#173177"
                   }
               }
            }';
        return $this->do_fun('send_tmp_msg',$post_data);
    }

	/**
	 * 提现成功通知（提现审核通过）
	 * {{first.DATA}}
	 * 提现金额：{{keyword1.DATA}}
	 * 提现时间：{{keyword2.DATA}}
	 * {{remark.DATA}}
    **/
    public function getcash($datas){
       $post_data = '{
           "touser":"'.$datas['openid'].'",
           "template_id":"'.$this->tmp_arr['getcash'].'",
           "url":"'.$datas['url'].'",
           "data":{
                   "first": {
                       "value":"'.$datas['first'].'",
                       "color":"#173177"
                   },
                   "keyword1":{
                       "value":"'.$datas['keyword1'].'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.$datas['keyword2'].'",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"'.$datas['remark'].'",
                       "color":"#173177"
                   }
               }
            }';
        return $this->do_fun('send_tmp_msg',$post_data);
    }

    /**
	 * 兑换成功提醒（抽奖获奖）
	 * {{first.DATA}}
	 * 兑换商品：{{keyword1.DATA}}
	 * 兑换金额：{{keyword2.DATA}}
	 * 兑换时间：{{keyword3.DATA}}
	 * {{remark.DATA}}
    **/
    public function lottery($datas){
       $post_data = '{
           "touser":"'.$datas['openid'].'",
           "template_id":"'.$this->tmp_arr['lottery'].'",
           "url":"'.$datas['url'].'",
           "data":{
                   "first": {
                       "value":"'.$datas['first'].'",
                       "color":"#173177"
                   },
                   "keyword1":{
                       "value":"'.$datas['keyword1'].'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.$datas['keyword2'].'",
                       "color":"#173177"
                   },
                   "keyword3": {
                       "value":"'.$datas['keyword3'].'",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"'.$datas['remark'].'",
                       "color":"#173177"
                   }
               }
            }';
        return $this->do_fun('send_tmp_msg',$post_data);
    }

    /**
	 * 审核结果通知（导入转正名单）
	 * {{first.DATA}}
	 * 公司名称：{{keyword1.DATA}}
	 * 审核内容：{{keyword2.DATA}}
	 * 审核结果：{{keyword3.DATA}}
	 * {{remark.DATA}}
    **/
    public function regular($datas){
       $post_data = '{
           "touser":"'.$datas['openid'].'",
           "template_id":"'.$this->tmp_arr['regular'].'",
           "url":"'.$datas['url'].'",
           "data":{
                   "first": {
                       "value":"'.$datas['first'].'",
                       "color":"#173177"
                   },
                   "keyword1":{
                       "value":"'.$datas['keyword1'].'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.$datas['keyword2'].'",
                       "color":"#173177"
                   },
                   "keyword3": {
                       "value":"'.$datas['keyword3'].'",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"'.$datas['remark'].'",
                       "color":"#173177"
                   }
               }
            }';
        return $this->do_fun('send_tmp_msg',$post_data);
    }
    /**
	 * 报名成功提醒（报名成功）
	 * {{first.DATA}}
	 * 工作确认方式：{{keyword1.DATA}}
	 * 工作时间：{{keyword2.DATA}}
	 * 工作地点：{{keyword3.DATA}}
	 * 负责人：{{keyword4.DATA}}
	 * 咨询电话：{{keyword5.DATA}}
	 * {{remark.DATA}}
    **/
    public function enroll($datas){
       $post_data = '{
           "touser":"'.$datas['openid'].'",
           "template_id":"'.$this->tmp_arr['enroll'].'",
           "url":"'.$datas['url'].'",
           "data":{
                   "first": {
                       "value":"'.$datas['first'].'",
                       "color":"#173177"
                   },
                   "keyword1":{
                       "value":"'.$datas['keyword1'].'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.$datas['keyword2'].'",
                       "color":"#173177"
                   },
                   "keyword3": {
                       "value":"'.$datas['keyword3'].'",
                       "color":"#173177"
                   },
                   "keyword4": {
                       "value":"'.$datas['keyword4'].'",
                       "color":"#173177"
                   },
                   "keyword5": {
                       "value":"'.$datas['keyword5'].'",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"'.$datas['remark'].'",
                       "color":"#173177"
                   }
               }
            }';
        return $this->do_fun('send_tmp_msg',$post_data);
    }
	/**
	 * 工资到账提醒（周薪发放和月结工资都用该模板）
	 * {{first.DATA}}
	 * 岗位：{{keyword1.DATA}}  
	 * 工资金额：{{keyword2.DATA}}  
     * 结算时间：{{keyword3.DATA}}
	 * {{remark.DATA}}
    **/
    public function getsalary($datas){
       $post_data = '{
           "touser":"'.$datas['openid'].'",
           "template_id":"'.$this->tmp_arr['getsalary'].'",
           "url":"'.$datas['url'].'",
           "data":{
                   "first": {
                       "value":"'.$datas['first'].'",
                       "color":"#173177"
                   },
                   "keyword1":{
                       "value":"'.$datas['keyword1'].'",
                       "color":"#173177"
                   },
                   "keyword2": {
                       "value":"'.$datas['keyword2'].'",
                       "color":"#173177"
                   },
                   "keyword3": {
                       "value":"'.$datas['keyword3'].'",
                       "color":"#173177"
                   },
                   "remark":{
                       "value":"'.$datas['remark'].'",
                       "color":"#173177"
                   }
               }
            }';
        return $this->do_fun('send_tmp_msg',$post_data);
    }
	private function curl_request($url,$data = null){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}
	private function error_msg($msg = ''){
		return empty($msg)?'参数有误！':$msg;
	}
}
?>