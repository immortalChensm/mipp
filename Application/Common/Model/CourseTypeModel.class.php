<?php
namespace Common\Model;

//通用model类
class CourseTypeModel extends CommonModel{
    protected $_validate = array();
    public function _initialize(){
			$this->_validate[] =array('name','require','请输入类型名称');
			$this->_validate[] =array('pic','require','请上传图片');
		}
}
