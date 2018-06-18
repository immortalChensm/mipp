<?php
namespace Common\Model;

//通用model类
class EntryModel extends CommonModel{
    
	protected $_validate = array(

	);
	protected $_link = array(
	
			'user'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'Users',
					'foreign_key'       => 'user_id',
			),
			'position'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'Position',
					'foreign_key'       => 'position_id',
			),
			'company'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'Company',
					'foreign_key'       => 'company_id',
			)
	);
}
