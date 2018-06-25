<?php
namespace Admin\Controller;

use Think\Page;
use Think\Verify;

class TeacherController extends BaseController {

	//教师列表
    public function index()
    {
    	$where = array();
    	$where['status'] = '1';
        I('keywords') && $where['name|nickname|phone'] = array('like','%'.I('keywords').'%');
        I('teach_type') && $where['teach_type'] = I('teach_type');
        I('sex') && $where['sex'] = I('sex');
    	$datapage = D('Teacher')->getPager($where,10,'create_date desc',true);
    	$list = $datapage['data'];
    	foreach ($list as $key=>$val){
    		$list[$key]['has_account'] = D('Admin')->where(array('teacher_id'=>$val['id']))->count()?true:false;
    	}
		$this->assign('list',$list);
		$this->assign('page',$datapage['page']);
		$this->assign('teach_types',getKeyValue(D('TeachType')->where(array('status'=>'1'))->select(),'id','name'));
		$this->assign('request',I('request.'));
        $this->display();
    }
    //添加、编辑报名
    public function edit()
    {
		if(IS_POST){
			$info = input_data(I('post.info'));
			$location = I('post.location');
			if ($location) {
				$locats = explode(',', $location);
				$info['lng'] = $locats[0];
				$info['lat'] = $locats[1];
			}
            $info['content'] = htmlentities($info['content']);
            if(!D('Teacher')->create($info))$this->error(D('Teacher')->getError());
			D('Teacher')->saveData($info) ? $this->success('保存成功!',U('Teacher/index')) : $this->error('保存失败！');
		}else{
			if ($this->admin_info['type'] == 2) {
				$id = $this->admin_info['teacher_id'];
			}elseif ((int)I('get.id')){
				$id = (int)I('get.id');
			}
			$id || $this->error('非法的操作');
            $info = D('Teacher')->where(array('id'=>$id))->find();
            $info || $this->error('非法的操作');
            $info['content'] = html_entity_decode($info['content']);
            $this->assign('info',output_data($info));
            $this->assign('teach_types',getKeyValue(D('TeachType')->where(array('status'=>'1'))->select(),'id','name'));
			$this->display();
		}
    }
    //删除教师（注销教师账号，伪删除）
    public function dele_teacher(){
    	if(IS_POST)
    	{
    		$id = (int)I('post.id');
    		$id || $this->error('非法的操作');
    		D('Teacher')->where(array('id'=>$id))->save(array('status'=>4)) && $this->success('数据删除成功！');
    		$this->error('网络异常，删除失败！');
    	}
    }
    //教师类型
	public function teacher_type()
	{
		$where = array();
		$where['status'] = 1;
		I('keywords') && $where['name'] = array('like','%'.I('keywords').'%');
		$datapage = D('TeacherType')->getPager($where,10,'sort_index asc,create_date desc',true);
		$this->assign('list',$datapage['data']);
		$this->assign('page',$datapage['page']);
		$this->assign('request',I('request.'));
		$this->display();
	}
	//编辑教师类型
	public function edit_teacher_type()
	{
		$act = I('post.act');
		if ($act == 'save')
		{
			$info = I('post.info');
			if ($info['id'] == '') unset($info['id']);
			if (!D('TeacherType')->create($info)) $this->error(D('TeacherType')->getError());
			if (D('TeacherType')->saveData($info)) 
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
			I('post.id') && $info = D('TeacherType')->where(array('id'=>(int)I('id')))->find();
			$this->assign('info',$info);
			$this->assign('default_sort',getMaxValue('TeacherType','sort_index')+1);
			$this->display();
		}
	}
	
	//删除教师类型
	public function dele_teacher_type()
	{
		if(IS_POST){
			$id = (int)I('post.id');
			$id || $this->error('非法的操作');
			$teacher = D('Teacher')->where(array('teacher_type'=>$id))->find();
			if($teacher)
			{
				$this->error('该类型下还有教师数据，不可删除！');
			}
			else 
			{
				D('TeacherType')->where(array('id'=>$id))->save(array('status'=>2)) && $this->success('数据删除成功！');
				$this->error('网络异常，删除失败！');
			}

		}
	}
	
	//授课类型
	public function teach_type()
	{
		$where = array();
		$where['status'] = 1;
		I('keywords') && $where['name'] = array('like','%'.I('keywords').'%');
		$datapage = D('TeachType')->getPager($where,10,'sort_index asc,create_date desc',true);
		$this->assign('list',$datapage['data']);
		$this->assign('page',$datapage['page']);
		$this->assign('request',I('request.'));
		$this->display();
	}
	//编辑授课类型
	public function edit_teach_type()
	{
		$act = I('post.act');
		if ($act == 'save')
		{
			$info = I('post.info');
			if ($info['id'] == '') unset($info['id']);
			if (!D('TeachType')->create($info)) $this->error(D('TeachType')->getError());
			if (D('TeachType')->saveData($info)) 
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
			I('post.id') && $info = D('TeachType')->where(array('id'=>(int)I('id')))->find();
			$this->assign('info',$info);
			$this->assign('default_sort',getMaxValue('TeachType','sort_index')+1);
			$this->display();
		}
	}
	//删除授课类型
	public function dele_teach_type()
	{
		if(IS_POST)
		{
			$id = (int)I('post.id');
			$id || $this->error('非法的操作');
			$teacher = D('Teacher')->where(array('teach_type'=>$id))->find();
			if($teacher)
			{
				$this->error('该类型下还有教师数据，不可删除！');
			}
			else 
			{
				D('TeachType')->where(array('id'=>$id))->save(array('status'=>2)) && $this->success('数据删除成功！');
				$this->error('网络异常，删除失败！');
			}
	
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
			D('Teacher')->where(array('id'=>$id))->save(array('is_stick'=>$is_show));
			$this->success('');
		}
	}
	//申请列表
	public function apply_list()
	{
		$where = array();
		$where['status'] = array('between','2,3');
		I('keywords') && $where['name|nickname|phone'] = array('like','%'.I('keywords').'%');
		I('teach_type') && $where['teach_type'] = I('teach_type');
		I('sex') && $where['sex'] = I('sex');
		$datapage = D('Teacher')->getPager($where,10,'create_date desc',true);
		$this->assign('list',$datapage['data']);
		$this->assign('page',$datapage['page']);
		$this->assign('teach_types',getKeyValue(D('TeachType')->where(array('status'=>'1'))->select(),'id','name'));
		$this->assign('request',I('request.'));
		$this->display();
	}
	//审核教师
    public function check_teacher(){
    	$id = (int)I('post.id');
    	$status = (int)I('post.status');
    	D('Teacher')->save(array('id'=>$id,'status'=>$status,'check_time'=>date('Y-m-d H:i:s'))) && $this->success('审核成功!');
        $this->error('网络异常，请稍后再试！');
    }
    
    public function info(){
        $id = (int)I('post.id');
        $info = D('Teacher')->where(array('id'=>$id))->find();
        $this->assign('info',$info);
        $this->display();
    }
}