<?php
namespace Common\Model;
use Think\Model;
//周薪星薪资说明
class WuserModel extends Model{

    protected $trueTableName    =   'rt_users';
    
	public function getUserinfo($user)
	{
	    $condition['_string'] = "rt_entry.user_id='{$user['id']}'";
	    
	    $list = M("Entry")
	                       ->field([
	                           'rt_entry.create_date',
	                           'rt_users.nickname',
	                           'rt_users.phone',
	                           'rt_users.headimgurl',
	                           'rt_company.name'=>'companyname',
	                           'rt_position.title'=>'workattribute',
	                           'zxx_borrowbranch.salary_perh',
	                           'zxx_borrowbranch.salary_perd',
	                           'zxx_borrowbranch.salaryinfo',
	                       ])
	                       ->join("left join rt_users on rt_entry.user_id=rt_users.id")
	                       ->join("left join rt_company on rt_entry.company_id=rt_company.id")
	                       ->join("left join rt_position on rt_entry.position_id=rt_position.id")
	                       ->join("left join zxx_borrowbranch on rt_entry.user_id=zxx_borrowbranch.userid")
	                       ->where($condition)
	                       ->find();
	    
	    $list['create_date'] =date("Y-m-d",strtotime($list['create_date']));
	    return $list;
	}
	
	//获取当前会员的银行卡和工牌信息
	public function getusercardstatus($user)
	{
	    $condition['_string'] = "rt_users.id='{$user['id']}'";
	    
	    $list = M()->table("rt_users")->field([
	                   'rt_users.nickname',
	                   'rt_users.relname',
	                   'rt_card.card_number'=>'bankcard',
	                   'zxx_workcard.cardsn'=>'workcard'
	               ])
	               ->join("left join rt_card on rt_users.id=rt_card.user_id")
	               ->join("left join zxx_workcard on rt_users.id=zxx_workcard.userid")
	               ->where($condition)
	               ->find();
	    
	    $status = [];
	    //判断是否绑定了银行卡
	    if(!empty($list['bankcard'])){
	        $status['bankcard']['isbind'] = "1";
	        $status['bankcard']['info'] = "已绑定";
	    }else{
	        $status['bankcard']['isbind'] = "2";
	        $status['bankcard']['info'] = "未绑定";
	        //https://recruit.mppstore.com/Home/Users/wallet_card.html
	        
	        if(get_client()=="ios"||get_client()=="android"){
	            $status['bankcard']['bind_bank_url'] = U("H5/Users/wallet_card");
	        }elseif(get_client()=="wechat"){
	            $status['bankcard']['bind_bank_url'] = U("Home/Users/wallet_card");
	        }
	        
	    }
	    
	    //判断是否绑定了工牌
	    if(!empty($list['workcard'])){
	        $status['workcard']['isbind'] = "1";
	        $status['workcard']['info'] = "已绑定";
	    }else{
	        $status['workcard']['isbind'] = "2";
	        $status['workcard']['info'] = "未绑定";
	        
	        if(get_client()=="ios"||get_client()=="android"){
	            $status['workcard']['bind_workcard_url'] = U("H5/Workcard/index");
	        }elseif(get_client()=="wechat"){
	            $status['workcard']['bind_workcard_url'] = U("Home/Workcard/index");
	        }
	        
	    }
	    
	    return $status;
	}
	
	public function getimportnotice()
	{
	    return M()->table("zxx_salaryinfo")->getField("info",true);
	}
	
	public function getmessagelist($user)
	{
	    $list = M()->table("zxx_message")->field("title,content,time")->select();
	    foreach ($list as $k=>$v){
	        $list[$k]['time'] = date("Y-m-d",$v['time']);
	    }
	    return $list;
	}
}
