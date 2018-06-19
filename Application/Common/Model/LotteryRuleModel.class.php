<?php
namespace Common\Model;

//通用model类
class LotteryRuleModel extends CommonModel{
    
	protected $_validate = array();
	
	public function _initialize(){
			$this->_validate[] = array('rule','require','请输入中奖规则');
			$this->_validate[] = array('chance','require','请输入分享获取抽奖机会');
	}
}
