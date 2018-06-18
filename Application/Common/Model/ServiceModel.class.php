<?php
namespace Common\Model;

//通用model类
class ServiceModel extends CommonModel{
    
	protected $_validate = array(
			array('name','require','请输入客服姓名'),
			array('phone','require','请输入客服电话'),
			array('type','require','请选择客服类型')
			// array('province_id','require','请选择省份'),
			// array('city_id','require','请选择城市'),
	);
	protected $_link = array(
			'province'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'Province',
					'foreign_key'       => 'province_id',
			),
			'city'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'City',
					'foreign_key'       => 'city_id',
			),
	);
}
