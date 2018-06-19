<?php
namespace Common\Model;

//通用model类
class TeacherModel extends CommonModel{
    protected $_validate = array();
    public function _initialize(){
			$this->_validate[] =array('name','require','请输入姓名');
			$this->_validate[] =array('sex','require','请选择性别');
			$this->_validate[] =array('phone','require','请输入手机号');
			$this->_validate[] =array('phone','/1[3-9][0-9]{9}/','手机号格式有误');
			$this->_validate[] =array('email','require','请输入邮箱');
			$this->_validate[] =array('lng','require','请选择地理位置');
			$this->_validate[] =array('lat','require','请选择地理位置');
			$this->_validate[] =array('teacher_type','require','请输入授课类型');
			$this->_validate[] =array('teach_type','require','请选择授课类型');
			
			$this->_validate[] =array('fcard','require','请上传身份证正面照');
			$this->_validate[] =array('bcard','require','请上传身份证反面照');
			$this->_validate[] =array('qualification','require','请上传资格证书');
			
			$this->_validate[] =array('profile','require','请输入教师简介');
			$this->_validate[] =array('content','require','请输入教师详情');
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
