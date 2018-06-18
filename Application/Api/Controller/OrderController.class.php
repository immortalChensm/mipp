<?php
namespace Api\Controller;
class OrderController extends BaseController {

    //我的订单列表
    public function index(){
        !$this->user_id && $this->returnError('参数错误');
        
        $p = I('request.p') ? (int)I('request.p') : 1;
        $pagesize = 10;
        
        $where = array();
        $where['user_id'] = $this->user_id;
        if (I('status')) {
        	$where['status'] = I('status');
        }
        $list = D('Order')->where($where)->relation(true)->page($p,$pagesize)->select();
        foreach ($list as &$val){
        	$val['course'] = output_data($val['course']);
        }
        $this->returnSuccess('',$list);
    }

    //订单详情
    public function detail(){
        !I('id') && $this->returnError('参数错误');
        $info = D('Order')->where(array('id'=>I('id')))->relation(true)->find();
        $info['course'] = output_data($info['course']);
        $info['teacher'] = output_data($info['teacher']);
        $this->returnSuccess('',$info);
    }

    //取消订单
    public function del(){
        (!$this->user_id || !I('id')) && $this->returnError('参数错误');
        $order = D('Order')->where(array('id'=>I('id')))->getField('id');
        !$order && $this->returnError('订单不存在');
        $res = D('Order')->where(array('id'=>I('id')))->save(array('status'=>'4'));
        $res && $this->returnSuccess('取消成功');
    }

    //评价订单
    public function add_comment(){
        !$this->user_id && $this->returnError('参数错误');
        $data = I('request.');
        //自动验证
        if(!$r = D('Comment')->create($data)) $this->returnError(D('Comment')->getError());
        $res = D('Comment')->addorder($this->user_id,$data);
        if ($res) {
        	//更新订单状态为已评价
        	D('Order')->where(array('id'=>$data['order_id']))->save(array('comment_time'=>date('Y-m-d H:i:s'),'status'=>3));
        	$this->returnSuccess('评论成功');
        }else{
        	$this->returnError('系统繁忙，提交失败');
        }
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
    	$order_info['status'] = 1;
    	
    	//暂时让支付通 过
    	$order_info['pay_money'] = $order_info['price'];
    	$order_info['status'] = 2;
    	$order_info['pay_time'] = date('Y-m-d H:i:s');
    	
    	$res = D('Order')->add($order_info);
    	if ($res) {
	    	//增加销量
	    	//D('Course')->where(array('id'=>$course_info['id']))->setInc('sale_count');
	    	$paydata = $this->pay_order($post_data['openid'],$order_info);
	    	$this->returnSuccess('下单成功！',$paydata);
    	}else {
    		$this->returnError('系统繁忙，请您稍后操作');
    	}
    }
    /**
     * 统一下单
     * @param unknown $openid
     * @param unknown $order_info
     * @return Ambigous <json数据，可直接填入js函数作为参数, string>
     */
    private function pay_order($openid,$order_info)
    {
    	$order_info || $this->error('订单已失效！');
   		$total_fee = $order_info['price'];

    	$total  = $total_fee*100;
    	//$total = 1;//暂时使用
    
    	vendor("Wxpay.WxPayJsApiPay");
    	$tools = new \JsApiPay();
    	$input = new \WxPayUnifiedOrder();
    
    	$input->SetBody("C2C艺术教育");
    	$input->SetAttach($order_info['order_sn']);
    	$input->SetOut_trade_no($order_info['order_sn']);
    	$input->SetTotal_fee($total);
    	$input->SetTime_start(date("YmdHis"));
    	$input->SetTime_expire(date("YmdHis", time() + 600));
    	$input->SetGoods_tag("C2C艺术教育");
    	$input->SetNotify_url('http://'.$_SERVER['HTTP_HOST'].'/Home/Order/order_notify');
    	$input->SetTrade_type("JSAPI");
    	$input->SetOpenid($openid);
    	$order = \WxPayApi::unifiedOrder($input);
    	return $tools->GetJsApiParameters($order);
    }
    //支付成功的通知函数
    public function order_notify()
    {
    	$xml      = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : "";
    	$xmlObj   = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    	$xmlArr   = json_decode(json_encode($xmlObj), true);
    	if($xmlArr['return_code']=='SUCCESS'){
    		$total_fee = $xmlArr['total_fee']/100;
    		$order_sn = $xmlArr['attach'];
    		if($total_fee){
    			//更新订单状态
    
    			exit('SUCCESS');
    		}else{
    			exit('FAIL');
    		}
    	}
    	exit('FAIL');
    }
    /**
     * 获取订单号
     * @return string
     */
    private function getOrderSn(){
    	return date('YmdHis').mt_rand(1000, 9999);
    }
}