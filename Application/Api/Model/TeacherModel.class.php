<?php
namespace Home\Model;

use Think\Model\RelationModel;

    class OrderModel extends RelationModel{
    protected $_link = array(
        'comment' => array(
            'mapping_type'   => self::HAS_MANY,
            'class_name'     => 'Comment',
            'foreign_key'    => 'relation_id',
            'mapping_fields' => 'name',
            'as_fields'      => 'name',
        ),
        'course' => array(
            'mapping_type'   => self::HAS_MANY,
            'class_name'     => 'Course',
            'foreign_key'    => 'teacher_id',
            'mapping_fields' => 'name,pics',
            'as_fields'      => 'name:coursename',
        )
    );
}
