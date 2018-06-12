<?php
namespace Admin\Controller;
use Think\Page;
use Think\Verify;
class ExportController extends BaseController{


    //报名导出
    public function enroll(){

        vendor('Excel.Excel');
        $title_arr = array('姓名','性别','手机号','报名职位','职位所属企业','审核状态','报名时间');
        $where = array();
        I('keyword') && $where['name'] = I('keyword');
        I('phone') && $where['phone'] = I('phone');
        I('sex') && $where['sex'] = I('sex');
        $data = D('Enroll')->where($where)->select();
        $content_arr = array();
        foreach($data as $k=>$v){
            $info = D('Position')->where(array('id'=>$v['position_id']))->find();
            $status = '已报名';
            if($v['status'] == '2'){
                $status = '已确认';
            }else if($v['status'] =='3'){
                $status = '不合格';
            }else if($v['status'] =='4'){
                $status = '已反馈';
            }else if($v['status'] =='5'){
                $status = '已完成';
            }else if($v['status'] =='6'){
                $status = '已取消';
            }
            $sex = '未知';
            if($v['sex'] =='1'){
                $sex = '男';
            }elseif($v['sex'] =='2'){
                $sex = '女';
            }
            $arr = array();
            $arr[] = $v['name'];
            $arr[] = $sex;
            $arr[] = $v['phone'];
            $arr[] = $info['title'];
            $arr[] = D('Company')->where(array('id'=>$info['company_id']))->getField('name');
            $arr[] = $status;
            $arr[] = $v['create_date'];
            $content_arr[] = $arr;
        }
        $excel = new \Excel();
        $excel->writer($title_arr,$content_arr);
    }


    //会员列表
    public function user(){

        vendor('Excel.Excel');
        $title_arr = array('id','姓名','求职状态','性别','手机号','身份证号','注册时间');
        $where = array();
        $where['delete_status'] = '1';
        I('keywords') && $where['relname'] = array('like','%'.I('keywords').'%');
        (I('phone') || I('phone') == '0') && $where['phone'] = array('like','%'.I('phone').'%');
        I('card_number') && $where['card_number'] = array('like','%'.I('card_number').'%');
        I('position_type') && $where['position_type'] = I('position_type');
        $data = D('Users')->where($where)->select();
        $content_arr = array();
        foreach($data as $k=>$v){
            $sex = '未知';
            if($v['sex'] =='1'){
                $sex = '男';
            }elseif($v['sex'] =='2'){
                $sex = '女';
            }
            $arr = array();
            $arr[] = $v['id'];
            $arr[] = $v['relname'];
            $arr[] = $v['position_type'] =='1' ? '在职' : '待职';
            $arr[] = $sex;
            $arr[] = $v['phone'];
            $arr[] = $v['card_number'];
            $arr[] = $v['create_time'];
            $content_arr[] = $arr;
        }
        $excel = new \Excel();
        $excel->writer($title_arr,$content_arr);
    }
    //入职导出
    public function entry(){
        vendor('Excel.Excel');
        $orderModel = D('Enroll');
        $title_arr = array('ID','面试日期','报道时间','企业名称','姓名','工号','身份证号','手机号','性别','返现金额','返现周期（天）','审核状态');
        $where = array();
        I('keywords') && $where['keywords'] = array('like','%'.I('keywords').'%');
        I('name') && $where['name'] = array('like','%'.I('name').'%');
        I('card_number') && $where['card_number'] = array('like','%'.I('card_number').'%');
        if(I('begin_date') && !I('end_date')){
            $where['create_down'] = array('EGT',date('Y-m-d',strtotime(I('begin_date'))));
        }elseif(!I('begin_date') && I('end_date')){
            $where['create_down'] = array('LT',date('Y-m-d',strtotime('+1 day',strtotime(I('end_date')))));
        }elseif(I('begin_date') &&I('end_date')){
            $where['create_down'] = array('between',array(date('Y-m-d',strtotime(I('begin_date'))),date('Y-m-d',strtotime('+1 day',strtotime(I('get.end_date'))))));
        }
        $datapage = D('Entry')->where($where)->select();
         $content_arr = array();
        foreach($datapage as $k=>$v){
            $status = '待审核';
            switch ($v['audit_status']) {
                case '2':$status = '财务已审核通过';break;
                case '3':$status = '财务未审核通过';break;
                case '4':$status = '总经理审核已通过';break;
                case '5':$status = '总经理审核未通过';break;
                case '6':$status = '待打款';break;
                case '7':$status = '已打款';break;
            }
            $arr = array();
            $arr[] = $v['id'];
            $arr[] = $v['interview_date'];
            $arr[] = $v['create_down'];
            $arr[] = $v['company_name'];
            $arr[] = $v['name'];
            $arr[] = $v['job_number'];
            $arr[] = $v['card_number'];
            $arr[] = $v['phone'];
            $arr[] = $v['sex'];
            $arr[] = $v['subsidy_money'];
            $arr[] = $v['freez_time'];
            $arr[] = $status;
            $content_arr[] = $arr;
        }
        $excel = new \Excel();
        $excel->writer($title_arr,$content_arr);
        dump($datapage); die();
        
    }

    //会员和分销导出
    public function record(){
        vendor('Excel.Excel');
        $orderModel = D('Enroll');
        $title_arr = array('姓名','推荐人类型','推荐类型','被推荐人姓名','被推荐人手机号','获得金额','推荐状态','推荐时间');
        $where = array();
        I('invitee_name') && $where['invitee_name'] = array('like','%'.I('invitee_name').'%');
        I('invitee_phone') && $where['invitee_phone'] = array('like','%'.I('invitee_phone').'%');
        I('inviter_type') && $where['inviter_type'] = I('inviter_type');
        $datapage = D('Recommend')->where($where)->relation(true)->select();
        $content_arr = array();
        foreach($datapage as $k=>$v){
            $arr = array();
            $arr[] = $v['relname'];
            $arr[] = $v['phone'];
            $arr[] = $v['inviter_type']=='1' ? '区域代理' : '普通会员';
            $arr[] = $v['invite_type']=='1' ? '直接推荐' : '非直推推荐';
            $arr[] = $v['invitee_name'];
            $arr[] = $v['invitee_phone'];
            $arr[] = $v['get_money'];
            $arr[] = $v['register_date']? '已注册' :'未注册';
            $arr[] = $v['register_date']?substr($vo['register_date'],0,10):substr($vo['invite_date'],0,10);
            $content_arr[] = $arr;
        }
        $excel = new \Excel();
        $excel->writer($title_arr,$content_arr);
    }

    //抽奖导出
    public function lottery(){
        vendor('Excel.Excel');
        $orderModel = D('Enroll');
        $title_arr = array('中奖人姓名','联系电话','奖品名称','发放状态','中奖时间');
        $where = array();
        I('keywords') && $where['name'] = array('like','%'.I('keywords').'%');
        (I('phone') || I('phone') =='0') && $where['phone'] = array('like','%'.I('phone').'%');
        I('status') && $where['status'] = I('status');
        $datapage = D('LotteryList')->where($where)->select();
        $content_arr = array();
        foreach($datapage as $k=>$v){
            $arr = array();
            $arr[] = $v['name'];
            $arr[] = $v['phone'];
            $arr[] = $v['lottery_name'];
            $arr[] = $v['status']=='1' ? '未发放' : '已发放';
            $arr[] = $v['create_time'];
            $content_arr[] = $arr;
        }
        $excel = new \Excel();
        $excel->writer($title_arr,$content_arr);
    }
	//区域代理推荐记录
    public function agent_rec_log(){
    	I('get.inviter_id') || $this->error('非法的操作！');
    	$inviter_id = (int)I('get.inviter_id');
    	$inviter_info = D('Users')->where(array('id'=>$inviter_id))->find();
    	$inviter_info || $this->error('非法的操作！');
    	$where = array();
    	$where['inviter_id'] = $inviter_id;
    	if(I('keywords')){
    		$uw = array();
    		$uw['relname|phone|card_number'] = array('like','%'.I('keywords').'%');
    		$uid = D('Users')->where($uw)->getField('id',true);
    		if($uid){
    			$where['invitee_id'] = array('IN',implode(',',$uid));
    		}else{
    			$where['1'] = array('NEQ','1');
    		}
    	}
    	$date_w = array();
    	$date_w['inviter_id'] = $inviter_id;
    	if(I('get.start_date') && !I('get.end_date')){
    		$date_w['get_date'] = array('EGT',I('get.start_date'));
    	}elseif(!I('get.start_date') && I('get.end_date')){
    		$date_w['get_date'] = array('LT',I('get.end_date'));
    	}elseif(I('get.start_date') && I('get.end_date')){
    		$date_w['get_date'] = array(array('EGT',I('get.start_date').' 00:00:00'),array('LT',date('Y-m-d',strtotime(I('get.end_date'))).' 23:59:59'),'and');
    	}
    	$list = D('Recommend')->relation(true)->where($where)->order('id asc')->select();
    	$title_arr = array('姓名','性别','手机号','身份证号','推荐方式','数据统计','注册时间');
    	$content_arr = array();
    	foreach ($list as $key=>$val){
    		$row = array();
    		$row[] = $val['invitee']['relname'] ? $val['invitee']['relname'] : '--';
    		$row[] = $val['invitee']['sex'] == '1'?'男':($val['invitee']['sex'] == '2'?'女':'保密');
    		$row[] = $val['invitee']['phone'] ? $val['invitee']['phone'] :'--';
    		$row[] = $val['invitee']['card_number'] ? $val['invitee']['card_number'] : '--';
    		if($val['invite_type'] == '2') $inviter_name = D('Users')->where(array('my_code'=>$val['invitee']['invite_code']))->getField('relname');
    		$row[] = $val['invite_type'] == 1?'直接推荐':'间接推荐（'.$inviter_name.'）';
    		$row[] = D('RecommendLog')->where(array_merge(array('invitee_id'=>$val['invitee_id']),$date_w))->count();
    		$row[] = date('Y-m-d',$val['invitee']['reg_time']);
    		$content_arr[] = $row;
    	}
    	vendor('Excel.Excel');
    	$excel = new \Excel();
    	$excel->writer($title_arr,$content_arr);
    }
    //分销列表
    public function rec_record(){
    	$where = array();
    	$where['type'] = '2';
    	if(I('keywords')){
    		$where['relname|phone|card_number'] = array('like','%'.I('keywords').'%');
    	}
    	$date_w = array();
    	if(I('get.start_date') && !I('get.end_date')){
    		$date_w['get_date'] = array('EGT',I('get.start_date'));
    	}elseif(!I('get.start_date') && I('get.end_date')){
    		$date_w['get_date'] = array('LT',I('get.end_date'));
    	}elseif(I('get.start_date') && I('get.end_date')){
    		$date_w['get_date'] = array(array('EGT',I('get.start_date').' 00:00:00'),array('LT',date('Y-m-d',strtotime(I('get.end_date'))).' 23:59:59'),'and');
    	}
    	$list = D('Users')->relation(true)->where($where)->order('reg_time desc')->select();
    	$title_arr = array('姓名','性别','手机号','身份证号','推荐人数','获得金额（元）','注册时间');
    	$content_arr = array();
    	foreach ($list as $key=>$val){
    		$row = array();
    		$count_w = array();
    		$count_w['inviter_id'] = $val['id'];
    		if(I('get.start_date') && !I('get.end_date')){
    			$count_w['invite_date'] = array('EGT',I('get.start_date'));
    		}elseif(!I('get.start_date') && I('get.end_date')){
    			$count_w['invite_date'] = array('LT',I('get.end_date'));
    		}elseif(I('get.start_date') && I('get.end_date')){
    			$count_w['invite_date'] = array(array('EGT',I('get.start_date').' 00:00:00'),array('LT',date('Y-m-d',strtotime(I('get.end_date'))).' 23:59:59'),'and');
    		}
    		$row[] = $val['relname']?$val['relname']:'--';
    		$row[] = $val['sex'] == '1'?'男':($val['sex'] == '2'?'女':'保密');
    		$row[] = $val['phone']?$val['phone']:'--';
    		$row[] = $val['card_number']?$val['card_number']:'--';
    		$all_count = D('Recommend')->where($count_w)->count();
    		$rec_count = D('Recommend')->where(array_merge($count_w,array('invite_type'=>1)))->count();
    		$child_rec_count = D('Recommend')->where(array_merge($count_w,array('invite_type'=>2)))->count();
    		$row[] = "全部推荐人数:".$all_count."\n直接推荐人数:".$rec_count."\n间接推荐人数:".$child_rec_count;
    		
    		$money_w = array();
    		$money_w['inviter_id'] = $val['id'];
    		$date_w && $money_w = array_merge($money_w,$date_w);
    		$rec_log = D('RecommendLog')->field('sum(get_money) as rec_money')->where($money_w)->find();
    		$row[] = $rec_log['rec_money'];
    		$row[] = date('Y-m-d',$val['reg_time']);
    		$content_arr[] = $row;
    	}
    	vendor('Excel.Excel');
    	$excel = new \Excel();
    	$excel->writer($title_arr,$content_arr);
    }
    //分销明细
    public function rec_detail(){
    	I('get.inviter_id') || $this->error('非法的操作！');
		$inviter_id = (int)I('get.inviter_id');
		$inviter_info = D('Users')->where(array('id'=>$inviter_id))->find();
        $inviter_info || $this->error('非法的操作！');
		$where = array();
        $where['inviter_id'] = $inviter_id;
		if(I('keywords')){
            $uw = array();
            $uw['relname|phone|card_number'] = array('like','%'.I('keywords').'%');
            $uid = D('Users')->where($uw)->getField('id',true);
            if($uid){
                $where['invitee_id'] = array('IN',implode(',',$uid));
            }else{
                $where['1'] = array('NEQ','1');
            }
        }
        $date_w = array();
        $date_w['inviter_id'] = $inviter_id;
        if(I('get.start_date') && !I('get.end_date')){
            $date_w['get_date'] = array('EGT',I('get.start_date'));
        }elseif(!I('get.start_date') && I('get.end_date')){
            $date_w['get_date'] = array('LT',I('get.end_date'));
        }elseif(I('get.start_date') && I('get.end_date')){
            $date_w['get_date'] = array(array('EGT',I('get.start_date').' 00:00:00'),array('LT',date('Y-m-d',strtotime(I('get.end_date'))).' 23:59:59'),'and');
        }
        $title_arr = array('姓名','性别','手机号','身份证号','推荐方式','贡献金额（元）','注册时间');
    	$content_arr = array();
		$list = D('Recommend')->relation(true)->where($where)->order('id asc')->select();
		foreach ($list as $key=>$val){
			$row = array();
			$row[] = $val['invitee']['relname']?$val['invitee']['relname']:'--';
			$row[] = $val['invitee']['sex'] == '1'?'男':($val['invitee']['sex'] == '2'?'女':'保密');
			$row[] = $val['invitee']['phone']?$val['invitee']['phone']:'--';
			$row[] = $val['invitee']['card_number']?$val['invitee']['card_number']:'--';
            if($val['invite_type'] == '2') $inviter_name = D('Users')->where(array('my_code'=>$val['invitee']['invite_code']))->getField('relname');
            $row[] = $val['invite_type'] == 1?'直接推荐':'间接推荐（'.$inviter_name.'）';
            $money_w = array();
            $money_w['invitee_id'] = $val['invitee_id'];
            $date_w && $money_w = array_merge($money_w,$date_w);
            $result = D('RecommendLog')->field('sum(get_money) as rec_money')->where($money_w)->find();
            $row[] = $result['rec_money']?$result['rec_money']:'0';
            $row[] = date('Y-m-d',$val['invitee']['reg_time']);
            $content_arr[] = $row;
		}
    	vendor('Excel.Excel');
    	$excel = new \Excel();
    	$excel->writer($title_arr,$content_arr);
    }
    //用户分销记录
    public function user_rec_record(){
    	$where = array();
    	$where['inviter_type'] = 2;
		if(I('keywords')){
            $uw = array();
            $uw['relname|phone|card_number'] = array('like','%'.I('keywords').'%');
            $uid = D('Users')->where($uw)->getField('id',true);
            if($uid){
                $where['inviter_id'] = array('IN',implode(',',$uid));
            }else{
                $where['1'] = array('NEQ','1');
            }
        }
        $title_arr = array('姓名','性别','手机号','身份证号','推荐方式','推荐类型','被推荐人姓名','获得金额','发放日期','注册时间');
        $content_arr = array();
    	$list = D('RecommendLog')->relation(true)->where($where)->order('get_date desc')->select();
    	foreach ($list as $key=>$val){
    		$row = array();
    		$row[] = $val['relname']?$val['relname']:'--';
    		$row[] = $val['sex'] == '1'?'男':($val['sex'] == '2'?'女':'保密');
    		$row[] = $val['phone']?$val['phone']:'--';
    		$row[] = $val['card_number']?$val['card_number']:'--';
    		$row[] = $val['invite_type'] == 1?'直接推荐':'间接推荐';
    		$row[] = $val['source_type'] == 1?'扫码推荐':'朋友推荐';
    		$row[] = $val['invitee']['relname']?$val['invitee']['relname']:'--';
    		$row[] = $val['get_money']?$val['get_money']:'0';
    		$row[] = substr($val['get_date'],0,10);
    		$row[] = date('Y-m-d',$val['reg_time']);
    		$content_arr[] = $row;
    	}
    	vendor('Excel.Excel');
    	$excel = new \Excel();
    	$excel->writer($title_arr,$content_arr);
    }
}
