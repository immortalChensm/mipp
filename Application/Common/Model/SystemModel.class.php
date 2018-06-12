<?php
namespace Common\Model;

//通用model类
class SystemModel extends CommonModel{
    protected $_validate = array();
    public function _initialize(){
			$this->_validate[] =array('content','require','请输入内容');
		}
}
