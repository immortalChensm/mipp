<?php
namespace Admin\Controller;

use Think\Page;

class CourseController extends BaseController {

	//课程列表
    public function index()
    {
    	$where = array();
    	$where['status'] = array('LT',3);
    	$this->admin_info['type'] == 2 && $where['teacher_id'] = $this->admin_info['teacher_id'];
        I('keywords') && $where['name'] = array('like','%'.I('keywords').'%');
        I('type') && $where['type'] = I('type');
    	$datapage = D('Course')->getPager($where,10,'create_date desc',true);
    	$list = $datapage['data'];
    	foreach ($list as $key=>$val){
    		$list[$key] = output_data($val);
    	}
		$this->assign('list',$list);
		$this->assign('page',$datapage['page']);
		$this->assign('course_types',getKeyValue(D('CourseType')->where(array('status'=>'1'))->select(),'id','name'));
		$this->assign('request',I('request.'));
        $this->display();
    }
    //待审核课程列表
    public function wait_check_list()
    {
    	$where = array();
    	$where['status'] = array('EGT',3);
    	$this->admin_info['type'] == 2 && $where['teacher_id'] = $this->admin_info['teacher_id'];
    	I('keywords') && $where['name'] = array('like','%'.I('keywords').'%');
    	I('type') && $where['type'] = I('type');
    	$datapage = D('Course')->getPager($where,10,'create_date desc',true);
    	$list = $datapage['data'];
    	foreach ($list as $key=>$val){
    		$list[$key] = output_data($val);
    	}
    	$this->assign('list',$list);
    	$this->assign('page',$datapage['page']);
    	$this->assign('course_types',getKeyValue(D('CourseType')->where(array('status'=>'1'))->select(),'id','name'));
    	$this->assign('request',I('request.'));
    	$this->display();
    }
    //申请列表
    public function apply_list()
    {
    	$where = array();
    	$where['status'] = array('BETWEEN','3,4');
    	I('keywords') && $where['name'] = array('like','%'.I('keywords').'%');
    	I('type') && $where['type'] = I('type');
    	$datapage = D('Course')->getPager($where,10,'create_date desc',true);
    	$list = $datapage['data'];
    	foreach ($list as $key=>$val){
    		$list[$key] = output_data($val);
    	}
    	$this->assign('list',$list);
    	$this->assign('page',$datapage['page']);
    	$this->assign('course_types',getKeyValue(D('CourseType')->where(array('status'=>'1'))->select(),'id','name'));
    	$this->assign('request',I('request.'));
    	$this->display();
    }
    public function edit(){
    	if(IS_POST){
			$info = input_data(I('post.info'));
			if (!D('Course')->create($info)) $this->error(D('Course')->getError());
			$info['teacher_id'] || $info['teacher_id'] = $this->admin_info['teacher_id'];
            $info['content'] = htmlentities($info['content']);
			$info['status'] = 3;//只要编辑了，就要审核
    		D('Course')->saveData($info) ? $this->success('保存成功!',U('Course/wait_check_list')) : $this->error('保存失败！');
    	}else {
    		$info = array();
    		(int)I('get.id') && $info = D('Course')->where(array('id'=>(int)I('get.id')))->find();
            $info['content'] = html_entity_decode($info['content']);
    		$this->assign('info',output_data($info));
    		$this->assign('tags',getKeyValue(D('CourseTag')->select(),'id','name'));
    		$this->assign('types',getKeyValue(D('CourseType')->select(),'id','name'));
    		$this->display();
    	}
    }
    
    public function course_info(){
        $info = array();
        (int)I('get.id') && $info = D('Course')->where(array('id'=>(int)I('get.id')))->find();
        $info = output_data($info);
        $info['content'] = html_entity_decode($info['content']);
        $info['type'] = D('CourseType')->where(array('id'=>$info['type']))->getField('name');
        $this->assign('info',$info);
        $this->display();
    }
    
    //删除课程（注销课程账号，伪删除）
    public function dele_course(){
    	if(IS_POST)
    	{
    		$id = (int)I('post.id');
    		$id || $this->error('非法的操作');
    		D('Course')->where(array('id'=>$id))->save(array('status'=>4)) && $this->success('数据删除成功！');
    		$this->error('网络异常，删除失败！');
    	}
    }
    //课程类型
	public function course_type()
	{
		$where = array();
		$where['status'] = 1;
		I('keywords') && $where['name'] = array('like','%'.I('keywords').'%');
		$datapage = D('CourseType')->getPager($where,10,'sort_index asc,create_date desc',true);
		$this->assign('list',$datapage['data']);
		$this->assign('page',$datapage['page']);
		$this->assign('request',I('request.'));
		$this->display();
	}
	//编辑课程类型
	public function edit_course_type()
	{
		$act = I('post.act');
		if ($act == 'save')
		{
			$info = I('post.info');
			if ($info['id'] == '') unset($info['id']);
			if (!D('CourseType')->create($info)) $this->error(D('CourseType')->getError());
			if (D('CourseType')->saveData($info)) 
			{
				$this->success('保存成功!');
			} 
			else 
			{
				$this->error('非法的操作！');
			}
		} 
		elseif($act == 'show')
		{
			$info = array();
			I('post.id') && $info = D('CourseType')->where(array('id'=>(int)I('id')))->find();
			$this->assign('info',$info);
			$this->assign('default_sort',getMaxValue('CourseType','sort_index')+1);
			$this->display();
		}
	}
	
	//删除课程类型
	public function dele_course_type()
	{
		if(IS_POST){
			$id = (int)I('post.id');
			$id || $this->error('非法的操作');
			$course = D('Course')->where(array('course_type'=>$id))->find();
			if($course)
			{
				$this->error('该类型下还有课程数据，不可删除！');
			}
			else 
			{
				D('CourseType')->where(array('id'=>$id))->save(array('status'=>2)) && $this->success('数据删除成功！');
				$this->error('网络异常，删除失败！');
			}
		}
	}
	//课程标签
	public function course_tag()
	{
		$where = array();
		$where['status'] = 1;
		I('keywords') && $where['name'] = array('like','%'.I('keywords').'%');
		$datapage = D('CourseTag')->getPager($where,10,'create_date desc',true);
		$this->assign('list',$datapage['data']);
		$this->assign('page',$datapage['page']);
		$this->assign('request',I('request.'));
		$this->display();
	}
	//编辑课程类型
	public function edit_course_tag()
	{
		$act = I('post.act');
		$info = I('post.info');
		if ($info['id'] == '') unset($info['id']);
		if (!D('CourseTag')->create($info)) $this->error(D('CourseTag')->getError());
		if (D('CourseTag')->saveData($info))
		{
			$this->success('保存成功!');
		}
		else
		{
			$this->error('非法的操作！');
		}
	}
	
	//删除课程类型
	public function dele_course_tag()
	{
		if(IS_POST){
			$id = (int)I('post.id');
			$id || $this->error('非法的操作');
			D('CourseTag')->where(array('id'=>$id))->delete() && $this->success('数据删除成功！');
			$this->error('网络异常，删除失败！');
		}
	}
	//推荐
	public function set_stick()
	{
		if(IS_POST)
		{
			$id = (int)I('post.id');
			$id || $this->error('非法的操作');
			$is_show = (int)I('post.is_stick') == '1'? '2':'1';
			D('Course')->where(array('id'=>$id))->save(array('is_stick'=>$is_show));
			$this->success('');
		}
	}
	//修改上下架状态
	public function set_status()
	{
		if(IS_POST)
		{
			$id = (int)I('post.id');
			$id || $this->error('非法的操作');
			$status = (int)I('post.status') == '1'? '2':'1';
			D('Course')->where(array('id'=>$id))->save(array('status'=>$status));
			$this->success('');
		}
	}
	//审核课程
    public function check_course(){
    	$id = (int)I('post.id');
    	$status = (int)I('post.status');
    	D('Course')->save(array('id'=>$id,'status'=>$status,'check_time'=>date('Y-m-d H:i:s'))) && $this->success('审核成功!');
        $this->error('网络异常，请稍后再试！');
    }
}