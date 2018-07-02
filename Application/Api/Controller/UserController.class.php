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
        //是否是老师
        $data['teacher_status'] = D('Teacher')->where(array('user_id'=>$this->user_id))->getField('status');
        $this->returnSuccess('',$data);
    }
    //收藏/关注  老师或课程
    public function follow(){
    	!$this->user_id && $this->returnError('非法的操作');
    	$type = I('request.type');
    	$relation_id = I('request.relation_id');
    	$type && $relation_id || $this->returnError('非法的操作');
    	D('Follow')->where(array('user_id'=>$this->user_id,'follow_type'=>$type,'relation_id'=>$relation_id))->count() && $this->returnError('您已收藏');
    	$flw_data = array();
    	$flw_data['user_id'] = $this->user_id;
    	$flw_data['follow_type'] = $type;
    	$flw_data['relation_id'] = $relation_id;
    	$res = D('Follow')->add($flw_data);
        if($res){
            if($type == 1){
                $follow_num = D('Follow')->where(array('relation_id'=>$relation_id,'type'=>1))->count();
                $this->returnSuccess('关注成功',array('follow_num'=>$follow_num,'follow_id'=>$res)) ;
            }else{
                $this->returnSuccess('收藏成功',$res);
            }
        }else{
                $this->returnError('系统繁忙，请稍后再试');
        }
    }

}