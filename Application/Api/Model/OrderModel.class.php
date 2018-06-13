<?php
namespace Home\Model;

use Think\Model\RelationModel;

    class OrderModel extends RelationModel{
    protected $_link = array(
        'teacher' => array(
            'mapping_type'   => self::HAS_MANY,
            'class_name'     => 'Teacher',
            'foreign_key'    => 'teacher_id',
            'mapping_fields' => 'name',
            'as_fields'      => 'name',
        ),
        'course' => array(
            'mapping_type'   => self::HAS_MANY,
            'class_name'     => 'Course',
            'foreign_key'    => 'course_id',
            'mapping_fields' => 'name,pics',
            'as_fields'      => 'name:coursename',
        )
    );
}
