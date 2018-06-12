<?php

namespace Api\Controller;
class CourseController extends BaseController {
	
	/**
	 * 首页推荐课程
	 */
	public function course_stick(){
		$where = array();
		$where['status'] = 1;
		$where['is_stick'] = 1;
		$courses = D('Course')->where($where)->limit(6)->order('create_date asc')->select();
		foreach ($courses as &$val){
			$row = D('Comment')->field('AVG(star) as star')->where(array('type'=>2,'relation_id'=>1))->find();
			$val['star'] = $row['star'] ? round($row['star'],1) : 0;
			$pics = unserialize($val['pics']);
			$val['pic'] = $pics[0];
		}
		$courses && $this->returnSuccess('',$courses);
		$courses || $this->returnError('暂无数据');
	}

	/**
	 * 课程列表
	 */
    public function course_list(){
    	$where = array();
    	$where['status'] = 1;
    	$p = I('request.p') ? (int)I('request.p') : 1;
    	$pagesize = 10;
    	$courses = D('Course')->where($where)->order('is_stick asc,create_date desc')->limit(($p-1)*$pagesize,$pagesize)->select();
		$courses && $this->returnSuccess('',$courses);
		$courses || $this->returnError('暂无数据');
    }
	/**
	 * 课程分类
	 */
    public function course_types(){
    	$types = D('CourseType')->order('sort_index asc,create_date desc')->select();
		$types && $this->returnSuccess('',$types);
		$types || $this->returnError('暂无数据');
    }
	
}