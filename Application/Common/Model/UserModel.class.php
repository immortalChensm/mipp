<?php
namespace Common\Model;
//通用model类
class UserModel extends CommonModel{

	protected $_validate = array();
	
	protected $_link = array(
			'province'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'Province',
					'foreign_key'       => 'province_id',
			),
			'city'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'City',
					'foreign_key'       => 'city_id',
			),
	);
	
	public function _initialize(){
		//$this->_validate[] = array('nickname','require','请输入姓名');
		$this->_validate[] = array('relname','require','请输入真实姓名');
		$this->_validate[] = array('phone','require','请输入手机号码');
		$this->_validate[] = array('phone','/1[3-8][0-9]{9}/','手机号格式有误');
	}
}
