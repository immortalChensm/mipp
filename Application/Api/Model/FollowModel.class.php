<?php
namespace Home\Model;

use Think\Model\RelationModel;

    class FollowModel extends RelationModel{
    protected $_link = array(
        'teacher' => array(
            'mapping_type'   => self::HAS_MANY,
            'class_name'     => 'Teacher',
            'foreign_key'    => 'relation_id',
        ),
        'course' => array(
            'mapping_type'   => self::HAS_MANY,
            'class_name'     => 'Course',
            'foreign_key'    => 'relation_id',
        )
    );
}
