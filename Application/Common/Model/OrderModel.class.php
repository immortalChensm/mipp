<?php
namespace Common\Model;

//通用model类
class OrderModel extends CommonModel{
	
	protected $_link = array(
		'teacher'=>array(
				'mapping_type'      => self::BELONGS_TO,
				'class_name'        => 'Teacher',
				'foreign_key'       => 'teacher_id',
// 				'mapping_fields'       => '',
// 				'as_fields'       => '',
		),
		'course'=>array(
				'mapping_type'      => self::BELONGS_TO,
				'class_name'        => 'Course',
				'foreign_key'       => 'course_id',
				// 				'mapping_fields'       => '',
		// 				'as_fields'       => '',
		),
		'user'=>array(
				'mapping_type'      => self::BELONGS_TO,
				'class_name'        => 'User',
				'foreign_key'       => 'user_id',
				// 				'mapping_fields'       => '',
		// 				'as_fields'       => '',
		),
	);
}
