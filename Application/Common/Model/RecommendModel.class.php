<?php
namespace Common\Model;

//通用model类
class RecommendModel extends CommonModel{

	protected $_validate = array(
			array('invitee_name','require','请输入好友真实姓名'),
			array('invitee_phone','require','请输入好友手机号'),
			array('invitee_phone','/1[3-8][0-9]{9}/','手机号格式有误'),
			array('invitee_phone','','抱歉，您的这位朋友已经被推荐了哦',0,'unique'),
			array('invitee_phone','check_user','抱歉，您的这位朋友已经是会员了哦',0,'callback'),
	);
    
	protected $_link = array(
			'inviter'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'Users',
					'foreign_key'       => 'inviter_id',
					'mapping_fields'    => 'relname,nickname,headimgurl,phone,reg_time',
					'as_fields'         => 'relname,nickname,headimgurl,phone,reg_time',
			),
            'invitee'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'Users',
					'foreign_key'       => 'invitee_id'
			),
	);
	
	protected function check_user(){
		$invitee_phone = $_POST['invitee_phone'];
		if(D('Users')->where(array('phone'=>$invitee_phone))->find()){
			return false;
		}else{
			return true;
		}
	}
}
