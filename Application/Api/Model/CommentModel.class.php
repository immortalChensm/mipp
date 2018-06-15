<?php
namespace Home\Model;

use Think\Model\RelationModel;

    class CommentModel extends RelationModel{
        protected $_validate = array(
            array('id', 'require', '订单id不能为空'),
            array('course_star', 'require', '请选择课程星级'),
            array('course_content', 'require', '请输入课程评价内容'),
            array('teacher_star', 'require', '请选择老师星级'),
            array('teacher_content', 'require', '请输入老师评价内容'),
        );

        public function addorder($user_id,$data){
            $order = D('Order')->where(array('id'=>$data['id']))->field('order_id,teacher_id,course_id')->find();
            $teacher = D('Comment')->add(array(
            	'order_id'=>$order['id'],
            	'user_id'=>$user_id,
            	'relation_id'=>$order['teacher_id'],
            	'content'=>$data['content'],
            	'star'=>$data['teacher_star']
            ));
            $course = D('Comment')->add(array(
            	'order_id'=>$order['id'],
            	'user_id'=>$user_id,
            	'type'=>'2',
            	'relation_id'=>$order['course_id'],
            	'content'=>$data['course_content'],
            	'star'=>$data['course_star']));
            if($teacher && $course)  return true;
        }
}
