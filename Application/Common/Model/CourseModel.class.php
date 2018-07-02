<?php
namespace Common\Model;

//通用model类
class CourseModel extends CommonModel{
    protected $_validate = array();
    public function _initialize(){
			$this->_validate[] =array('name','require','请输入姓名',1);
			$this->_validate[] =array('type','require','请选择课程类型');
			$this->_validate[] =array('price','require','请输入课程价格');
			$this->_validate[] =array('stock','require','请输入限购人数');
			$this->_validate[] =array('yuyue_stock','require','请输入预约名额');
			$this->_validate[] =array('pics','require','请上传课程图片');
// 			$this->_validate[] =array('link','require','请上传介绍视频');
			$this->_validate[] =array('profile','require','请输入课程简介');
			$this->_validate[] =array('tags','require','请选择课程标签');
			$this->_validate[] =array('content','require','请输入课程详情');
		}
	protected $_link = array(
			'teacher'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'Teacher',
					'foreign_key'       => 'teacher_id'
			),
			'course_type'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'CourseType',
					'foreign_key'       => 'course_type'
			),
	);
}
