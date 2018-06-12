<?php
namespace Common\Model;

//通用model类
class PositionSubsidyModel extends CommonModel{
    
	protected $_validate = array(
		
	);
	protected $_link = array(
	
			'position'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'Position',
					'foreign_key'       => 'position_id',
					'mapping_fields'       => 'freez_time',
 					'as_fields'       => 'freez_time',
			)
	);
}
