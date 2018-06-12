<?php
namespace Common\Model;

//通用model类
class TeachTypeModel extends CommonModel{
    protected $_validate = array();
    public function _initialize(){
			$this->_validate[] =array('name','require','请输入类型名称');
		}
	/* protected $_link = array(
			'course'=>array(
					'mapping_type'      => self::HAS_MANY,
					'class_name'        => 'Course',
					'foreign_key'       => 'teacher_id',
					'mapping_fields'       => 'company_id,salary_id,title,image',
					'as_fields'       => 'company_id,salary_id,title,image',
			),
	); */
}
