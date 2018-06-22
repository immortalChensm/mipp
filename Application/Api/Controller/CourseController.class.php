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
        $p = I('request.p') ? (int)I('request.p') : 1;
    	$pagesize = 5;
		$courses = D('Course')->where($where)->limit(($p-1)*$pagesize,$pagesize)->order('create_date asc')->select();
		foreach ($courses as &$val){
			$val = output_data($val);
			$row = D('Comment')->field('AVG(star) as star')->where(array('type'=>2,'relation_id'=>$val['id']))->find();
			$val['star'] = $row['star'] ? round($row['star'],1) : 0;
			$val['stararr'] = $this->starArr($val['star']);
			$val['pic'] = $val['pics'][0];
		}
		$courses && $this->returnSuccess('',$courses);
		$courses || $this->returnError('暂无数据');
	}

	/**
	 * 课程列表
	 */
    public function course_list(){
    	//查询条件
    	$where = array();
    	$where['status'] = 1;
    	I('request.type') && $where['type'] = I('request.type');
    	
    	$p = I('request.p') ? (int)I('request.p') : 1;
    	$pagesize = 5;
    	
    	$order = 'is_stick asc,create_date desc';
    	switch (I('request.sort_sale_count')){
    		case '1':$order = 'sale_count asc,create_date desc';break;
    		case '2':$order = 'sale_count desc,create_date desc';break;
    	}
    	switch (I('request.sort_price')){
    		case '1':$order = 'price asc,create_date desc';break;
    		case '2':$order = 'price desc,create_date desc';break;
    	}
    	$courses = D('Course')->where($where)->order($order)->limit(($p-1)*$pagesize,$pagesize)->select();
    	foreach ($courses as &$val){
    		$val = output_data($val);
			$row = D('Comment')->field('AVG(star) as star')->where(array('type'=>2,'relation_id'=>$val['id']))->find();
			$val['star'] = $row['star'] ? round($row['star'],1) : 0;
			$val['stararr'] = $this->starArr($val['star']);
			$val['pic'] = $val['pics'][0];
		}
		$courses && $this->returnSuccess('',$courses);
		$courses || $this->returnError('暂无数据');
    }
    
    /**
     * 课程信息
     */
    public function course_info(){
    	$course_id = (int)I('request.course_id');
    	$course_id || $this->returnError('非法的访问');
    	$course_info = D('Course')->where(array('id'=>$course_id))->find();
    	if ($course_info) {
    		$row = D('Comment')->field('AVG(star) as star')->where(array('type'=>2,'relation_id'=>$course_info['id']))->find();
    		$course_info['star'] = $row['star'] ? round($row['star'],1) : 0;
    		$course_info['stararr'] = $this->starArr($row['star']);
    		$course_info['content'] = html_entity_decode($course_info['content']);
    		$this->returnSuccess('',output_data($course_info));
    	}else{
    		$this->returnError('暂无数据');
    	}
    }
    
    /**
     * 评价信息
     */
    public function comments(){
    	$course_id = (int)I('request.course_id');
    	$course_id || $this->returnError('非法的访问');
    	$comments = D('Comment')->relation('user')->where(array('type'=>2,'status'=>1,'relation_id'=>$course_id))->select();
    	foreach ($comments as &$val){
    		$val['stararr'] = $this->starArr($val['star']);
    	}
    	$comments && $this->returnSuccess('',output_data($comments));
    	$comments || $this->returnError('暂无数据');
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