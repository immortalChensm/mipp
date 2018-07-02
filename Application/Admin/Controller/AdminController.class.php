<?php
namespace Admin\Controller;

use Think\Page;
use Think\Verify;

class AdminController extends BaseController {

    public function index(){
    	$where = array();
    	$where['status'] = '1';
    	$where['type'] = '2';
    	I('keywords') && $where['role_name'] = array('like','%'.I('keywords').'%');
    	$datapage = D('Admin')->getPager($where,10);
		$this->assign('list',$datapage['data']);
		$this->assign('page',$datapage['page']);
		$this->assign('request_data',I('request.'));
        $this->display();
    }
    //添加、编辑教师账号
    public function add_adminer(){
    	if(IS_POST){
    		$info = I('post.info');
    		if(!D('Admin')->create($info))$this->error(D('Admin')->getError());
    		$info['password'] = encrypt($info['password']);
    		$teacher = D('Teacher')->where(array('id'=>$info['teacher_id']))->find();
    		$info['phone'] = $teacher['phone'];
    		$info['email'] = $teacher['email'];
    		D('Admin')->saveData($info) ? $this->success('保存成功!',U('Admin/index')) : $this->error('保存失败！');
    	}else{
    		$info = $teacher = array();
    		(int)I('get.id') && $info = D('Admin')->where(array('id'=>(int)I('get.id')))->find();
    		(int)I('get.teacher_id') && $teacher = D('Teacher')->where(array('id'=>(int)I('get.teacher_id')))->find();
    		$this->assign('info',$info);
    		$this->assign('teacher',$teacher);
    		$this->display();
    	}
    }
    //修改密码
    public function edit_pass(){
    	if(IS_POST){
    		$info = I('post.info');
    		if(!D('Admin')->create($info))$this->error(D('Admin')->getError());
    		D('Admin')->where(array('id'=>$this->admin_info['id']))->save(array('password'=> encrypt($info['password']))) ? $this->success('保存成功!',U('Admin/index')) : $this->error('保存失败！');
    	}else{
    		$this->display();
    	}
    }
    /*
     * 管理员登陆
     */
    public function login(){
        // echo phpinfo(); die();
        if(session('admin_id')>0){
             $this->error("您已登录",U('Index/index'));
        }
        if(IS_POST){
            $where = array();
            $where['user_name'] = I('post.username');
            $where['password'] = I('post.password');
            if(!$res = D('Admin')->create($where)){
            	$this->error(D('Admin')->getError());
            }else{
                $where['password'] = encrypt($where['password']);
                $admin_info = D('Admin')->where($where)->find();
                if(is_array($admin_info)){
                    //老师账号需验证老师状态
                    if($admin_info['type'] == '2'){
                        $teacher = D('Teacher')->where(array('id'=>$admin_info['teacher_id']))->find();
                        $teacher['status'] != '1' && $this->error('该账号尚未审核通过或已被注销');
                    }
                	session('admin_id',$admin_info['id']);
                	$url = session('from_url') ? session('from_url') : U('Admin/Index/index');
                	$this->success('登录成功！',$url);
                }else{
                	$this->error('账号或密码错误');
                }
            }
        }
        
        $this->display();
    }
    /**
     * 越权登录返回
     */
    public function over_login_back(){
    	$over_admin_id = session('over_admin_id');
    	if(!empty($over_admin_id)){
            session('cururl',null);
    		session('admin_id',$over_admin_id);
    		session('over_admin_id',null);
    		$this->redirect(U('Index/index'));
    	}else{
    		$this->error('非法的操作',U('Index/index'));
    	}
    }
    /**
     * 越权登录
     */
    public function over_login(){
    	$admin_info = array();
    	if((int)I('teacher_id')){
    		if(!$teacher = D('Teacher')->where(array('id'=>(int)I('teacher_id'),'status'=>'1'))->find()){
    			$this->error('此用户不是老师或账号已被注销',U('Teacher/adminer',array('teacher_id'=>(int)I('teacher_id'))));
    		}
    		$admin_info = D('Admin')->where(array('teacher_id'=>(int)I('teacher_id')))->find();
    		$admin_info || $this->error('尚未创建老师管理账号',U('Admin/add_adminer',array('teacher_id'=>$teacher['id'])));
    	}
    	(int)I('id') && $admin_info = D('Admin')->where(array('id'=>(int)I('id')))->find();
    	if(empty($admin_info)){
    		$this->error('不存在此用户',$_SERVER['HTTP_REFERER']);
    	}else{
    		$cur_suser = session('admin_id');
    		session('over_admin_id',$cur_suser);
    		session('admin_id',$admin_info['id']);
    		session('cururl',null);
    		echo '<script type="text/javascript">
                        if(window.top==window.self){
                                window.location.href="'.U('Index/index').'";
                        }else{
                                parent.window.location.reload();
                        }
                  </script>';
    		exit;
    	}
    }
    /**
     * 退出登陆
     */
    public function logout(){
        session_unset();
        session_destroy();
        $this->success("退出成功",U('Admin/Admin/login'));
    }
	//删除后台管理员
	public function dele_adminer(){
		if(IS_POST){
			$id = (int)I('post.id');
			$id || $this->error('非法的操作');
			D('Admin')->where(array('id'=>$id))->save(array('status'=>'2'));
			$this->success('删除成功！');
		}
	}
}