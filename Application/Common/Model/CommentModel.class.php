<?php
namespace Common\Model;

class CommentModel extends CommonModel{
    
	protected $_validate = array(
			array('id', 'require', '订单id不能为空'),
			array('course_star', 'require', '请选择课程星级'),
			array('course_content', 'require', '请输入课程评价内容'),
			array('teacher_star', 'require', '请选择老师星级'),
			array('teacher_content', 'require', '请输入老师评价内容'),
	);
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
		$order = D('Order')->where(array('id'=>$data['order_id']))->field('id,teacher_id,course_id')->find();
		$teacher = D('Comment')->add(array(
				'order_id'=>$order['id'],
				'user_id'=>$user_id,
				'relation_id'=>$order['teacher_id'],
				'content'=>$data['teacher_content'],
				'star'=>$data['teacher_star']
		));
		$course = D('Comment')->add(array(
				'order_id'=>$order['id'],
				'user_id'=>$user_id,
				'type'=>'2',
				'relation_id'=>$order['course_id'],
				'content'=>$data['course_content'],
				'star'=>$data['course_star']));
		return $teacher && $course;
	}

}
