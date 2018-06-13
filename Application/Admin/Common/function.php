<?php


/**
 * 管理员操作记录
 * @param $log_url 操作URL
 * @param $log_info 记录信息
 * @param $log_type 日志类别
 */
function adminLog($log_info,$log_type=0){
    $add['log_time'] = time();
    $add['admin_id'] = session('admin_id');
    $add['log_info'] = $log_info;
    $add['log_ip'] = getIP();
    $add['log_url'] = __ACTION__;
    $add['log_type'] = $log_type;
    M('admin_log')->add($add);
}


function getAdminInfo($admin_id){
	return D('admin')->where("admin_id=$admin_id")->find();
}

function tpversion()
{     
    if(!empty($_SESSION['isset_push']))
        return false;    
    $_SESSION['isset_push'] = 1;    
    error_reporting(0);//关闭所有错误报告
    $app_path = dirname($_SERVER['SCRIPT_FILENAME']).'/';
    $version_txt_path = $app_path.'/Application/Admin/Conf/version.txt';
    $curent_version = file_get_contents($version_txt_path);
    
    $vaules = array(            
            'domain'=>$_SERVER['HTTP_HOST'], 
            'last_domain'=>$_SERVER['HTTP_HOST'], 
            'key_num'=>$curent_version, 
            'install_time'=>INSTALL_DATE, 
            'cpu'=>'0001',
            'mac'=>'0002',
            'serial_number'=>SERIALNUMBER,
            );     
     $url = "http://service.tp-shop.cn/index.php?m=Home&c=Index&a=user_push&".http_build_query($vaules);
     stream_context_set_default(array('http' => array('timeout' => 3)));
     file_get_contents($url);       
}
 
/**
 * 面包屑导航  用于后台管理
 * 根据当前的控制器名称 和 action 方法
 */
function navigate_admin()
{        
    $navigate = include APP_PATH.'Common/Conf/navigate.php';    
    $location = strtolower('Admin/'.CONTROLLER_NAME);
    $arr = array(
        '后台首页'=>U('Index/welcome'),
        $navigate[$location]['name']=>'javascript:void();',
        $navigate[$location]['action'][ACTION_NAME]=>'javascript:void();',
    );
    return $arr;
}

/**
 * 导出excel
 * @param $strTable	表格内容
 * @param $filename 文件名
 */
function downloadExcel($strTable,$filename)
{
	header("Content-type: application/vnd.ms-excel");
	header("Content-Type: application/force-download");
	header("Content-Disposition: attachment; filename=".$filename."_".date('Y-m-d').".xls");
	header('Expires:0');
	header('Pragma:public');
	echo '<html><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.$strTable.'</html>';
}

/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
	$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
	return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 根据id获取地区名字
 * @param $regionId id
 */
function getRegionName($regionId){
    $data = M('region')->where(array('id'=>$regionId))->field('name')->find();
    return $data['name'];
}

/* function getMenuList(){
	$menu_list = getAllMenu();
	$admin_info = admin_info(session('admin_id'));
	if(empty($admin_info)) return false;
	if($admin_info['power']){
		if ($admin_info['power'] != 'all') {
			$mypower = unserialize($admin_info['power']);
			$powers = array();
			foreach ($mypower as $val){
				$powers = array_merge($powers,$val);
			}
			$right = D('SystemMenu')->where("id in(".implode(',', $powers).")")->cache(true)->getField('right',true);
			foreach ($right as $val){
				$role_right .= $val.',';
			}
			$role_right = explode(',', $role_right);
			foreach($menu_list as $k=>$mrr){
				foreach ($mrr['sub_menu'] as $j=>$v){
					if(!in_array($v['control'].'/'.$v['act'], $role_right)){
						unset($menu_list[$k]['sub_menu'][$j]);//过滤菜单
					}
				}
			}
		}
		return $menu_list;
	}else{
		return false;
	}
} */
function getMenuList(){
	return getAllMenu();
}
function getAllMenu(){
	$admin_info = admin_info(session('admin_id'));
	return	$admin_info['type'] == '1'? C('MENU') : C('TEACHER_MENU');
}

//格式化日期 2011-03-02 18:22:34或unix时间戳 转换为：3月2日 18时22分
function fmt_date($date, $time_flg = TRUE) {
	$dateOri = $date;
	if (!strstr($date, '-')) {
		$date = date("Y-m-d H:i:s", $date);
	}
	$date = substr($date, 5, 11);
	$date_array1 = explode('-', $date);
	$month = (int)$date_array1[0];
	$date_array2 = explode(' ', $date_array1[1]);
	$day = (int)$date_array2[0];

	$date2 = substr($dateOri,11);
	$dateArr2 = explode(':', $date2);
	$otherdate = $dateArr2[0].'时'.$dateArr2[1].'分';
	if ($time_flg) {
		return $month . '月' . $day . '日 ' . $otherdate;
	} else {
		return $month . '月' . $day . '日 ';
	}
}


/**
 * 添加操作日志
 */
function add_opt_log(){
	$logmessage = C('OPTLOG');
	$key = CONTROLLER_NAME.'/'.ACTION_NAME;
	if(!$logmessage[$key]) return;
	$logmsg = $logmessage[$key];
	if (session('admin_id')) {
		$admin_info = D('Admin')->where(array('id'=>session('admin_id')))->find();
	}
	if($admin_info) {
		D('OptLog')->add(array('adminer'=>$admin_info['role_name']?$admin_info['role_name']:$admin_info['user_name'],'content'=>$logmsg['message'],'type'=>$logmsg['type']));
	}
	return ;
}
/**
 * 获取后台登录账号信息
 * @param string $admin_id
 * @return boolean|Ambigous <string, \Think\mixed, boolean, NULL, multitype:, unknown, mixed, object>
 */
function admin_info($admin_id = null){
	if(!$admin_id){
		if(session('admin_id')){
			$admin_id = session('admin_id');	
		}else{
			return false;
		}
	}
	$admin_info = D('Admin')->where(array('id'=>$admin_id))->find();
	if($admin_info['type'] == '3'){
		$service_power = C('SERVICE_POWER');
		$service = D('Service')->where(array('id'=>$admin_info['service_id']))->find();
		$service && $admin_info['power'] = serialize($service_power[$service['type']]);
	}
	return $admin_info;
}

/**
 * 获取数据库某字段的最大值
 * @param unknown $model 数据库类
 * @param string $filed 字段
 */
function getMaxValue($model,$filed = 'id'){
	return D($model)->max($filed);
}
