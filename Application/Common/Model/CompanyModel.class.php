<?php
namespace Common\Model;

//通用model类
class CompanyModel extends CommonModel{
    
	protected $_validate = array(
			array('name','require','请输入企业名称'),
			array('pattern','require','请输入企业性质'),
			array('scale','require','请输入企业规模'),
			array('profile','require','请输入企业简介'),
			// array('province_id','require','请选择省份'),
			// array('city_id','require','请选择城市'),
			array('address','require','请输入企业所在地址'),
			array('location','require','请输入企业所在地经纬度'),
	);
	protected $_link = array(
	
			'province'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'Province',
					'foreign_key'       => 'province_id',
// 					'mapping_fields'       => 'name',
// 					'as_fields'       => 'pname:name',
			),
			'city'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'City',
					'foreign_key'       => 'city_id',
// 					'mapping_fields'       => 'name',
// 					'as_fields'       => 'cname:name',
			),
	);
}
