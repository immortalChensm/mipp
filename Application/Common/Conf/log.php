<?php
    return array(
        'OPTLOG' => array(
    		'Admin/login' => array('type'=>1,'message'=>'登录后台'),
    		'Admin/add_adminer' => array('type'=>1,'message'=>'更新教师账号'),
    		'Admin/edit_pass' => array('type'=>1,'message'=>'修改密码'),
    		'Admin/dele_adminer' => array('type'=>1,'message'=>'删除教师账号'),
    		'System/faq' => array('type'=>1,'message'=>'更新FAQ'),
    		'System/about' => array('type'=>1,'message'=>'更新关于我们'),
    		'System/edit_banner' => array('type'=>1,'message'=>'更新Banner'),
    		'System/dele_banner' => array('type'=>1,'message'=>'删除Banner'),
    		'System/cleanCache' => array('type'=>1,'message'=>'清除缓存'),

    		'Teacher/dele_teacher' => array('type'=>2,'message'=>'删除教师'),
    		'Teacher/edit' => array('type'=>2,'message'=>'编辑教师信息'),
    		'Teacher/edit_teach_type' => array('type'=>2,'message'=>'编辑授课类型'),
    		'Teacher/dele_teach_type' => array('type'=>2,'message'=>'删除授课类型'),
    		'Teacher/set_stick' => array('type'=>2,'message'=>'设置推荐教师'),
    		'Teacher/check_teacher' => array('type'=>2,'message'=>'审核教师'),

    		'Course/edit_course_type' => array('type'=>3,'message'=>'编辑课程类型'),
    		'Course/dele_course_type' => array('type'=>3,'message'=>'删除课程类型'),
    		'Course/edit_course_tag' => array('type'=>3,'message'=>'编辑课程标签'),
    		'Course/dele_course_tag' => array('type'=>3,'message'=>'删除课程标签'),
    		'Course/set_stick' => array('type'=>3,'message'=>'设置推荐课程'),
    		'Course/set_status' => array('type'=>3,'message'=>'更新课程状态'),
    		'Course/check_course' => array('type'=>3,'message'=>'审核课程'),
    		'Course/edit' => array('type'=>3,'message'=>'编辑课程信息'),
    	)
    );
?>
