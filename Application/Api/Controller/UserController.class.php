<?php
namespace Api\Controller;
class UserController extends BaseController {

    //热门推荐职位
    public function index(){
        !$this->user_id && $this->returnError('非法的操作');
        //用户信息
        $data = D('User')->where(array('id'=>$this->user_id))->Field('id,nickname,headimgurl,identify')->find();  
        //待付款订单
        $data['pay_num'] = D('Order')->where(array('user_id'=>$data['id'],'status'=>'1'))->count();
        //待评价订单
        $data['com_num'] = D('Order')->where(array('user_id'=>$data['id'],'status'=>'2'))->count();
        $this->returnSuccess('',$data);
    }
    //收藏/关注  老师或课程
    public function follow(){
    	!$this->user_id && $this->returnError('非法的操作');
    	$type = I('request.type');
    	$relation_id = I('request.relation_id');
    	$type && $relation_id || $this->returnError('非法的操作');
    	D('Follow')->where(array('user_id'=>$this->user_id,'relation_id'=>$relation_id))->count() && $this->returnSuccess('您已收藏');
    	$flw_data = array();
    	$flw_data['user_id'] = $this->user_id;
    	$flw_data['follow_type'] = $type;
    	$flw_data['relation_id'] = $relation_id;
    	$res = D('Follow')->add($flw_data);
    	$res ? $this->returnSuccess('收藏成功') : $this->returnError('系统繁忙，请稍后再试');
    }

}