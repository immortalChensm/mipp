<?php
namespace Common\Model;

//通用model类
class RecommendLogModel extends CommonModel{

	protected $_link = array(
	
			'inviter'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'Users',
					'foreign_key'       => 'inviter_id',
					'mapping_fields'       => 'relname,phone,sex,card_number,my_code,invite_code,reg_time',
					'as_fields'       => 'relname,phone,sex,card_number,my_code,invite_code,reg_time'
			),
            'invitee'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'Users',
					'foreign_key'       => 'invitee_id',
			),

	);
}
