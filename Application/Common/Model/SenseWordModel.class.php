<?php
namespace Common\Model;

//通用model类
class SenseWordModel extends CommonModel{
    
	protected $_validate = array(
			array('keyword','require','请输入敏感词')
	);
	
}
