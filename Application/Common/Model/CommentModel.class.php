<?php
namespace Common\Model;

class CommentModel extends CommonModel{
    
	protected $_validate = array();
	
	public function _initialize(){
		$this->_validate = array('star','require','请选择分数');
		$this->_validate = array('content','require','请输入评论内容');
	}
	protected $_link = array(
		'user'=>array(
				'mapping_type'      => self::BELONGS_TO,
				'class_name'        => 'User',
				'foreign_key'       => 'user_id',
				'mapping_fields'       => 'nickname,headimgurl',
				'as_fields'       => 'nickname,headimgurl',
		),
	);
}
