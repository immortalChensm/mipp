<?php
    return array(
         'MENU' => array(
			'system' => array('name'=>'系统设置','icon'=>'fa-cog',
				'sub_menu'=>array(
					array('name' => '角色管理', 'act'=>'index', 'control'=>'Admin'),
					array('name'=>'BANNER管理','act'=>'banner','control'=>'System'),
					array('name'=>'FAQ管理','act'=>'faq','control'=>'System'),
					array('name'=>'关于我们','act'=>'about','control'=>'System'),
				    array('name'=>'操作日志','act'=>'opt_log','control'=>'System'),
				),
				'prv'=>array(
					'Index/index',
					'Admin/index',
					'Admin/welcome',
					'Admin/add_adminer',
					'Admin/edit_pass',
					'Admin/login',
					'Admin/logout',
					'Admin/dele_adminer',
					'Admin/over_login',
					'System/faq',
					'System/about',
					'System/banner',
					'System/edit_banner',
					'System/dele_banner',
					'System/opt_log',
					'System/cleanCache',
				)
			),
			'teacher' => array('name'=>'教师管理','icon'=>'fa-graduation-cap',
				'sub_menu'=>array(
					array('name'=>'老师列表','act'=>'index','control'=>'Teacher'),
					array('name'=>'申请审核列表','act'=>'apply_list','control'=>'Teacher')
				),
				'prv'=>array(
					'Teacher/index',
					'Teacher/apply_list',
					'Teacher/dele_teacher',
					'Teacher/teach_type',
					'Teacher/edit_teach_type',
					'Teacher/dele_teach_type',
					'Teacher/set_stick',
					'Teacher/check_teacher',
					'Teacher/edit',
					'Teacher/info',
				)
			),
			'course' => array('name' => '课程管理', 'icon'=>'fa-leaf', 
				'sub_menu' => array(
					array('name' => '课程列表', 'act'=>'index', 'control'=>'Course'),
					array('name' => '课程审核', 'act'=>'apply_list', 'control'=>'Course'),
				),
				'prv'=>array(
					'Course/index',
					'Course/course_type',
					'Course/edit_course_type',
					'Course/dele_course_type',
					'Course/course_tag',
					'Course/edit_course_tag',
					'Course/dele_course_tag',
					'Course/set_stick',
					'Course/set_status',
					'Course/apply_list',
					'Course/check_course',
					'Course/course_info',
				)
			),
         	'comment' => array('name' => '评价管理', 'icon'=>'fa-comments', 
         		'sub_menu' => array(
	         		array('name' => '教师评价', 'act'=>'teacher_cnlist', 'control'=>'Comment'),
	         		array('name' => '课程评价', 'act'=>'course_cnlist', 'control'=>'Comment')
         		),
         		'prv'=>array(
         			'Comment/teacher_cnlist',
         			'Comment/course_cnlist',
         			'Comment/dele_comment',
         			'Comment/set_status',
         		)
         	),
            'user'=>array('name'=>'用户管理','icon'=>'fa-user',
            	'sub_menu'=>array(
                    array('name' => '用户列表','act'=>'index', 'control'=>'User'),
            	),
            	'prv'=>array(
            		'User/index',
            	)
            ),
            'order'=>array('name'=>'订单管理','icon'=>'fa-cart-plus',
            	'sub_menu'=>array(
            		array('name' => '订单列表','act'=>'index', 'control'=>'Order'),
            		array('name' => '预约订单','act'=>'yuyue_order', 'control'=>'Order'),
            	),
            	'prv'=>array(
            		'Order/index',
            		'Order/yuyue_order',
            		'Order/user_order',
            		'Order/detail',
            		'Order/close_order',
           		)
            ),
			'statistic' => array('name' => '数据统计', 'icon'=>'fa-signal', 
				'sub_menu' => array(
					array('name' => '用户统计', 'act'=>'user_data', 'control'=>'Statistic'),
					array('name' => '订单统计', 'act'=>'order_data', 'control'=>'Statistic'),
					array('name' => '教师统计', 'act'=>'teacher_data', 'control'=>'Statistic'),
					array('name' => '课程统计', 'act'=>'course_data', 'control'=>'Statistic'),
				),
			    'prv'=>array(
		    		'Statistic/user_data',
		    		'Statistic/order_data',
		    		'Statistic/teacher_data',
		    		'Statistic/course_data',
				)
			)
		),
    		
        'TEACHER_MENU' => array(
			'system' => array('name'=>'系统设置','icon'=>'fa-cog',
				'sub_menu'=>array(
					array('name' => '修改密码', 'act'=>'edit_pass', 'control'=>'Admin'),
					array('name'=>'教师信息','act'=>'edit','control'=>'Teacher'),
				),
				'prv'=>array(
					'Index/index',
					'Admin/index',
					'Admin/login',
					'Admin/logout',
					'Admin/edit_pass',
					'Admin/welcome',
					'Admin/over_login_back',
					'Teacher/edit',
					'System/cleanCache',
				)
			),
			'course' => array('name' => '课程管理', 'icon'=>'fa-leaf', 
				'sub_menu' => array(
					array('name' => '课程列表', 'act'=>'index', 'control'=>'Course'),
					array('name' => '待审核课程', 'act'=>'wait_check_list', 'control'=>'Course'),
					array('name' => '添加课程', 'act'=>'edit', 'control'=>'Course'),
				),
				'prv'=>array(
					'Course/index',
					'Course/edit',
					'Course/dele_course',
					'Course/wait_check_list',
					'Course/set_status',
				)
			),
         	'comment' => array('name' => '评价管理', 'icon'=>'fa-comments', 
         		'sub_menu' => array(
	         		array('name' => '教师评价', 'act'=>'teacher_cnlist', 'control'=>'Comment'),
	         		array('name' => '课程评价', 'act'=>'course_cnlist', 'control'=>'Comment')
         		),
         		'prv'=>array(
         			'Comment/teacher_cnlist',
         			'Comment/course_cnlist',
         			'Comment/dele_comment',
         		)
         	),
            'order'=>array('name'=>'订单管理','icon'=>'fa-cart-plus',
            	'sub_menu'=>array(
            		array('name' => '订单列表','act'=>'index', 'control'=>'Order'),
            		array('name' => '预约订单','act'=>'yuyue_order', 'control'=>'Order'),
            	),
            	'prv'=>array(
            		'Order/index',
            		'Order/yuyue_order',
            		'Order/close_order',
            		'Order/detail',
           		)
            ),
		),
    );
?>
