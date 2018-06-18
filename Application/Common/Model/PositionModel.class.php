<?php
namespace Common\Model;

//通用model类
class PositionModel extends CommonModel{
    
	protected $_validate = array(
			array('type','require','请选择职位类型',1),
			array('company_id','require','请选择所属企业'),
			//array('company_id','','该企业已被选择，请重新选择企业',0,'unique'),
			array('title','require','请输入职位名称'),
			array('title_note','require','请输入职位副标题'),
			array('image','require','请上传职位图片'),
			array('category_id','require','请选择职位性质'),
			array('walfares','require','请选择职位福利'),
			array('sex_type','require','请选择职位性别'),
			array('age_min','require','请输入职位年龄最小范围'),
			array('age_max','require','请输入职位年龄最大范围'),
			array('money_min','require','请输入职位薪资最小范围'),
			array('money_max','require','请输入职位薪资最大范围'),
			// array('salary_id','require','请选择职位薪资范围'),
			array('salary_note','require','请输入薪资介绍'),
// 			array('position_note','require','请输入岗位介绍'),
	);
	protected $_link = array(
	
			'company'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'Company',
					'foreign_key'       => 'company_id',
			),
// 			'category'=>array(
// 					'mapping_type'      => self::BELONGS_TO,
// 					'class_name'        => 'PositionAttr',
// 					'foreign_key'       => 'category_id',
// 			),
			'age'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'PositionAttr',
					'foreign_key'       => 'age_id',
			),
			'salary'=>array(
					'mapping_type'      => self::BELONGS_TO,
					'class_name'        => 'PositionAttr',
					'foreign_key'       => 'salary_id',
			),
			'subsidy'=>array(
					'mapping_type'      => self::HAS_MANY,
					'class_name'        => 'PositionSubsidy',
					'foreign_key'       => 'position_id',
			),
			'comment'=>array(
					'mapping_type'      => self::HAS_MANY,
					'class_name'        => 'Comment',
					'foreign_key'       => 'position_id',
			),
	);
}
