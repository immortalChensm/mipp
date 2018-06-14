<?php
namespace Api\Controller;
class UserController extends BaseController {

    //热门推荐职位
    public function index(){
        !$this->user_id && $this->returnError('参数错误');
        //用户信息
        $data = D('User')->where(array('id'=>$this->user_id))->Field('id,nickname,headimgurl,identify')->find();  
        //是否申请成为老师
        $data['status'] = $data['identify'] == '1' ?  D('Teacher')->where(array('user_id'=>$data['id']))->getField('status') : '0';
        //待付款订单
        $data['pay_num'] = D('Order')->where(array('user_id'=>$data['id'],'status'=>'2'))->count();
        //待评价订单
        $data['com_num'] = D('Order')->where(array('user_id'=>$data['id'],'status'=>'1'))->count();
        $this->returnSuccess('',$data);
    }

}