<?php
namespace Common\Model;

//通用model类
class PositionAttrModel extends CommonModel{
    
	protected $_validate = array();
	
	public function _initialize(){
		$info = I('post.info');
		$attr_msg = array('1'=>'职位性质名称','2'=>'职位福利名称','3'=>'职位年龄范围','4'=>'职位薪资范围');
		$this->_validate[] = array('name','require','请输入'.$attr_msg[$info['type']]);
	}
}
