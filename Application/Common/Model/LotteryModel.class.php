<?php
namespace Common\Model;

//通用model类
class LotteryModel extends CommonModel{
    
	protected $_validate = array();
	
	public function _initialize(){
			$this->_validate[] = array('name','require','请输入奖品名称');
			$this->_validate[] = array('number','require','请输入奖品数量 ');
			$this->_validate[] = array('percentage','require','请输入中奖率');

	}
}
