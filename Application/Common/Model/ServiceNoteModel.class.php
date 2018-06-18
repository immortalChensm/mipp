<?php
namespace Common\Model;

//通用model类
class ServiceNoteModel extends CommonModel{
    
	protected $_validate = array(
			array('content','require','请输入备注内容'),
			array('content','1,200','备注最多200个字符',0,'length'),
	);
}
