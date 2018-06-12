<?php

namespace Admin\Controller;

class UserController extends BaseController {

	//会员列表
	public function index(){
    	$where = array();
    	I('keywords') && $where['nickname|name|phone'] = array('like','%'.I('keywords').'%');
    	I('identify') && $where['identify'] = (int)I('identify');
    	$datapage = D('User')->getPager($where,10,'create_date desc');
    	$list = $datapage['data'];
    	foreach ($list as $key=>$val){
    		$money_res = D('Order')->field("sum(price) as consume_money")->where(array('user_id'=>$val['id']))->find();
    		$list[$key]['consume_money'] = $money_res['consume_money']?$money_res['consume_money']:0;
    		($val['identify'] == 2) && $list[$key]['teacher_id'] = D('Teacher')->where(array('user_id'=>$val['id']))->getField('id');
    	}
		$this->assign('list',$list);
		$this->assign('page',$datapage['page']);
		$this->assign('request_data',I('request.'));
        $this->display();
    }
    
    //编辑会员
    public function edit_user(){
    	if(IS_POST){
    		D('User')->create(I('post.info')) || $this->error(D('User')->getError());
    		D('User')->saveData(I('post.info')) ? $this->success('保存成功!') : $this->error('保存失败！');
    	}else {
    		$info = array();
    		(int)I('get.id') && $info = D('User')->where(array('id'=>(int)I('get.id')))->find();
    		$this->assign('info',$info);
    		$this->assign('area_data',D('Province')->getProvince($info['province_id']));
    		$this->display();
    	}
    }
    
}