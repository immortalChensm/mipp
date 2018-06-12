<?php
namespace Common\Model;

//通用model类
class TeacherModel extends CommonModel{
    protected $_validate = array();
    public function _initialize(){
			$this->_validate[] =array('name','require','请输入姓名',1);
			$this->_validate[] =array('sex','require','请选择性别');
			$this->_validate[] =array('phone','require','请输入手机号');
			$this->_validate[] =array('phone','/1[3-9][0-9]{9}/','手机号格式有误');
			$this->_validate[] =array('email','require','请输入邮箱');
			$this->_validate[] =array('lng','require','请输入经纬度');
			$this->_validate[] =array('lat','require','请输入经纬度');
// 			$this->_validate[] =array('lng','number','不合法的经纬度');
// 			$this->_validate[] =array('lat','number','不合法的经纬度');
			$this->_validate[] =array('teacher_type','require','请选择教师类型');
			$this->_validate[] =array('teach_type','require','请选择授课类型');
			$this->_validate[] =array('profile','require','请输入教师简介');
			$this->_validate[] =array('content','require','请输入教师详情');
// 			if(ACTION_NAME =='register' || ACTION_NAME =='edit_agent' || ACTION_NAME=='phone_save'){
// 				$this->_validate[] =array('card_number','require','请输入身份证号');
// 				$this->_validate[] =array('card_number','/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/','身份证格式不合法');
// 			}
		}
	protected $_link = array(
			'course'=>array(
					'mapping_type'      => self::HAS_MANY,
					'class_name'        => 'Course',
					'foreign_key'       => 'teacher_id',
// 					'mapping_fields'       => 'company_id,salary_id,title,image',
// 					'as_fields'       => 'company_id,salary_id,title,image',
			),
			'teach_type'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'TeachType',
					'foreign_key'       => 'teach_type',
					'mapping_fields'       => 'name',
					'as_fields'       => 'teach_type_name',
			),
	);
}
