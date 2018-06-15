<?php

namespace Api\Controller;
class TeacherController extends BaseController {
	
	/**
	 * 首页推荐老师
	 */
	public function teacher_stick(){
		$lng = I('request.lng') ? I('request.lng') : 0;
		$lat = I('request.lat') ? I('request.lat') : 0;
		$where = array();
		$where['status'] = 1;
		$where['is_stick'] = 1;
		D('Teacher')->field('t.*,ACOS(SIN((' . $lat . ' * '.M_PI.') / 180) * SIN((lat * '.M_PI.') / 180 ) +COS((' . $lat . ' * '.M_PI.') / 180 ) * COS((lat * '.M_PI.') / 180 ) *COS((' . $lng . '* '.M_PI.') / 180 - (lng * '.M_PI.') / 180 ) ) * 6371 as distance');
		
		$teachers = D('Teacher')->where($where)->limit(6)->order('distance asc,create_date asc')->select();
		foreach ($teachers as &$val){
			$row = D('Comment')->field('AVG(star) as star')->where(array('type'=>1,'relation_id'=>1))->find();
			$val['star'] = $row['star'] ? round($row['star'],1) : 0;
			$val['stararr'] = $this->starArr($val['star']);
			$val['distance'] = $val['distance']>1?round($val['distance'],1).'km':(round($val['distance'],3)*1000).'m';
			$val['teach_type'] = D('TeachType')->where(array('id'=>$val['teach_type']))->getField('name');
			$val['profile'] = strip_tags(html_entity_decode($val['profile']));
		}
		$teachers && $this->returnSuccess('',$teachers);
		$teachers || $this->returnError('暂无数据');
	}

	/**
	 * 教师列表
	 */
	public function teacher_list(){
		$where = array();
		$where['t.status'] = 1;
		$pagesize = 10;
		$p = I('request.p') ? (int)I('request.p') : 1;
		$lng = I('request.lng') ? I('request.lng') : 0;
		$lat = I('request.lat') ? I('request.lat') : 0;
		$order = 'distance asc,t.create_date asc';
		I('sale') == '2' && $order = 'sale_num asc';
		I('teach_type') && $where['t.teach_type'] = I('teach_type');

    	D('Teacher')->field('t.*,ACOS(SIN((' . $lat . ' * '.M_PI.') / 180) * SIN((lat * '.M_PI.') / 180 ) +COS((' . $lat . ' * '.M_PI.') / 180 ) * COS((lat * '.M_PI.') / 180 ) *COS((' . $lng . '* '.M_PI.') / 180 - (lng * '.M_PI.') / 180 ) ) * 6371 as distance ,c.sale_count');
		$teachers = D('Teacher')
					->alias('t')
					->join('edu_course as c  ON t.id = c.teacher_id')
					->where($where)->limit(($p-1)*$pagesize,$pagesize)->order($order)->select();
		$teacher = array();
		foreach ($teachers as &$val){
			$row = D('Comment')->field('AVG(star) as star')->where(array('type'=>1,'relation_id'=>1))->find();
			$val['star'] = $row['star'] ? round($row['star'],1) : 0;
			$val['teach_type'] = D('TeachType')->where(array('id'=>$val['teach_type']))->getField('name');
			$val['profile'] = strip_tags(html_entity_decode($val['profile']));
			if(I('distance') && $val['distance'] < I('distance')){
				$val['distance'] = $val['distance']>1?round($val['distance'],1).'km':(round($val['distance'],3)*1000).'m';
				$teacher[] = $val;
			}
				$val['distance'] = $val['distance']>1?round($val['distance'],1).'km':(round($val['distance'],3)*1000).'m';
		}
		$teachers && $this->returnSuccess('',$teacher ? $teacher : $teachers);
		$teachers || $this->returnError('暂无数据');
	}

	/*老师详情*/
	public function detail(){
		!I('request.id') && $this->returnError('参数错误');
		$where = array();
		$where['t.status'] = 1;
		var_dump($list);
	}


	/**
	 * 教师信息
	 */
	public function teacher_info(){
		(int)I('request.course_id') && $teacher_id = D('Course')->where(array('id'=>(int)I('request.course_id')))->getField('teacher_id');
		(int)I('request.teacher_id') && $teacher_id = (int)I('request.teacher_id');
		$teacher_id || $this->returnError('非法的访问');
		$teacher_info = D('Teacher')->where(array('id'=>$teacher_id))->find();
		$teacher_info && $this->returnSuccess('',output_data($teacher_info));
		$teacher_info || $this->returnError('暂无数据');
	}
	/**
	 * 老师授课类型
	 */
    public function teach_types(){
    	$types = D('TeachType')->order('sort_index asc,create_date desc')->select();
		$types && $this->returnSuccess('',$types);
		$types || $this->returnError('暂无数据');
    }
	
}