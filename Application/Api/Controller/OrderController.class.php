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
    
    //添加订单
    public function add_order(){
    	$post_data = I('request.');
    	$post_data || $this->returnError('非法的操作');
    	$post_data['name'] || $this->returnError('请输入姓名');
    	$post_data['phone'] || $this->returnError('请输入手机号');
    	$user_id = D('User')->where(array('openid'=>$post_data['openid']))->getField('id');
    	$course_info = D('Course')->where(array('id'=>$post_data['course_id'],'status'=>1))->find();
    	$course_info || $this->returnError('此课程已被删除或下架');
    	$order_info = array();
    	$order_info['type'] = $post_data['type'];
    	$order_info['user_id'] = $user_id;
    	$order_info['teacher_id'] = $course_info['teacher_id'];
    	$order_info['course_id'] = $course_info['id'];
    	$order_info['goods_num'] = $post_data['goods_num'];
    	$order_info['order_sn'] = $this->getOrderSn();
    	$order_info['price'] = $course_info['price']*$post_data['goods_num'];
    	$order_info['name'] = $post_data['name'];
    	$order_info['phone'] = $post_data['phone'];
    	$order_info['status'] = 2;
    	
    	//暂时让支付通 过
    	$order_info['pay_money'] = $order_info['price'];
    	$order_info['status'] = 1;
    	$order_info['pay_time'] = date('Y-m-d H:i:s');
    	D('Order')->add($order_info) ? $this->returnSuccess('下单成功！') : $this->returnError('系统繁忙，请您稍后操作');
    }
    
    /**
     * 获取订单号
     * @return string
     */
    private function getOrderSn(){
    	return date('YmdHis').mt_rand(1000, 9999);
    }
}