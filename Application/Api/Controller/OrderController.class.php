<?php
namespace Api\Controller;
class OrderController extends BaseController {

    //我的订单
    public function index($p = 1){
        !$this->user_id && $this->returnError('参数错误');
        $where = array();
        $where['user_id'] = $this->user_id;
        I('status') && $where['status'] = I('status');
        $list = D('Order')->where($where)->field('order_sn,pay_money,create_time,status')->relation(true)->page($p,8)->select();
        $this->returnSuccess('',$list);
    }

}