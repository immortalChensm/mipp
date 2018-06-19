<?php
namespace Api\Controller;
class FollowController extends BaseController {

    //我关注的老师
    public function follow_teacher(){
    	$this->user_id || $this->returnError('参数错误');
    	
    	$lng = I('request.lng') ? I('request.lng') : 0;
    	$lat = I('request.lat') ? I('request.lat') : 0;
    	
    	$list = D('Follow')->where(array('follow_type'=>'1','user_id'=>$this->user_id))->select();
    	foreach ($list as &$val){
    		
    		
    		$field = '*,ACOS(SIN((' . $lat . ' * '.M_PI.') / 180) * SIN((lat * '.M_PI.') / 180 ) +COS((' . $lat . ' * '.M_PI.') / 180 ) * COS((lat * '.M_PI.') / 180 ) *COS((' . $lng . '* '.M_PI.') / 180 - (lng * '.M_PI.') / 180 ) ) * 6371 as distance';
    		
    		$teacher_info = D('Teacher')->field($field)->where(array('id'=>$val['relation_id']))->find();
    		
    		$row = D('Comment')->field('AVG(star) as star')->where(array('type'=>1,'relation_id'=>$teacher_info['id']))->find();
    		$teacher_info['star'] = $row['star'] ? round($row['star'],1) : 0;
    		$teacher_info['stararr'] = $this->starArr($teacher_info['star']);
    		$teacher_info['distance'] = $teacher_info['distance']>1?round($teacher_info['distance'],1).'km':(round($teacher_info['distance'],3)*1000).'m';
    		$teacher_info['type'] = D('TeachType')->where(array('teacher_id'=>$teacher_info['id']))->getField('name');
    		$teacher_info['profile'] = strip_tags(html_entity_decode($teacher_info['profile']));
    		
    		$val['teacher'] = output_data($teacher_info);
    		$val['isTouchMove'] = false;
    	}
    	$this->returnSuccess('',$list);
    }
    //我关注的课程
    public function follow_course(){
    	$this->user_id || $this->returnError('参数错误');
    	$list = D('Follow')->where(array('follow_type'=>'2','user_id'=>$this->user_id))->select();
    	foreach ($list as &$val){
    		$course_info = D('Course')->where(array('id'=>$val['relation_id']))->find();
    		$row = D('Comment')->field('AVG(star) as star')->where(array('type'=>2,'relation_id'=>$course_info['id']))->find();
    		$course_info['star'] = $row['star'] ? round($row['star'],1) : 0;
    		$course_info['stararr'] = $this->starArr($course_info['star']);
    		$val['course'] = output_data($course_info);
    		$val['isTouchMove'] = false;
    	}
    	$this->returnSuccess('',$list);
    }
    //检查是否关注/收藏
    public function check_follow(){
        $this->user_id || $this->returnError('参数错误');
        $type = I('request.type');
        $rel_id = I('request.rel_id');
        D('Follow')->where(array('type'=>$type,'user_id'=>$this->user_id,'relation_id'=>$rel_id))->find() ? $this->returnSuccess('',true) : $this->returnSuccess('',false);
    }
    //取消关注
    public function cancel(){
    	$this->user_id || $this->returnError('参数错误');
    	(int)I('request.id') || $this->returnError('非法的操作');
    	
		D('Follow')->delete((int)I('request.id')) ? $this->returnSuccess('已取消') : $this->returnError('系统繁忙，操作失败');
    }

}