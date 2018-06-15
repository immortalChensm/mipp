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

	public function addorder($user_id,$data){
            $order = D('Order')->where(array('id'=>$data['id']))->field('teacher_id,course_id')->find();
            $teacher = D('Comment')->add(array('user_id'=>$user_id,'relation_id'=>$order['teacher_id'],'content'=>$data['content'],'star'=>$data['teacher_star']));
            $course = D('Comment')->add(array('user_id'=>$user_id,'type'=>'2','relation_id'=>$order['course_id'],'content'=>$data['course_content'],'star'=>$data['course_star']));
            if($teacher && $course)  return true;
        }
}
