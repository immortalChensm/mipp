<?php
namespace Common\Model;

//通用model类
class CourseTagModel extends CommonModel{
    protected $_validate = array();
    public function _initialize(){
			$this->_validate[] = array('name','require','请输入标签名称');
			$this->_validate[] = array('name','1,5','标签最多四个字',0,'length');
			$this->_validate[] = array('name','','该标签已经存在！',0,'unique',1);
		}
}
