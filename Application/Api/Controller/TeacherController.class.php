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
		D('Teacher')->field('*,ACOS(SIN((' . $lat . ' * '.M_PI.') / 180) * SIN((lat * '.M_PI.') / 180 ) +COS((' . $lat . ' * '.M_PI.') / 180 ) * COS((lat * '.M_PI.') / 180 ) *COS((' . $lng . '* '.M_PI.') / 180 - (lng * '.M_PI.') / 180 ) ) * 6371 as distance');
		
		$teachers = D('Teacher')->where($where)->limit(6)->order('distance asc,create_date asc')->select();
		foreach ($teachers as &$val){
			$row = D('Comment')->field('AVG(star) as star')->where(array('type'=>1,'relation_id'=>1))->find();
			$val['star'] = $row['star'] ? round($row['star'],1) : 0;
			$val['distance'] = $val['distance']>1?round($val['distance'],1).'km':(round($val['distance'],3)*1000).'m';
			$val['teach_type'] = D('TeachType')->where(array('id'=>$val['teach_type']))->getField('name');
			$val['profile'] = strip_tags(html_entity_decode($val['profile']));
		}
		$teachers && $this->returnSuccess('',$teachers);
		$teachers || $this->returnError('暂无数据');
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