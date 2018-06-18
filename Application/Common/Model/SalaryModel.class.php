<?php
namespace Common\Model;
//周薪星实体模型
use Think\Model;
class SalaryModel extends Model
{
    protected $trueTableName = "zxx_month_salary";
    
    public function getmonthsalary($date,$user)
    {
        $condition['userid'] = $user['id'];
        $condition["check_date"] = $date?:date("Y-m",strtotime("last month"));
        $list = $this->field("allincome,has_borrow,fact_pay,check_date,mark,range_date")->where($condition)->select();
        if(!$list){
            return null;
        }
        foreach ($list as $key=>$value){
            $temp = explode("至", $value['range_date']);
            $list[$key]['week'] = $this->getweekfrommonth($temp);
        }
       
        if(!$list[0]['week']){
            return null;
        }
       
        //员工在职状态
        $workstatus = M()->table("rt_entry")->field("rt_entry.type,rt_company.name")->join("rt_company on rt_entry.company_id=rt_company.id")->where("rt_entry.user_id=".$user['id'])->find();
        $list[0]['company'] = $workstatus['name']; 
        $list[0]['workstatus'] = $workstatus['type']==1?'在职':'离职';
        
        $map['userid'] = ['eq',$user['id']];
        foreach ($list[0]['week'] as $value){
            $map['week_date'] = ['eq',$value];
            $list[0]['week_salary'][] = M("")->table("zxx_week_salary")->field("week_date,week_salary,fact_pay,mark")->where($map)->find();
        }
        
        return $list;
        
    }
    
    //从指定月份获取周期
    private function getweekfrommonth($month_range)
    {
        $week = [1,8,15,22,29];
        $calendar = [];
        foreach ($month_range as $vdate){
            $year = date("Y",strtotime($vdate));
            $month = date("m",strtotime($vdate));
            foreach ($week as $value){
                if(date("Y-m-d",strtotime("5 day this week ".$year."-$month"."-".$value))>=$month_range[0]&&date("Y-m-d",strtotime("5 day this week ".$year."-$month"."-".$value))<=$month_range[1]){
                    $calendar[date("Y-m-d",strtotime("-1 day this week ".$year."-$month"."-".$value))."至".date("Y-m-d",strtotime("5 day this week ".$year."-$month"."-".$value))] = date("Y-m-d",strtotime("-1 day this week ".$year."-$month"."-".$value))."至".date("Y-m-d",strtotime("5 day this week ".$year."-$month"."-".$value));
                }
            }
        }
        //file_put_contents("salarydebug.log", json_encode($calendar));
        $new_calendar = [];
        foreach ($calendar as $k=>$v){
            $new_calendar[] = $v;
        }
        return $new_calendar;
    }
    
    //获取账单明细列表
    public function getsalarylist($user)
    {
        $condition['userid'] = $user['id'];
        
        $monthlist = M()->table("zxx_month_salary")->field("id,check_date,fact_pay,pay_date")->where($condition)->order("check_date desc")->select();
        $weeklist = M()->table("zxx_week_salary")->field("id,week_date,pay_date,fact_pay,date")->where($condition)->order("date desc")->select();
        $company = M()->table("rt_entry")->field("rt_company.name")->join("rt_company on rt_entry.company_id=rt_company.id")->where("rt_entry.user_id=".$user['id'])->find()['name'];
        
        $weekbill = [];
        $bill = [];
        foreach ($weeklist as $key=>$value){
            $weekbill['name'] = $company.date("Y年m月",$value['date'])."周薪";
            $weekbill['money'] = $value['fact_pay'];
            $weekbill['range'] = $value['week_date'];
            //到账时间
            if(($value['pay_date']+24*60*60)<=time()){
                $weekbill['status'] = "已到账";
            }else{
                $weekbill['status'] = "预计到账时间：".date("Y-m-d",$value['pay_date']+24*60*60);
            }
            $weekbill['id'] = $value['id'];
            $weekbill['type'] = 'weeksalary';
            $bill[] = $weekbill;
        }
        $monthbill = [];
        foreach ($monthlist as $key=>$value){
            $temp_date = explode("-", $value['check_date']);
            $monthbill['name'] = $company.$temp_date[0]."年".$temp_date[1]."月结余工资";
            $monthbill['money'] = $value['fact_pay'];
            //到账时间
            if(($value['pay_date']+24*60*60)<=time()){
                $monthbill['status'] = "已到账";
            }else{
                $monthbill['status'] = "预计到账时间：".date("Y-m-d",$value['pay_date']+24*60*60);
            }
            $monthbill['id'] = $value['id'];
            $monthbill['type'] = 'monthsalary';
            $bill[] = $monthbill;
        }
        return $bill?:null;
    }
    
    public function salarydetails($user,$data)
    {

        $condition['_string'] = "zxx_week_salary.id='{$data['id']}'";
        $type = $data['type'];
        if($type=="weeksalary"){
            
            $list = M()->table("zxx_week_salary")
                       ->field([
                           "zxx_week_salary.fact_pay",
                           "zxx_week_salary.week_date",
                           "zxx_week_salary.pay_date",
                           "rt_users.nickname",
                           "zxx_workcard.cardsn",
                           "rt_company.name"=>'companyname',
                           "rt_card.card_number"=>'bankcard'
                       ])
                       ->join("rt_users on zxx_week_salary.userid=rt_users.id")
                       ->join("zxx_workcard on zxx_week_salary.userid=zxx_workcard.userid")
                       ->join("rt_company on zxx_workcard.companyid=rt_company.id")
                       ->join("rt_card on zxx_week_salary.userid=rt_card.user_id")
                       ->where($condition)
                       ->find();
            if($list['pay_date']+24*60*60<=time()){
                $list['status'] = "已到账";
            }else{
                $list['status'] = date("Y-m-d",$list['pay_date']+24*60*60)." 24点前";
            }
            //打款时间　仅显示日期
            $list['pay_date'] = date("Y-m-d",$list['pay_date']);
            
        }if($type=="monthsalary"){
            $map['_string'] = "zxx_month_salary.id='{$data['id']}'";
            $list = M()->table("zxx_month_salary")
                       ->field([
                           "zxx_month_salary.fact_pay",
                           "zxx_month_salary.range_date",
                           "zxx_month_salary.pay_date",
                           "rt_users.nickname",
                           "zxx_workcard.cardsn",
                           "rt_company.name"=>'companyname',
                           "rt_card.card_number"=>'bankcard'
                       ])
                       ->join("rt_users on zxx_month_salary.userid=rt_users.id")
                       ->join("zxx_workcard on zxx_month_salary.userid=zxx_workcard.userid")
                       ->join("rt_company on zxx_workcard.companyid=rt_company.id")
                       ->join("rt_card on zxx_month_salary.userid=rt_card.user_id")
                       ->where($map)
                       ->find();
            if($list['pay_date']+24*60*60<=time()){
                $list['status'] = "已到账";
            }else{
                $list['status'] = date("Y-m-d",$list['pay_date']+24*60*60)." 24点前";
            }
            //打款日期
            $list['pay_date'] = date("Y-m-d",$list['pay_date']);
        }
        return $list?:null;
    }
    
    //周薪星本周上周预收入计算　
    public function getcurrentweek($user,$type)
    {
        //本年本月本周
        if($type=="current"){
            $current = date("Y-m-W",strtotime("this week"));
        }elseif($type=="last"){
            $current = date("Y-m-W",strtotime("last week"));
        }
        
        $map['_string'] = "date_format(from_unixtime(time),'%Y-%m-%u')='$current'";
        $map['userid'] = $user['id'];
        $borrow = M()->table("zxx_borrowbranch")->field("salary_perh,salary_perd,salary_condition")->where(['userid'=>$user['id']])->find();
        $list = M()->table("zxx_cardrecord")->field("starttime,endtime,time")->where($map)->select(); 
        $preincome = 0;
        foreach ($list as $key=>$value){
            //下班时间减上班时间
            $hours = ($value['endtime']-$value['starttime'])/3600;
            if($hours>$borrow['salary_condition']&&$hours>0){
                //超出借支条件的时薪不在计算　
                //$today_extra_income = $borrow['salary_perd']+round(($hours-$borrow['salary_condition'])*$borrow['salary_perh'],2);
                $today_extra_income = $borrow['salary_perd'];
                
            }elseif($hours==$borrow['salary_condition']&&$hours>0){
                //符合借支条件
                $today_extra_income = $borrow['salary_perd'];
                
            }elseif($hours<$borrow['salary_condition']&&$hours>0){
                //$today_extra_income = round($hours*$borrow['salary_perh'],2);
                $today_extra_income = 0;
            }elseif($hours<0){
                //小于0的情况是上下班只打一次的情况将不算工时
                $today_extra_income = 0;
            }else{
                $today_extra_income = 0;
            }
            $preincome+=$today_extra_income;
            
        }
        return $preincome?:null;
    }
    
    public function getuserinfo($user)
    {
        $userid  = $user['id'];
        $condition['_string'] = "rt_users.id='$userid'";
        
        $info = M()->table("rt_users")
                   ->field([
                       'rt_users.nickname',
                       'rt_users.relname',
                       'rt_company.name'=>'companyname',
                       'rt_position.title'
                   ])
                   ->join("rt_entry on rt_users.id=rt_entry.user_id")
                   ->join("rt_company on rt_entry.company_id=rt_company.id")
                   ->join("rt_position on rt_entry.company_id=rt_position.company_id")
                   ->where($condition)
                   ->find();
        return $info?:null;
        
    }
}
?>