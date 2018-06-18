<?php
namespace Common\Model;

use Think\Model;
class CardrecordModel extends Model
{
    protected $tablePrefix = "zxx_";
    
    
    protected $_validate = [
        ['startplace','require','-1'],
        ['endplace','require','-2'],
        ['idle','require','-3'],
        ['idle','integer','-4'],
    ];
 
    
    /**
     * 添加上班卡时间
     * @param array [userid,time,startplace,idle]
     * **/
    public function addworktime($startxy,$user)
    {
        
        $data['userid'] = $user['id'];
        
        //当前会员的id
        $condition['userid'] = $data['userid'];
        
        //当天的日期
        $currenttime = date("Y-m-d",time());
        
        $condition['_string'] = "date_format(from_unixtime(starttime),'%Y-%m-%d')='{$currenttime}'";
         
        $sql = "select starttime from zxx_cardrecord where date_format(from_unixtime(starttime),'%Y-%m-%d')='{$currenttime}' and userid='{$data['userid']}'";
        
        //查询是否打了休息卡
        //当前会员的id
        $condition1['userid'] = $data['userid'];
        
        //当天的日期
        $currenttime1 = date("Y-m-d",time());
        
        $condition1['_string'] = "date_format(from_unixtime(time),'%Y-%m-%d')='{$currenttime1}'";
        $isidle = $this->field("idle")->where($condition1)->find()['idle'];
        if(in_array($isidle, [3,4,5,6,7,8,9])){
            return '-6';
        }

        if(M()->query($sql)){
        
            return "0";
        }else{
        
            $data['starttime'] = time();//打卡时间
        
            $data['startplace'] = $startxy;//打卡位置
        
            $data['time'] = time();//打卡日期　　哪一天
            
            $data['idle'] = 0;
            
            if($this->create($data)){
                
                /****查询今天是否打了下班卡，防止打了下班卡又打上班卡　　***/
                //当前会员的id
                $condition2['userid'] = $data['userid'];
                
                //当天的日期
                $currenttime2 = date("Y-m-d",time());
                
                $condition2['_string'] = "date_format(from_unixtime(endtime),'%Y-%m-%d')='{$currenttime2}'";
                $isendtime = $this->field("endtime")->where($condition2)->find()['endtime'];
                
                //获取当前用户的借支条件　如８小时　　表示再次打上班卡必须超过８小时才可以再打上班卡
                $standard_hours = M()->table("zxx_borrowbranch")->field("salary_condition")->where(['userid'=>$data['userid']])->find();
                
                //间隔时间
                $interval_hours = (time()-$isendtime)/3600;
                //当天已经了打下班卡，再次打上班卡时必须满足借支条件设置的值　　防止一天搞多班打卡
                if($interval_hours<$standard_hours['salary_condition']){

                    return '-11';
                }else{
                    /****查询今天是否打了下班卡，防止打了下班卡又打上班卡　　***/

                    return $this->add($data);
                }
                
            }else{
                return $this->getError();
            }
           
        }
        
    }
    
    /**
     * 添加上班卡时间  debug 调试测试方法
     * @param array [userid,time,startplace,idle]
     * **/
    public function addworktimedebug($startxy,$user,$time)
    {
        $data['userid'] = $user['id'];
        
        //当前会员的id
        $condition['userid'] = $data['userid'];
        
        //当天的日期
        $currenttime = date("Y-m-d",strtotime($time));
        
        $condition['_string'] = "date_format(from_unixtime(starttime),'%Y-%m-%d')='{$currenttime}'";
         
        $sql = "select starttime from zxx_cardrecord where date_format(from_unixtime(starttime),'%Y-%m-%d')='{$currenttime}' and userid='{$data['userid']}'";
        
        //查询是否打了休息卡
        //当前会员的id
        $condition1['userid'] = $data['userid'];
        
        //当天的日期
        $currenttime1 = date("Y-m-d",strtotime($time));
        
        $condition1['_string'] = "date_format(from_unixtime(time),'%Y-%m-%d')='{$currenttime1}'";
         
        $isidle = $this->field("idle")->where($condition1)->find()['idle'];
        if(in_array($isidle, [3,4,5,6,7,8,9])){
            return '-6';
        }

        if(M()->query($sql)){
        
            return "0";
        }else{
        
            $data['starttime'] = strtotime($time);//打卡时间
        
            $data['startplace'] = $startxy;//打卡位置
        
            $data['time'] = strtotime($time);//打卡日期　　哪一天
            
            $data['idle'] = 0;
            
            if($this->create($data)){
                
                /****查询今天是否打了下班卡，防止打了下班卡又打上班卡　　***/
                //当前会员的id
                $condition2['userid'] = $data['userid'];
                
                //当天的日期
                $currenttime2 = date("Y-m-d",strtotime($time));
                
                $condition2['_string'] = "date_format(from_unixtime(endtime),'%Y-%m-%d')='{$currenttime2}'";
                $isendtime = $this->field("endtime")->where($condition2)->find()['endtime'];
                
                //获取当前用户的借支条件　如８小时　　表示再次打上班卡必须超过８小时才可以再打上班卡
                $standard_hours = M()->table("zxx_borrowbranch")->field("salary_condition")->where(['userid'=>$data['userid']])->find();
                
                //间隔时间
                $interval_hours = (strtotime($time)-$isendtime)/3600;
                //当天已经了打下班卡，再次打上班卡时必须满足借支条件设置的值　　防止一天搞多班打卡
                if($interval_hours<$standard_hours['salary_condition']){

                    return '-11';
                }else{

                    return $this->add($data);
                }
                /****查询今天是否打了下班卡，防止打了下班卡又打上班卡　　***/
                
            }else{
                return $this->getError();
            }
           
        }
        
    }
    
    /**
     * 下班打卡
     * **/
    public function addendtime($endxy,$user)
    {

        $data['userid'] = $user['id'];//员工id  为当前登录会员的id
        
        
        $data['endtime'] = time();//打卡时间
        
        $data['endplace'] = $endxy;//打卡位置
        
        //当前会员的id
        $condition['userid'] = $data['userid'];
        
        //当天的日期
        $currenttime = date("Y-m-d",time());
        
        $condition['_string'] = "date_format(from_unixtime(starttime),'%Y-%m-%d')='$currenttime'";
        
        //查询是否打了休息卡
        //当前会员的id
        $condition1['userid'] = $data['userid'];
        
        //当天的日期
        $currenttime1 = date("Y-m-d",time());
        
        $condition1['_string'] = "date_format(from_unixtime(time),'%Y-%m-%d')='$currenttime1'";
        $isidle = $this->field("idle")->where($condition1)->find()['idle'];
        if(in_array($isidle, [3,4,5,6,7,8,9])){
            return '-6';
        }
        //判断打卡时间
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        $hour = "13:00:00";//下午
        //当天下午打卡   只算[白班]
        if(date("Y-m-d H:i",$data['endtime'])>date("Y-m-d H:i",strtotime($year.'-'.$month.'-'.$day.' '.$hour))){
            
            //查找当天是否打了上班卡
            if($this->where($condition)->find()){
                //查找到当天的上班记录则显示为正
                $data['idle'] = 2;
                //将下班卡时间与上班卡时间对应
                if($this->create($data)){
                    return $this->where($condition)->save($data)?1:0;
                }else{
                    return $this->getError();
                }
                
            }else{
                //当天没有打上班卡 [忘记打卡的情况、手机没电、没网络、手机不见、app没有装的情况]
                $data['idle'] = 1;//只打了下班卡的白班  状态为  下
                $data['time'] = time();//打卡时间  白班没有打上班卡的时候要添加时间
                /************查找当天是否打了下班卡*******************/
                //当前会员的id
                $map['userid'] = $data['userid'];
                
                //当天的日期
                $currenttime = date("Y-m-d",time());
                //下班卡时间[白班]
                $map['_string'] = "date_format(from_unixtime(endtime),'%Y-%m-%d')='$currenttime'";
                
                if($this->where($map)->find()){
                    
                    //查找到当天打了下班卡[白班]重复打下班卡更新
                    if($this->create($data)){
                        return $this->where($map)->save($data)?2:0;
                    }else{
                        return $this->getError();
                    }
                    
                }else{
                    
                    //当天没有打下班卡则添加为今天的[白班]
                    if($this->create($data)){
                        return $this->add($data)?10:0;
                    }else{
                        return $this->getError();
                    }
                                 
                }
                /************查找当天是否打了下班卡*******************/
                
                
               
            }
            
        //当天下午之前打的下班卡 只算[夜班]    
        }elseif(date("Y-m-d H:i",$data['endtime'])<date("Y-m-d H:i",strtotime($year.'-'.$month.'-'.$day.' '.$hour))){
            
            //查找昨天打的上班卡
            //当前会员的id
            $condition_yesterday['userid'] = $data['userid'];
            
            //昨天的日期
            $currenttime_yesterday = date("Y-m-d",strtotime("yesterday"));
            //昨天的上班卡打卡时间
            $condition_yesterday['_string'] = "date_format(from_unixtime(starttime),'%Y-%m-%d')='$currenttime_yesterday'";
            
            if($this->where($condition_yesterday)->find()){
               
                //查找到昨天的上班卡
                $yesterday_starttime = $this->where($condition_yesterday)->find();
                
                //昨天打的上班卡不可以是上午的
                $yesterday_year = date("Y",strtotime("yesterday"));
                $yesterdaymonth = date("m",strtotime("yesterday"));
                $yesterdayday = date("d",strtotime("yesterday"));
                $hour_yesterday = "12:00:00";//昨天12点之前
                
                //昨天12点之前
                $yesterday_morning = $yesterday_year.'-'.$yesterdaymonth.'-'.$yesterdayday.' '.$hour_yesterday;
                
                //判断昨天的上班卡必须是下午或是晚上打的否则不算夜班
                if(date("Y-m-d H",$yesterday_starttime['starttime'])>date("Y-m-d H",strtotime($yesterday_morning))){
                    $data['idle'] = 2;
                    if($this->create($data)){
                        return $this->where($condition_yesterday)->save($data)?3:0;
                    }else{
                        return $this->getError();
                    }
                    
                }
            }else{
                
                //查找不到昨天的上班卡打卡时间[用户昨天没有打上班卡夜班]
                //时间写昨天的日期
                $data['idle'] = 1;//只打了下班卡
                $data['time'] = strtotime("yesterday");//昨天的打卡时间
                
                /***********查找夜班打的下班卡是否重复****************/
                
                //今天早上上午重复打下班卡均更为夜班并进行更新操作
                //当前会员的id
                $morning['userid'] = $data['userid'];
                
                //当天的日期
                $morning_currenttime = date("Y-m-d",time());
                //下班卡时间[夜班]
                $morning['_string'] = "date_format(from_unixtime(endtime),'%Y-%m-%d')='$morning_currenttime'";
                
                $today = $this->where($morning)->find();
                
                //判断当天打的下班卡是否是早上的，如果是早上的则说明是夜班打的下班卡
                $now_year = date("Y");
                $now_month = date("m");
                $now_day = date("d");
                $now_hours = "12:00:00";//今天12点之前
                
                //查到夜班的今天早上打下班卡记录
                if($this->where($morning)->find()){
                    
                    //今天的打卡时间小于中午12点之前的均列为已经打了夜班的下班卡则进行操作
                    if(date("Y-m-d H:i",$today['endtime'])<date("Y-m-d H:i",strtotime($now_year.'-'.$now_month.'-'.$now_day.' '.$now_hours))){
                        
                        if($this->create($data)){
                            
                            //在次检查今天早上是否打了白班的上班卡
                                    
                            //当前会员的id
                            $morning_again['userid'] = $data['userid'];
                            
                            //当天的日期
                            $morning_currenttime_again = date("Y-m-d",time());
                            //下班卡时间[夜班]
                            $morning_again['_string'] = "date_format(from_unixtime(starttime),'%Y-%m-%d')='$morning_currenttime_again'";
                            
                            if($this->where($morning_again)->find()){
                                //查找到更新状态为２
                                $data['idle'] = 2;  //白班的更新
                                $data['time'] = time();
                                return $this->where($morning_again)->save($data)?4:0;
                            }else{
                                //夜班的更新
                                return $this->where($morning)->save($data)?4:0;
                            }
                            
                        }else{
                            return $this->getError();
                        }
                        
                    }
                }else{
                    //查找不到夜班今天早上的下班卡记录则添加
                    if($this->create($data)){
                        
                        //在次检查今天早上是否打了白班的上班卡
               
                        //当前会员的id
                        $morning_again['userid'] = $data['userid'];
                        
                        //当天的日期
                        $morning_currenttime_again = date("Y-m-d",time());
                        //下班卡时间[夜班]
                        $morning_again['_string'] = "date_format(from_unixtime(starttime),'%Y-%m-%d')='$morning_currenttime_again'";
                        
                        if($this->where($morning_again)->find()){
                            $data['idle'] = 2;//在今天早上打了上班卡
                            $data['time'] = time();
                
                            //今天早上打了白班的上班卡
                            return $this->where($morning_again)->save($data)?7:0;
                        }else{
                            return $this->add($data)?5:0;
                        }
                        
                        
                        
                        
                    }else{
                        return $this->getError();
                    }
                    
                }
                
                /***********查找夜班打的下班卡是否重复****************/
            }
        }
    }
    
    
    /**
     * 下班打卡 debug
     * **/
    public function addendtimedebug($endxy,$user,$time)
    {
    
        $data['userid'] = $user['id'];//员工id  为当前登录会员的id
    
    
        $data['endtime'] = strtotime($time);//打卡时间
    
        $data['endplace'] = $endxy;//打卡位置
    
        //当前会员的id
        $condition['userid'] = $data['userid'];
    
        //当天的日期
        $currenttime = date("Y-m-d",strtotime($time));
    
        $condition['_string'] = "date_format(from_unixtime(starttime),'%Y-%m-%d')='$currenttime'";
    
        //查询是否打了休息卡
        //当前会员的id
        $condition1['userid'] = $data['userid'];
        
        //当天的日期
        $currenttime1 = date("Y-m-d",strtotime($time));
        
        $condition1['_string'] = "date_format(from_unixtime(time),'%Y-%m-%d')='$currenttime1'";
        $isidle = $this->field("idle")->where($condition1)->find()['idle'];
        if(in_array($isidle, [3,4,5,6,7,8,9])){
            return '-6';
        }
        //判断打卡时间
        $year = date("Y",strtotime($time));
        $month = date("m",strtotime($time));
        $day = date("d",strtotime($time));
        $hour = "13:00:00";//下午
        //当天下午打卡   只算[白班]
        if(date("Y-m-d H:i",$data['endtime'])>date("Y-m-d H:i",strtotime($year.'-'.$month.'-'.$day.' '.$hour))){
    
            //查找当天是否打了上班卡
            if($this->where($condition)->find()){
                //查找到当天的上班记录则显示为正
                $data['idle'] = 2;
                //将下班卡时间与上班卡时间对应
                if($this->create($data)){
                    return $this->where($condition)->save($data)?1:0;
                }else{
                    return $this->getError();
                }
    
            }else{
                //当天没有打上班卡 [忘记打卡的情况、手机没电、没网络、手机不见、app没有装的情况]
                $data['idle'] = 1;//只打了下班卡的白班  状态为  下
                $data['time'] = strtotime($time);//打卡时间  白班没有打上班卡的时候要添加时间
                /************查找当天是否打了下班卡*******************/
                //当前会员的id
                $map['userid'] = $data['userid'];
    
                //当天的日期
                $currenttime = date("Y-m-d",strtotime($time));
                //下班卡时间[白班]
                $map['_string'] = "date_format(from_unixtime(endtime),'%Y-%m-%d')='$currenttime'";
    
                if($this->where($map)->find()){
    
                    //查找到当天打了下班卡[白班]重复打下班卡更新
                    if($this->create($data)){
                        return $this->where($map)->save($data)?2:0;
                    }else{
                        return $this->getError();
                    }
    
                }else{
    
                    //当天没有打下班卡则添加为今天的[白班]
                    if($this->create($data)){
                        return $this->add($data)?10:0;
                    }else{
                        return $this->getError();
                    }
                     
                }
                /************查找当天是否打了下班卡*******************/
    
    
                 
            }
    
            //当天下午之前打的下班卡 只算[夜班]
        }elseif(date("Y-m-d H:i",$data['endtime'])<date("Y-m-d H:i",strtotime($year.'-'.$month.'-'.$day.' '.$hour))){
    
            //查找昨天打的上班卡
            //当前会员的id
            $condition_yesterday['userid'] = $data['userid'];
    
            //昨天的日期
            $currenttime_yesterday = date("Y-m-d",(strtotime($time)-24*60*60));
            //昨天的上班卡打卡时间
            $condition_yesterday['_string'] = "date_format(from_unixtime(starttime),'%Y-%m-%d')='$currenttime_yesterday'";
    
            if($this->where($condition_yesterday)->find()){
                 
                //查找到昨天的上班卡
                $yesterday_starttime = $this->where($condition_yesterday)->find();
    
                //昨天打的上班卡不可以是上午的
                $yesterday_year = date("Y",(strtotime($time)-24*60*60));
                $yesterdaymonth = date("m",(strtotime($time)-24*60*60));
                $yesterdayday = date("d",(strtotime($time)-24*60*60));
                $hour_yesterday = "12:00:00";//昨天12点之前
    
                //昨天12点之前
                $yesterday_morning = $yesterday_year.'-'.$yesterdaymonth.'-'.$yesterdayday.' '.$hour_yesterday;
    
                //判断昨天的上班卡必须是下午或是晚上打的否则不算夜班
                if(date("Y-m-d H",$yesterday_starttime['starttime'])>date("Y-m-d H",strtotime($yesterday_morning))){
                    $data['idle'] = 2;
                    if($this->create($data)){
                        return $this->where($condition_yesterday)->save($data)?3:0;
                    }else{
                        return $this->getError();
                    }
    
                }
            }else{
    
                //查找不到昨天的上班卡打卡时间[用户昨天没有打上班卡夜班]
                //时间写昨天的日期
                $data['idle'] = 1;//只打了下班卡
                $data['time'] = (strtotime($time)-24*60*60);//昨天的打卡时间
    
                /***********查找夜班打的下班卡是否重复****************/
    
                //今天早上上午重复打下班卡均更为夜班并进行更新操作
                //当前会员的id
                $morning['userid'] = $data['userid'];
    
                //当天的日期
                $morning_currenttime = date("Y-m-d",strtotime($time));
                //下班卡时间[夜班]
                $morning['_string'] = "date_format(from_unixtime(endtime),'%Y-%m-%d')='$morning_currenttime'";
    
                $today = $this->where($morning)->find();
    
                //判断当天打的下班卡是否是早上的，如果是早上的则说明是夜班打的下班卡
                $now_year = date("Y",strtotime($time));
                $now_month = date("m",strtotime($time));
                $now_day = date("d",strtotime($time));
                $now_hours = "12:00:00";//今天12点之前
    
                //查到夜班的今天早上打下班卡记录
                if($this->where($morning)->find()){
    
                    //今天的打卡时间小于中午12点之前的均列为已经打了夜班的下班卡则进行操作
                    if(date("Y-m-d H:i",$today['endtime'])<date("Y-m-d H:i",strtotime($now_year.'-'.$now_month.'-'.$now_day.' '.$now_hours))){
    
                        if($this->create($data)){
                        //在次检查今天早上是否打了白班的上班卡
                                    
                            //当前会员的id
                            $morning_again['userid'] = $data['userid'];
                            
                            //当天的日期
                            $morning_currenttime_again = date("Y-m-d",(strtotime($time)));
                            //下班卡时间[夜班]
                            $morning_again['_string'] = "date_format(from_unixtime(starttime),'%Y-%m-%d')='$morning_currenttime_again'";
                            
                            if($this->where($morning_again)->find()){
                                //查找到更新状态为２
                                $data['idle'] = 2;  //白班的更新
                                $data['time'] = (strtotime($time));
                                return $this->where($morning_again)->save($data)?4:0;
                            }else{
                                //夜班的更新
                                return $this->where($morning)->save($data)?4:0;
                            }
                        }else{
                            return $this->getError();
                        }
    
                    }
                }else{
                    //查找不到夜班今天早上的下班卡记录则添加
                    if($this->create($data)){
                    //在次检查今天早上是否打了白班的上班卡
               
                        //当前会员的id
                        $morning_again['userid'] = $data['userid'];
                        
                        //当天的日期
                        $morning_currenttime_again = date("Y-m-d",(strtotime($time)));
                        //下班卡时间[夜班]
                        $morning_again['_string'] = "date_format(from_unixtime(starttime),'%Y-%m-%d')='$morning_currenttime_again'";
                        
                        if($this->where($morning_again)->find()){
                            $data['idle'] = 2;//在今天早上打了上班卡
                            $data['time'] = (strtotime($time));
                            //今天早上打了白班的上班卡
                            return $this->where($morning_again)->save($data)?7:0;
                        }else{
                            return $this->add($data)?5:0;
                        }
                        
                    }else{
                        return $this->getError();
                    }
    
                }
    
                /***********查找夜班打的下班卡是否重复****************/
            }
        }
    }
    
    
    /**
     * 休息状态未做特殊说明，默认以今天的为准
     * **/
    public function idle($user)
    {
        $data['idle'] = I("post.idle");//休息状态id
        
        $data['mark'] = I("post.mark");//休息备注
        
        $data['userid'] = $user['id'];//当前登录会员的id
        
        $data['time'] = time();//休息时间
        
        //当前用户id
        $map['userid'] = $data['userid'];
        
        //当天的日期
        $currenttime = date("Y-m-d",time());
        
        //查询休息时间
        $map['_string'] = "date_format(from_unixtime(time),'%Y-%m-%d')='$currenttime' or date_format(from_unixtime(starttime),'%Y-%m-%d')='$currenttime' or date_format(from_unixtime(endtime),'%Y-%m-%d')='$currenttime'";
        
        
        //查询今天是否打了上班卡，或是下班卡，或是休息
        if($this->field("id")->where($map)->find()){
        
            /*
            if($this->create($data)){
                return $this->where($map)->save($data);
            }else{
                return $this->getError();
            }
            */
            return "-5";
            
        }else{
        
        //当前用户id
            $map1['userid'] = $data['userid'];
            
            //当天的日期
            $currenttime1 = date("Y-m-d",time());
            
            //查询休息时间
            $map1['_string'] = "date_format(from_unixtime(time),'%Y-%m-%d')='$currenttime1'";
            if($this->field("id")->where($map1)->find()){
                return "-8";
            }else{
                //今天没有打上下班卡，则添加休息
                if($this->create($data)){
                    return $this->add($data);
                }else{
                    return $this->getError();
                }
            }
        }
    }
    
    
    /**
     * 休息状态未做特殊说明，默认以今天的为准  debug
     * **/
    public function idledebug($user,$time)
    {
        $data['idle'] = I("post.idle");//休息状态id
    
        $data['mark'] = I("post.mark");//休息备注
    
        $data['userid'] = $user['id'];//当前登录会员的id
    
        $data['time'] = strtotime($time);//休息时间
    
        //当前用户id
        $map['userid'] = $data['userid'];
    
        //当天的日期
        $currenttime = date("Y-m-d",strtotime($time));
    
        //查询休息时间
        $map['_string'] = "date_format(from_unixtime(starttime),'%Y-%m-%d')='$currenttime' or date_format(from_unixtime(endtime),'%Y-%m-%d')='$currenttime'";
 
        //查询今天是否打了上班卡，或是下班卡，或是休息
        if($this->field("id")->where($map)->find()){
    
            /*
             if($this->create($data)){
             return $this->where($map)->save($data);
             }else{
             return $this->getError();
             }
             */
            return "-5";
    
        }else{
    
            //当前用户id
            $map1['userid'] = $data['userid'];
            
            //当天的日期
            $currenttime1 = date("Y-m-d",strtotime($time));
            
            //查询休息时间
            $map1['_string'] = "date_format(from_unixtime(time),'%Y-%m-%d')='$currenttime1'";
            if($this->field("id")->where($map1)->find()){
                return "-8";
            }else{
                //今天没有打上下班卡，则添加休息
                if($this->create($data)){
                    return $this->add($data);
                }else{
                    return $this->getError();
                }
            }
            
        }
    }
    
    
    /**
     * 获取打卡记录列表
     * **/
    public function getrecordlist($date='',$user)
    {
           $userid = $user['id'];
           $time = $date?date("Y-m",strtotime($date)):date("Y-m",time());
           $condition['_string'] = "date_format(from_unixtime(time),'%Y-%m')='$time'";
           $condition['userid'] = ['eq',$userid];
           $condition['idle'] = ['in',[0,1,2]];//上，下，正都列出
           $list = $this->field("starttime,endtime,startplace,endplace,time")->where($condition)->order(['time'=>'desc'])->select();
           $user = M("Users")->field("nickname")->where(['id'=>$userid])->find()['nickname'];
           $cardsn = M()->table("zxx_workcard")->where(['userid'=>$userid])->getField("cardsn");
           $data = [];
           foreach ($list as $k=>$v){
               $record = [];
               $record['day'] = date("Y-m-d",$v['time']);
               $record['week'] = C("WEEK")[date("w",$v['time'])];
               $record['starttime'] = $v['starttime']?date("Y-m-d H:i",$v['starttime']):'0';
               $record['endtime'] = $v['endtime']?date("Y-m-d H:i",$v['endtime']):'0';
               $record['startplace'] = $v['startplace'];
               $record['endplace'] = $v['endplace'];
               $record['user'] = $user;
               $record['cardsn'] = $cardsn;
               $data[] = $record;
           }
           return $data?:'null';
    }
    
    //周薪星考勤记录数据
    public function getattendancerecord($date,$user)
    {
        $cal = $this->createdatefromcalendar($date);
        $date = implode("','", $cal);
        $condition['userid'] = $user['id'];
        
        $condition['_string'] = "date_format(from_unixtime(time),'%Y-%m-%d') in ('$date')";
        
        //入职时间
        $user_entry_date = M("Entry")->field("create_date")->where(['user_id'=>$condition['userid']])->find()['create_date'];
      
        //打上班卡，下班卡，调休，请假[事假，病假]均有时间
        $list = $this->field("time,idle,starttime,endtime")->where($condition)->order(['time'=>'asc'])->select();
        //员工的借支标准
        $borrow = M()->table("zxx_borrowbranch")->field("salary_perh,salary_perd,salary_condition")->where(['userid'=>$user['id']])->find();
        
        $card_data = [];
        $salary_data = [];
        foreach ($list as $key=>$value){
            //$card_data[$key]['time'] = date("Y-m-d",$value['time']);
            //$card_data[$key]['idle'] = C('WORKIDLE')[$value['idle']]?:'休';
            
            //核算预收入
            $hours = ($value['endtime'] - $value['starttime'])/3600;
            if($hours >$borrow['salary_condition']&&$hours>0){
                
                //上班时间超过借支条件
                $today_money = $borrow['salary_perd'];
                $today_extra_money = $today_money;
                //超出借支标准的时薪不在计算
                //$today_extra_money = $today_money + round(($hours - $borrow['salary_condition'])*$borrow['salary_perh'],0);
                
            }elseif($hours == $borrow['salary_condition']&&$hours>0){
                //上班时间等于借支条件
                $today_extra_money = $borrow['salary_perd'];
                
            }elseif($hours < $borrow['salary_condition']&&$hours>0){
                //上班时间小于借支条件
                //$today_extra_money = round($hours*$borrow['salary_perh'],0);
                $today_extra_money = '';
            }elseif($hours<0){
                $today_extra_money = '';
            }else{
                $today_extra_money = '';
            }
            
            $salary_data[date("Y-m-d",$value['time'])] = $today_extra_money;
            /****
            '3'=>'周末',
            '4'=>'国假',
            '5'=>'事假',
            '6'=>'病假',
            '7'=>'调休',
            9其它　均列为休状态
            ****/
            $statusName = ['周末','国假','事假','病假','调休','其它'];
            
            $temp_status = C('WORKIDLE')[$value['idle']];
            if(in_array($temp_status, $statusName)){
                $card_data[date("Y-m-d",$value['time'])] = '休';
            }else{
                $card_data[date("Y-m-d",$value['time'])] = $temp_status;
            }
            
        }

        $calendar_data = [];
        foreach ($cal as $key=>$value){
            $day_status = '';
            $temp = $card_data[$value];
            
            $temp_salary = $salary_data[$value];
            
            if(empty($temp)){
                
               if(
                   date("Y-m-d",strtotime($value))>date("Y-m-d",strtotime($user_entry_date))&&
                   date("Y-m-d",strtotime($value))<date("Y-m-d",strtotime("today"))&&
                   in_array(date("w",strtotime($value)), [1,2,3,4,5])
                   ){
        
                  $day_status = "旷";

               }else{
                   if(date("Y-m-d",strtotime($value))>date("Y-m-d",strtotime($user_entry_date))&&
                   date("Y-m-d",strtotime($value))<date("Y-m-d",strtotime("today"))){
                       $day_status = "休";
                   }
                   
               }
            }else{
                $day_status = $temp;
            }
            
            $calendar_data[$value] = $day_status.'-'.$temp_salary;
            
        }
        
        $week_card = [];
        foreach ($calendar_data as $day=>$status){
            $temp = '';
            $temp['day'] = $day;
            
            $status_d_s = explode("-", $status);
            
            $temp['status'] = $status_d_s[0];
            $temp['money'] = $status_d_s[1];
            $week_card[] = $temp;
        }
        return $week_card?:'null';
    }
    
    /**
     * 获取打卡记录仅获取月份
     * **/
    public function getcardlistbymonth($user)
    {
        $condition['userid'] = $user['id'];
        
        
        $list = $this->field("time")->where($condition)->order(['time'=>'desc'])->select();
        $monthdata = [];
        foreach ($list as $key=>$value){
            $monthdata[date("Y-m",$value['time'])] = date("Y年m月",$value['time']);
        }
        return $monthdata;
        
    }
    
    /**
     * 从日历数据导出时间数组
     * **/
    private function createdatefromcalendar($date)
    {
        $cal = $this->calendarforeign($date);
        
        $date = [];
        
        foreach($cal as $key=>$value){
            
            foreach($value[0] as $k=>$v){
                $date[] = $v;
            }
        }
        
        return $date;
    }
    
    /**
     * 外国人的日历
     * **/
    public function calendarforeign($date)
    {
        $week_name = ['1'=>'一','2'=>'二','3'=>'三','4'=>'四','5'=>'五','6'=>'六','0'=>'日'];
        
        if(!empty($date)){
            $now = $date;
        }else{
            $now = date("Y-m",time());
        }
        $calendar = [];
        $week = range(-1,20);

        $day = [1,8,15,22,29];
 
        $week_calendar = [];
        
        foreach ($day as $dv){
        
            $week_days = [];
        
            //每周的日期
            foreach ($week as $wv){
                 
                $week_days[] = date("Y-m-d",strtotime($wv." day this week $now-".$dv));
            }
        
            $week_calendar[date("Y-m W",strtotime($wv." day this week $now-".$dv))][] = $week_days;
        
        }
        
        $week_calendar['nowyear'] = date("Y",$date?strtotime($date):time());
        $week_calendar['nowmonth'] = date("m",$date?strtotime($date):time());
        $week_calendar['today'] = date("Y-m-d 第W周星期".$week_name[date("w",time())],time());
        return $week_calendar;
    }
}
?>