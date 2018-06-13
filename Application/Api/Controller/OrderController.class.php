<?php
namespace Api\Controller;
class OrderController extends BaseController {

    //我的订单列表
    public function index($p = 1){
        !$this->user_id && $this->returnError('参数错误');
        $where = array();
        $where['user_id'] = $this->user_id;
        I('status') && $where['status'] = I('status');
        $list = D('Order')->where($where)->field('order_sn,name,pay_money,create_time,status')->relation(true)->page($p,8)->select();
        $this->returnSuccess('',$list);
    }

    //订单详情
    public function detail(){
        !I('id') && $this->returnError('参数错误');
        $info = D('Order')->where(array('id'=>I('id')))->field('order_sn,name,phone,pay_money,status,pay_time,comment_time,create_time')->relation(true)->find();
        $this->returnSuccess('',$info);
    }

    //取消订单
    public function del(){
        (!$this->user_id || !I('id')) && $this->returnError('参数错误');
        $order = D('Order')->where(array('id'=>I('id')))->getField('id');
        !$order && $this->returnError('订单不存在');
        $res = D('Order')->where(array('id'=>I('id')))->delete();
        $res && $this->returnSuccess('取消成功');
    }

    //评价订单
    public function comment(){
        !$this->user_id && $this->returnError('参数错误');
        $data = I('post.');
        //自动验证
        $data = D('Comment')->create($data);
        !$data &&$this->error(D('Comment')->getError());
        $res = D('Comment')->addorder($this->user_id,$data);
        $res && $this->returnSuccess('评论成功');
        
    }
}