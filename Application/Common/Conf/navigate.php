<?php
// 面包屑导航配置
    return array(        
              'admin/index'=>array(
                'name' =>'C2C教育系统',
                'action'=>array(
                     'welcome'=>'欢迎页面',                     
         	       )
               ),
    		  'admin/admin'=>array(
    				'name' =>'系统设置',
    				'action'=>array(
    						'index'=>'角色管理',
    						'edit_pass'=>'修改密码',
    						'add_adminer'=>'教师账号',
    				)
    		  ),
              'admin/system'=>array(
                'name' =>'系统设置',
                'action'=>array(
                     'banner'=>'BANNER管理',        
                     'faq'=>'FAQ管理',
                     'about'=>'关于我们',
                     'opt_log'=> '操作日志',
         	       )
               ),
              'admin/teacher'=>array(
                'name' =>'教师管理',
                'action'=>array(
                     'index'=>'教师列表',        
                     'apply_list'=>'申请审核列表',
                     'teach_type'=>'授课类型',
                     'edit'=>'编辑教师信息',
         	       )
               ),
              'admin/course'=>array(
                'name' =>'课程管理',
                'action'=>array(
                     'index'=>'课程列表',        
                     'apply_list'=>'课程审核',
                     'course_type'=>'课程类型',
                     'course_tag'=>'课程标签',
                     'wait_check_list'=>'待审核课程',
                     'edit'=>'添加课程',
         	       )
               ),
               'admin/comment'=>array(
               		'name' =>'评价管理',
               		'action'=>array(
               				'teacher_cnlist'=>'教师评价',
               				'course_cnlist'=>'课程评价',
               		)
               ),
              'admin/statistic'=>array(
                'name' =>'数据统计',
                'action'=>array(
                     'user_data'=>'用户统计',        
                     'order_data'=>'订单统计',
                     'teacher_data'=>'教师统计',
                     'course_data'=>'课程统计',
                 )
               ),              
              'admin/order'=>array(
                'name' =>'订单管理',
                'action'=>array(
                     'index'=>'订单列表',
                     'user_order'=>'用户订单',
                     'yuyue_order'=>'预约订单',
         	      )
               ),        
              'admin/user'=>array(
                'name' =>'会员管理',
                'action'=>array(
                     'index'=>'用户列表',
                     'address'=>'收货地址',
                     'account_log'=>'用户资金',
                     'levelList'=>'等级列表',
                     'level'=>'添加等级',
                     'comments' => '会员评论',                    
                     'sense_words' => '敏感词列表', 
                     'edit_user' => '编辑会员信息',                   
         	      )
               ),
               'admin/lottery'=>array(
               		'name' =>'抽奖管理',
               		'action'=>array(
               				'index'=>'规则设置',
               				'prize'=>'奖品设置',
               				'lottery_list'=>'抽奖记录',
               		)
               ),
    );
?>
