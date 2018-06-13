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
			$val = output_data($val);
			$row = D('Comment')->field('AVG(star) as star')->where(array('type'=>2,'relation_id'=>$val['id']))->find();
			$val['star'] = $row['star'] ? round($row['star'],1) : 0;
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
    	$order = 'is_stick asc,create_date desc';
    	switch (I('request.sort_type')){
    		case '1':$order = 'sale_count desc,create_date desc';break;
    		case '2':$order = 'sale_count asc,create_date desc';break;
    		case '3':$order = 'price asc,create_date desc';break;
    		case '4':$order = 'price desc,create_date desc';break;
    	}
    	$pagesize = 10;
    	$courses = D('Course')->where($where)->order($order)->limit(($p-1)*$pagesize,$pagesize)->select();
    	foreach ($courses as &$val){
    		$val = output_data($val);
			$row = D('Comment')->field('AVG(star) as star')->where(array('type'=>2,'relation_id'=>$val['id']))->find();
			$val['star'] = $row['star'] ? round($row['star'],1) : 0;
			$val['pic'] = $val['pics'][0];
		}
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