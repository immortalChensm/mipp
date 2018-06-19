<?php
namespace Common\Model;

//通用model类
class WebsiteRecordModel extends CommonModel{

	protected $_validate = array(
		array('name','require','请输入姓名'),
		array('phone','require','请输入电话'),
	 	array('phone','/1[3-8][0-9]{9}/','手机号格式有误'), 
	    array('card_number','require','请输入身份证号'),
	    array('card_number','/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/','身份证格式不合法'),
	    array('sex','require','请选择性别')
	);

		protected $_link = array(
	
			'website'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'Website',
					'foreign_key'       => 'website_id',
					'mapping_fields'    => 'title,address,name',
					'as_fields'         => 'title,address,name:wname',
			)
	);

}
