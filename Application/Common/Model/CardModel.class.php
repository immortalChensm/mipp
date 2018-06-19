<?php
namespace Common\Model;

//通用model类
class CardModel extends CommonModel{
    
	protected $_validate = array(
			array('name','require','请输入开户人姓名'),
			array('card_number','require','请输入开户人卡号'),
			array('bank_id','require','请选择银行'),
			array('area','require','请输入开户行'),
			// array('pcard_image','require','请上传身份证正面图片'),
			// array('bcard_image','require','请上传身份证反面图片')
	);
		protected $_link = array(
			'bank'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'BankCard',
					'foreign_key'       => 'bank_id',
					'mapping_fields'    => 'name',
					'as_fields'         => 'name:card_name',

			),
	);
}
