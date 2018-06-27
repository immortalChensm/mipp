<?php

namespace Admin\Controller;

class CommentController extends BaseController {
	//老师评价列表
	public function teacher_cnlist()
    {
    	$where = array();
        $this->admin_info['type'] == 2 && $where['relation_id'] = $this->admin_info['teacher_id'];
    	$where['type'] = 1;
        if (I('keywords')) {
        	$teacher_ids = D('Teacher')->where(array('name'=>array('like','%'.I('keywords').'%')))->getField('id',true);
			$teacher_ids && $where['relation_id'] = array('IN',implode(',', $teacher_ids));
        
			$teacher_ids || $where['type'] = 0;
        }
    	$datapage = D('Comment')->getPager($where,10,'create_date desc',true);
    	$list = $datapage['data'];
    	foreach ($list as $key=>$val){
    		$list[$key]['teacher_name'] = D('Teacher')->where(array('id'=>$val['relation_id']))->getField('name');
    	}
		$this->assign('list',$list);
		$this->assign('page',$datapage['page']);
		$this->assign('request_data',I('request.'));
        $this->display();
	}
	//课程评价列表
	public function course_cnlist()
	{
    	$where = array();
        if($this->admin_info['type'] == 2){
            $course_ids = D('Course')->where(array('teacher_id'=>$this->admin_info['teacher_id']))->getField('id',true);
            $course_ids && $where['relation_id'] = array('IN',implode(',',$course_ids));
        }
    	$where['type'] = 2;
        if (I('keywords')) {
        	$course_ids = D('Course')->where(array('name'=>array('like','%'.I('keywords').'%')))->getField('id',true);
			$course_ids && $where['relation_id'] = array('IN',implode(',', $course_ids));
			$course_ids || $where['type'] = 0;
        }
    	$datapage = D('Comment')->getPager($where,10,'create_date desc',true);
    	$list = $datapage['data'];
    	foreach ($list as $key=>$val){
    		$list[$key]['course_name'] = D('Course')->where(array('id'=>$val['relation_id']))->getField('name');
    	}
		$this->assign('list',$list);
		$this->assign('page',$datapage['page']);
		$this->assign('request_data',I('request.'));
        $this->display();
	}
	//修改显示状态
	public function set_status()
	{
		if(IS_POST)
		{
			$id = (int)I('post.id');
			$id || $this->error('非法的操作');
			$status = (int)I('post.status') == '1'? '2':'1';
			D('Comment')->where(array('id'=>$id))->save(array('status'=>$status));
			$this->success('');
		}
	}
}