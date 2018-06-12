<?php
namespace Common\Model;

//通用model类
class RecommendRuleModel extends CommonModel{

	protected $_validate = array(
			array('money','require','请输入分销总金额'),
			array('rate1','require','请选择分销一级奖励比例'),
			array('rate2','require','请选择分销二级奖励比例'),
			array('rate3','require','请选择分销三级奖励比例'),
			array('rate4','require','请选择分销四级奖励比例')
	);

}
