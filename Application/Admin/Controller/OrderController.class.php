<?php
namespace Admin\Controller;

use Think\Page;
use Think\Verify;

class OrderController extends BaseController {

	//订单列表
    public function index(){
    	$where = array();
    	$where['type'] = 1;
    	$where['status'] = array('NEQ',4);
    	$this->admin_info['type'] == 2 && $where['teacher_id'] = $this->admin_info['teacher_id'];
        I('keywords') && $where['order_sn'] = array('like','%'.I('keywords').'%');
        I('status') && $where['status'] = I('status');
        if(I('begin_date') && !I('end_date')){
        	$where['create_date'] = array('EGT',date('Y-m-d',strtotime(I('begin_date'))));
        }elseif(!I('begin_date') && I('end_date')){
        	$where['create_date'] = array('LT',date('Y-m-d',strtotime('+1 day',strtotime(I('end_date')))));
        }elseif(I('begin_date') &&I('end_date')){
        	$where['create_date'] = array('between',array(date('Y-m-d',strtotime(I('begin_date'))),date('Y-m-d',strtotime('+1 day',strtotime(I('get.end_date'))))));
        }
    	$datapage = D('Order')->getPager($where,10,'create_date desc',true);
		$this->assign('list',$datapage['data']);
		$this->assign('page',$datapage['page']);
		$this->assign('request_data',I('request.'));
        $this->display();
    }
	
	//预约订单列表
    public function yuyue_order(){
    	$where = array();
    	$where['type'] = 2;
    	$where['status'] = array('NEQ',4);
    	$this->admin_info['type'] == 2 && $where['teacher_id'] = $this->admin_info['teacher_id'];
        I('keywords') && $where['order_sn'] = array('like','%'.I('keywords').'%');
        I('status') && $where['status'] = I('status');
        if(I('begin_date') && !I('end_date')){
        	$where['create_date'] = array('EGT',date('Y-m-d',strtotime(I('begin_date'))));
        }elseif(!I('begin_date') && I('end_date')){
        	$where['create_date'] = array('LT',date('Y-m-d',strtotime('+1 day',strtotime(I('end_date')))));
        }elseif(I('begin_date') &&I('end_date')){
        	$where['create_date'] = array('between',array(date('Y-m-d',strtotime(I('begin_date'))),date('Y-m-d',strtotime('+1 day',strtotime(I('get.end_date'))))));
        }
    	$datapage = D('Order')->getPager($where,10,'create_date desc',true);
		$this->assign('list',$datapage['data']);
		$this->assign('page',$datapage['page']);
		$this->assign('request_data',I('request.'));
        $this->display();
    }
    //用户订单列表
    public function user_order(){
    	$where = array();
    	$where['status'] = array('NEQ',4);
    	$where['user_id'] = (int)I('user_id');
    	$user_info = D('User')->where(array('id'=>(int)I('user_id')))->find();
    	$user_info || $this->error('非法的访问');
    	I('keywords') && $where['order_sn'] = array('like','%'.I('keywords').'%');
    	I('type') && $where['type'] = I('type');
    	if(I('begin_date') && !I('end_date')){
    		$where['create_date'] = array('EGT',date('Y-m-d',strtotime(I('begin_date'))));
    	}elseif(!I('begin_date') && I('end_date')){
    		$where['create_date'] = array('LT',date('Y-m-d',strtotime('+1 day',strtotime(I('end_date')))));
    	}elseif(I('begin_date') &&I('end_date')){
    		$where['create_date'] = array('between',array(date('Y-m-d',strtotime(I('begin_date'))),date('Y-m-d',strtotime('+1 day',strtotime(I('get.end_date'))))));
    	}
    	$datapage = D('Order')->getPager($where,10,'create_date desc',true);
    	$this->assign('list',$datapage['data']);
    	$this->assign('page',$datapage['page']);
    	$this->assign('request_data',I('request.'));
    	$this->assign('user_info',$user_info);
    	$this->display();
    }
    //获取订单详情
    public function detail(){
        if(IS_POST){
            $id = (int)I('post.id');
            $id || $this->error('参数有误');
            $info = D('Order')->relation(true)->where(array('id'=>$id))->find();
            $info['course'] = output_data($info['course']);
            $this->assign('info',$info);
            $this->display();
        }
    }

    //关闭订单
    public function close_order(){
    	if(IS_POST)
    	{
    		$id = (int)I('post.id');
    		$id || $this->error('非法的操作');
    		D('Order')->where(array('id'=>$id))->save(array('status'=>4)) && $this->success('');
    		$this->error('网络异常，删除失败！');
    	}
    }
}