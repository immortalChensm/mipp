<?php
namespace Common\Model;

//通用model类
class EnrollModel extends CommonModel{
    protected $_validate = array();
    public function _initialize(){
			$this->_validate[] =array('name','require','请输入报名人姓名',1);
			$this->_validate[] =array('phone','require','请输入手机号');
			$this->_validate[] =array('phone','/1[3-8][0-9]{9}/','手机号格式有误');
			$this->_validate[] =array('sex','require','请选择性别');
			$this->_validate[] =array('position_id','require','请选择报名职位');
			if(ACTION_NAME =='register' || ACTION_NAME =='edit_agent' || ACTION_NAME=='phone_save'){
			$this->_validate[] =array('card_number','require','请输入身份证号');
			$this->_validate[] =array('card_number','/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/','身份证格式不合法');
			}
			$this->_validate[] =array('province_id','require','请选择籍贯');
			$this->_validate[] =array('city_id','require','请选择籍贯');
		}
	protected $_link = array(
			'position'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'Position',
					'foreign_key'       => 'position_id',
					'mapping_fields'       => 'company_id,salary_id,title,image',
					'as_fields'       => 'company_id,salary_id,title,image',
			),
	);
}
