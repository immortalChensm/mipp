<?php
//  加载常量配置文件
require_once('constant.php');
return array(
	'URL_MODEL' => "2", //URL模式：2重写模式
	'AUTH_CODE' => "TPSHOP", //安装完毕之后不要改变，否则所有密码都会出错
    /* 加载公共函数 */
    'LOAD_EXT_FILE' =>'common',
    'URL_CASE_INSENSITIVE' => false, //URL大小写不敏感
    'LOAD_EXT_CONFIG'=>'db,route,area,menu,log', // 加载数据库配置文件
    /*
     * RBAC认证配置信息
     */

    'SESSION_AUTO_START'        => true,
    'USER_AUTH_ON'              => true,
    'USER_AUTH_TYPE'            => 1,         // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY'             => 'authId',  // 用户认证SESSION标记
    'ADMIN_AUTH_KEY'            => 'administrator',
    'USER_AUTH_MODEL'           => 'User',    // 默认验证数据表模型
    'AUTH_PWD_ENCODER'          => 'md5',     // 用户认证密码加密方式
    'USER_AUTH_GATEWAY'         => '/Public/login',// 默认认证网关
    'NOT_AUTH_MODULE'           => 'Public',  // 默认无需认证模块

    'GUEST_AUTH_ON'             => false,     // 是否开启游客授权访问
    'GUEST_AUTH_ID'             => 0,         // 游客的用户ID
    'DB_LIKE_FIELDS'            => 'title|remark',
    'RBAC_ROLE_TABLE'           => 'think_role',
    'RBAC_USER_TABLE'           => 'think_role_user',
    'RBAC_ACCESS_TABLE'         => 'think_access',
    'RBAC_NODE_TABLE'           => 'think_node',
    'SHOW_PAGE_TRACE'           =>1,         //显示调试信息

    'ERROR_PAGE'=>'/index.php/Home/Tperror/tp404.html',

    'DEFAULT_FILTER'        => 'strip_sql,htmlspecialchars',   // 系统默认的变量过滤机制

    //小程序APPID
	'APPID' => 'wx91ff772237eff014',
	'APPSECRET' => '8185656c55109de807ec494953ad080d',
	'APP_AUTH_URL' => 'https://api.weixin.qq.com/sns/jscode2session?grant_type=authorization_code&',//appid=APPID&secret=SECRET&js_code=JSCODE

);