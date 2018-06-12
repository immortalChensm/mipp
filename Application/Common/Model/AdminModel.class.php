<?php
namespace Common\Model;

//通用model类
class AdminModel extends CommonModel{
    
	protected $_validate = array();
	protected $_link = array(
		'service' => array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'Service',
			'foreign_key' => 'service_id',
		),
	);
	public function _initialize(){
		if(ACTION_NAME == 'login'){
			$this->_validate[] = array('user_name','require','请输入用户名');
			$this->_validate[] = array('password','require','请输入密码');
		}elseif(ACTION_NAME == 'edit_pass'){
			$this->_validate[] = array('old_password','require','请输入原密码');
			$this->_validate[] = array('old_password','check_password','原密码有误',0,'callback');
			$this->_validate[] = array('new_password','require','请输入新密码');
			$this->_validate[] = array('new_password','/[a-zA-Z0-9_]{6,20}/','新密码需为6~20位字母、数字、下划线的组合');
			$this->_validate[] = array('password','require','请输入确认密码');
			$this->_validate[] = array('password','new_password','两次输入的密码不一致',0,'confirm');
		}elseif (ACTION_NAME == 'edit_servicer'){
			$info = I('post.info');
			$this->_validate[] = array('user_name','require','请输入用户名');
			$this->_validate[] = array('user_name','/[a-zA-Z0-9_]{6,20}/','用户名需为6~20位字母、数字、下划线的组合');
			$this->_validate[] = array('user_name','','该帐号已经存在！',0,'unique',1);
			//$this->_validate[] = array('email','require','请输入电子邮箱地址');
			$this->_validate[] = array('email','/[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+/','邮箱格式有误');
			if(!$info['id']){
				$this->_validate[] = array('password','require','请输入密码');
				$this->_validate[] = array('password','/[a-zA-Z0-9_]{6,20}/','密码需为6~20位字母、数字、下划线的组合');
			}else{
				$info['password'] && $this->_validate[] = array('password','/[a-zA-Z0-9_]{6,20}/','密码需为6~20位字母、数字、下划线的组合');
			}
			$this->_validate[] = array('service_id','require','请选择关联客服');
		}else{
			$info = I('post.info');
			$this->_validate[] = array('user_name','require','请输入用户名');
			$this->_validate[] = array('user_name','/[a-zA-Z0-9_]{6,20}/','用户名需为6~20位字母、数字、下划线的组合');
			$this->_validate[] = array('user_name','','该帐号已经存在！',0,'unique',1);
			$this->_validate[] = array('email','require','请输入电子邮箱地址');
			$this->_validate[] = array('email','/[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+/','邮箱格式有误');
			if(!$info['id']){
				$this->_validate[] = array('password','require','请输入密码');
				$this->_validate[] = array('password','/[a-zA-Z0-9_]{6,20}/','密码需为6~20位字母、数字、下划线的组合');
			}else{
				$info['password'] && array('password','/[a-zA-Z0-9_]{6,20}/','密码需为6~20位字母、数字、下划线的组合');
			}
			$this->_validate[] = array('role_name','require','请输入角色名称');
			$this->_validate[] = array('role_desc','require','请输入角色介绍');
		}
	}
	protected function check_password(){
		$info = I('post.info');
		$admin_info = $this->where(array('id'=>session('admin_id')))->find();
		if(encrypt($info['old_password']) == $admin_info['password']) return true;
		return false;
	}
}
