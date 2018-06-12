<?php

namespace Admin\Controller;

class StatisticController extends BaseController {

	/*会员统计*/
	public function user_data(){
		 //网站总浏览量
		 $where = array();
		 if(I('begin_date') && !I('end_date')){
            $where['create_date'] = array('EGT',date('Y-m-d',strtotime(I('begin_date'))));
        }elseif(!I('begin_date') && I('end_date')){
            $where['create_date'] = array('LT',date('Y-m-d',strtotime('+1 day',strtotime(I('end_date')))));
        }elseif(I('begin_date') &&I('end_date')){
            $where['create_date'] = array('between',array(date('Y-m-d',strtotime(I('begin_date'))),date('Y-m-d',strtotime('+1 day',strtotime(I('get.end_date'))))));
        }
        $list = D('User')->where($where)->field('left(create_date,10) as create_date')->distinct(true)->order('create_date asc')->select();
        $num_time = array();
        $num_value = array();
        foreach ($list as $val) {
           $num_time[] = $val['create_date'];
           $num_value[] = D('User')->where(array('left(create_date,10)'=>$val['create_date']))->count();
        }
        $this->assign('num_time',json_encode($num_time));
        $this->assign('num_value',json_encode($num_value));
        $this->assign('request_data',I('request.'));
		$this->display();
	}

	/*订单统计*/
	public function order_data(){
		//网站总浏览量
		$where = array();
		if(I('begin_date') && !I('end_date')){
			$where['create_date'] = array('EGT',date('Y-m-d',strtotime(I('begin_date'))));
		}elseif(!I('begin_date') && I('end_date')){
			$where['create_date'] = array('LT',date('Y-m-d',strtotime('+1 day',strtotime(I('end_date')))));
		}elseif(I('begin_date') &&I('end_date')){
			$where['create_date'] = array('between',array(date('Y-m-d',strtotime(I('begin_date'))),date('Y-m-d',strtotime('+1 day',strtotime(I('get.end_date'))))));
		}
		$list = D('Order')->where($where)->field('left(create_date,10) as create_date')->distinct(true)->order('create_date asc')->select();
		$num_time = array();
		$num_value = array();
		foreach ($list as $val) {
			$num_time[] = $val['create_date'];
			$num_value[] = D('Order')->where(array('left(create_date,10)'=>$val['create_date']))->count();
		}
		$this->assign('num_time',json_encode($num_time));
		$this->assign('num_value',json_encode($num_value));
		$this->assign('request_data',I('request.'));
		$this->display();
	}
	
	/*课程统计*/
	public function course_data(){
		//网站总浏览量
		 $where = array();
		 if(I('begin_date') && !I('end_date')){
            $where['create_date'] = array('EGT',date('Y-m-d',strtotime(I('begin_date'))));
        }elseif(!I('begin_date') && I('end_date')){
            $where['create_date'] = array('LT',date('Y-m-d',strtotime('+1 day',strtotime(I('end_date')))));
        }elseif(I('begin_date') &&I('end_date')){
            $where['create_date'] = array('between',array(date('Y-m-d',strtotime(I('begin_date'))),date('Y-m-d',strtotime('+1 day',strtotime(I('get.end_date'))))));
        }
        $list = D('Course')->where($where)->field('left(create_date,10) as create_date')->distinct(true)->order('create_date asc')->select();
        $num_time = array();
        $num_value = array();
        foreach ($list as $val) {
           $num_time[] = $val['create_date'];
           $num_value[] = D('Course')->where(array('left(create_date,10)'=>$val['create_date']))->count();
        }
        $this->assign('num_time',json_encode($num_time));
        $this->assign('num_value',json_encode($num_value));
        $this->assign('request_data',I('request.'));
		$this->display();
	}

	/*老师统计*/
	public function teacher_data(){
		//网站总浏览量
		$where = array();
		if(I('begin_date') && !I('end_date')){
			$where['create_date'] = array('EGT',date('Y-m-d',strtotime(I('begin_date'))));
		}elseif(!I('begin_date') && I('end_date')){
			$where['create_date'] = array('LT',date('Y-m-d',strtotime('+1 day',strtotime(I('end_date')))));
		}elseif(I('begin_date') &&I('end_date')){
			$where['create_date'] = array('between',array(date('Y-m-d',strtotime(I('begin_date'))),date('Y-m-d',strtotime('+1 day',strtotime(I('get.end_date'))))));
		}
		$list = D('Teacher')->where($where)->field('left(create_date,10) as create_date')->distinct(true)->order('create_date asc')->select();
		$num_time = array();
		$num_value = array();
		foreach ($list as $val) {
			$num_time[] = $val['create_date'];
			$num_value[] = D('Teacher')->where(array('left(create_date,10)'=>$val['create_date']))->count();
		}
		$this->assign('num_time',json_encode($num_time));
		$this->assign('num_value',json_encode($num_value));
		$this->assign('request_data',I('request.'));
		$this->display();
	}

}

?>