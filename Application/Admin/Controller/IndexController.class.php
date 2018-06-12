<?php

namespace Admin\Controller;


use Org\Umeng\Umeng;
use Think\Page;

class IndexController extends BaseController
{

    public function index($url='')
    {
        $this->pushVersion();
        if(session('cururl')){
            $url = session('cururl');
        }
        $comtroller_url = $url ? $url : U('Admin/Index/welcome');
        $this->assign('comtroller_url',$comtroller_url);
        $this->assign('menu_list', getMenuList());
        $this->display('index');
    }

    public function websearch(){
        $menu = getAllMenu();
        $arr = array();
        foreach ($menu as $key => $val) {
            $arr[] = array($val['name'],$val['sub_menu'][0]);  //取出父级菜单等于子级的第一个
            foreach ($val['sub_menu'] as $va) {                 //循环子级菜单
                $arr[] = array($va['name'],$va);
            }
        }
        $keyword = I('get.keyword');
        $url = '';
        foreach ($arr as $v) {
            if(strpos($v[0],$keyword) !== false){           //strpos() 函数查找字符串在另一字符串中第一次出现的位置
                $url = U($v[1]['control'].'/'.$v[1]['act']);
                break;
            }
        }
        $this->assign('keyword',$keyword);
        $this->index($url);
    }

    public function welcome()
    {
        $this->assign('sys_info', $this->get_sys_info());
        //老师总数
        $this->assign('teacher_count',D('Teacher')->where(array('status'=>1))->count());
        //会员总数
        $this->assign('user_count',D('User')->count());
        //课程总数
        $this->assign('course_count',D('Course')->where(array('status'=>1))->count());
        //课程销量
        $this->assign('sale_count',D('Order')->where(array('status'=>1))->count());
        //今日申请的老师数量
        $this->assign('today_apply_count',D('Teacher')->where(array('status'=>3,'create_date'=>array('EGT',date('Y-m-d ').'00:00:00')))->count());
        //今日新增的会员数量
        $this->assign('today_user_count',D('User')->where(array('create_date'=>array('EGT',date('Y-m-d ').'00:00:00')))->count());
        //今日新增的评论数量
        $this->assign('today_comment_count',D('Comment')->where(array('create_date'=>array('EGT',date('Y-m-d ').'00:00:00')))->count());
        
        $this->display();
    }

    public function get_sys_info()
    {
        $sys_info['os'] = PHP_OS;
        $sys_info['zlib'] = function_exists('gzclose') ? 'YES' : 'NO';//zlib
        $sys_info['safe_mode'] = (boolean)ini_get('safe_mode') ? 'YES' : 'NO';//safe_mode = Off
        $sys_info['timezone'] = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
        $sys_info['curl'] = function_exists('curl_init') ? 'YES' : 'NO';
        $sys_info['web_server'] = $_SERVER['SERVER_SOFTWARE'];
        $sys_info['phpv'] = phpversion();
        $sys_info['ip'] = GetHostByName($_SERVER['SERVER_NAME']);
        $sys_info['fileupload'] = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'unknown';
        $sys_info['max_ex_time'] = @ini_get("max_execution_time") . 's'; //脚本最大执行时间
        $sys_info['set_time_limit'] = function_exists("set_time_limit") ? true : false;
        $sys_info['domain'] = $_SERVER['HTTP_HOST'];
        $sys_info['memory_limit'] = ini_get('memory_limit');
        $sys_info['version'] = file_get_contents('./Application/Admin/Conf/version.txt');
        $mysqlinfo = M()->query("SELECT VERSION() as version");
        $sys_info['mysql_version'] = $mysqlinfo['version'];
        if (function_exists("gd_info")) {
            $gd = gd_info();
            $sys_info['gdinfo'] = $gd['GD Version'];
        } else {
            $sys_info['gdinfo'] = "未知";
        }
        return $sys_info;
    }

    /*
   * 获取商品分类
   */
    public function get_category()
    {
        $parent_id = I('get.parent_id', 0); // 商品分类 父id
        empty($parent_id) && exit('');
        $list = M('goods_category')->where(array('parent_id' => $parent_id))->select();
        // 店铺id
        $store_id = session('store_id');
        //如果店铺登录了
        if ($store_id) {
            $store = M('store')->where("store_id = $store_id")->find();

            if ($store['bind_all_gc'] == 0) {
                $class_id1 = M('store_bind_class')->where("store_id = $store_id and state = 1")->getField('class_1', true);
                $class_id2 = M('store_bind_class')->where("store_id = $store_id and state = 1")->getField('class_2', true);
                $class_id3 = M('store_bind_class')->where("store_id = $store_id and state = 1")->getField('class_3', true);
                $class_id = array_merge($class_id1, $class_id2, $class_id3);
                $class_id = array_unique($class_id);
            }
        }
        foreach ($list as $k => $v) {
            // 如果是某个店铺登录的, 那么这个店铺只能看到自己申请的分类,其余的看不到
            if ($class_id && !in_array($v['id'], $class_id))
                continue;
            $html .= "<option value='{$v['id']}' rel='{$v['commission']}'>{$v['name']}</option>";
        }

        exit($html);
    }

    public function pushVersion()
    {
        if (!empty($_SESSION['isset_push']))
            return false;
        $_SESSION['isset_push'] = 1;
        error_reporting(0);//关闭所有错误报告
        $app_path = dirname($_SERVER['SCRIPT_FILENAME']) . '/';
        $version_txt_path = $app_path . '/Application/Admin/Conf/version.txt';
        $curent_version = file_get_contents($version_txt_path);

        $vaules = array(
            'domain' => $_SERVER['SERVER_NAME'],
            'last_domain' => $_SERVER['SERVER_NAME'],
            'key_num' => $curent_version,
            'install_time' => INSTALL_DATE,
            'serial_number' => SERIALNUMBER,
        );
        $url = "http://service.tp-shop.cn/index.php?m=Home&c=Index&a=user_push&" . http_build_query($vaules);
        stream_context_set_default(array('http' => array('timeout' => 3)));
        file_get_contents($url);
    }

    /**
     * ajax 修改指定表数据字段  一般修改状态 比如 是否推荐 是否开启 等 图标切换的
     * table,id_name,id_value,field,value
     */
    public function changeTableVal()
    {
        $table = I('table'); // 表名
        $id_name = I('id_name'); // 表主键id名
        $id_value = I('id_value'); // 表主键id值
        $field = I('field'); // 修改哪个字段
        $value = I('value'); // 修改字段值
        M($table)->where("$id_name = $id_value")->save(array($field => $value)); // 根据条件保存修改的数据

    }
    //修改商品下架上架 同时修改经销商表
    public function changeGoodsVal()
    {
        $table = I('table'); // 表名
        $id_name = I('id_name'); // 表主键id名
        $id_value = I('id_value'); // 表主键id值
        $field = I('field'); // 修改哪个字段
        $value = I('value'); // 修改字段值
        M($table)->where("$id_name = $id_value")->save(array($field => $value)); // 根据条件保存修改的数据
        M('agency_goods')->where(['goods_id'=>$id_value])->save(['is_on_sale'=>$value]);

    }


    public function push_msg()
    {
        if (IS_POST) {
            $post = I("post.");
            $web_site = "https://nd.dongyin.com:8093";
            $data["ticker"] = $post["ticker"];
            $data["title"] = $post["title"];
            $data["text"] = $post["text"];
            $data["after_open"] = "go_app";
            // $data["url"] = $post["url"];
            // $data["img"] = $post["img_url"];
            $data["type_id"] = 1;
            $data["push_time"] = time();
            $data['img'] =$web_site.$post['img'];
            /* 点击"通知"的后续行为，默认为打开app。
            "after_open": "xx" // 必填 值可以为:
                                   "go_app": 打开应用
                                   "go_url": 跳转到URL
                                   "go_activity": 打开特定的activity
                                   "go_custom": 用户自定义内容。
            "url": "xx",       // 可选 当"after_open"为"go_url"时，必填。
                                   通知栏点击后跳转的URL，要求以http或者https开头  */


            $msg =
                [
                    "ticker" => $post["ticker"],
                    "title" => $post["title"],
                    "text" => $post["text"],
                    'site_url' => htmlspecialchars_decode($post['url']),
                    'message_type' => $post['message_type'],
                    'description' => $post['description'],
                    'img' => $web_site.$post['img'],
                    // "img_url"   => $post["img_url"],
                    //"after_open" => "go_custom",
                    // 'thirdparty_id' =>$post['thirdparty_id'],
                    "activity" => "com.ydd.nongding",
                ];
            // 安卓端推送
            $andriod_umeng = $this->andriodumeng($msg);


            $ios_msg =
                [
                    // "ticker" => $data["alert"],
                    "title" => $post["title"],
                    "alert" => $post["title"],
                    "description" => $post["text"],
                    "img" => $web_site.$post["img"],
                    'message_type' => $post['message_type'],
                    'site_url' => htmlspecialchars_decode($post['url']),
                    // "text"   => $data["description"],
                    "form_site" => 2,

                ];

            //ios推送
            $ios_umeng = $this->iosumeng($ios_msg);


            //if ($andriod_umeng["ret"] == "SUCCESS") {
            if (1) {
                $data["status"] = 1;
                $data["push_num"] = 1;
                $data['msg_type'] =$post['message_type'];
                $data['url'] =htmlspecialchars_decode($post['url']);
                $data["img"] = $web_site.$post['img'];
                M("push_message")->add($data);
                $this->success('发送成功!', U("Index/push_msg_list"));
            } else {
                $data["status"] = 0;
                $data["push_num"] = 0;
                M("push_message")->add($data);
                $this->success('发送失败,请重新发送!', U("Index/push_msg_list"));
            }
            exit();
        }

        $this->display();
    }

    public function push_msg_edit()
    {
        $admin_id = session("admin_id");
        $web_site = "https://nd.dongyin.com:8093";
        $getid = addslashes(I("get.id"));
        if (!is_numeric($getid)) {
            $this->error('非法id!', U("Index/push_msg_list"));
            eixt();
        }
        $where = " id = $getid ";
        $msg_list = M("push_message")->where($where)->find();
        if (!$msg_list) {
            $this->error('非法数据!', U("Index/push_msg_list"));
            eixt();
        }

        if (IS_POST) {
            $post = I("post.");
            $data["ticker"] = $post["ticker"];
            $data["title"] = $post["title"];
            $data["text"] = $post["text"];
            $data["after_open"] = "go_app";
            // $data["url"] = $post["url"];
            // $data["img"] = $post["img_url"];
            $data["type_id"] = 1;
            $data["push_time"] = time();
            $data['img'] =$web_site.$post['img'];
            /* 点击"通知"的后续行为，默认为打开app。
            "after_open": "xx" // 必填 值可以为:
                                   "go_app": 打开应用
                                   "go_url": 跳转到URL
                                   "go_activity": 打开特定的activity
                                   "go_custom": 用户自定义内容。
            "url": "xx",       // 可选 当"after_open"为"go_url"时，必填。
                                   通知栏点击后跳转的URL，要求以http或者https开头  */


            $msg =
                [
                    "ticker" => $post["ticker"],
                    "title" => $post["title"],
                    "text" => $post["text"],
                    'site_url' => htmlspecialchars_decode($post['url']),
                    'message_type' => $post['message_type'],
                    'img' => $web_site.$post['img'],
                    // "img_url"   => $post["img_url"],
                    "after_open" => "go_app",
                    // 'thirdparty_id' =>$post['thirdparty_id'],
                    "activity" => "com.ydd.nongding",
                ];
            // 安卓端推送
            $andriod_umeng = $this->andriodumeng($msg);

            $ios_msg =
                [
                    "title" => $post["title"],
                    "alert" => $post["title"],
                    'site_url' => htmlspecialchars_decode($post['url']),
                    "description" => $post["text"],
                    "img" => $web_site.$post["img"],
                    'message_type' => $post['message_type'],
                    // "text"   => $data["description"],
                    "form_site" => 2,

                ];

            //ios推送
            $ios_umeng = $this->iosumeng($ios_msg);


            if (1) {
                // if ($andriod_umeng["ret"] == "SUCCESS") {
                $data["status"] = 1;
                $data["push_num"] = 1;
                $data['msg_type'] =$post['message_type'];
                $data["img"] = $web_site.$post['img'];
                $data['url'] =htmlspecialchars_decode($post['url']);
                M("push_message")->add($data);
                $this->success('发送成功!', U("Index/push_msg_list"));
            } else {
                $data["status"] = 0;
                $data["push_num"] = 0;
                M("push_message")->add($data);
                $this->success('发送失败,请重新发送!', U("Index/push_msg_list"));
            }
            exit();


        }

        $this->assign("msg_list", $msg_list);
        $this->assign("is_edit", 1);
        $this->assign("getid", $getid);
        $this->display("push_msg");
    }

    public function push_msg_list()
    {
        $admin_id = session("admin_id");
        $where = "1=1";
        $push_message = M("push_message");
        $count = $push_message->where($where)->count();
        $Page = new Page($count, 10);

        $msg_list = $push_message->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->order("push_time desc")->select();

        $show = $Page->show();
        $this->assign("msg_list", $msg_list);
        $this->assign('page', $show);// 赋值分页输出
        $this->display();
    }

    public function push_msg_del()
    {
        $admin_id = session("admin_id");
        $id = I('id', 0);
        if (is_array($id)) $id = implode(",", $id);

        $where = "id in($id)  ";
        if (M('push_message')->where($where)->delete()) {
            $this->success('操作成功!');
        } else {
            $this->success('操作失败!');
        }

    }

    public function andriodumeng($msg)
    {   //andourd
        $umeng = new Umeng("58a4379af43e481a58000397", "nlbghghjzszwintjlmmlrhazvappfb0o");

        $umeng_data = $umeng->sendAndroidBroadcast($msg);
        $send = json_decode($umeng_data, true);
        return $send;
    }

    public function iosumeng($msg)
    {   //ios
        $umeng = new Umeng("58a50e05677baa50b80019f9", "ckj2zfxb0f3fpxxtugpfpxfyk8expcdi");
        $umeng_data = $umeng->sendIOSBroadcast($msg);
        $send = json_decode($umeng_data, true);
        return $send;
    }

    public function ios_umeng()
    {

        //ios
        if (IS_POST) {
            $data = I("post.");
            $msg =
                [
                    // "ticker" => $data["alert"],
                    "title" => $data["title"],
                    "alert" => $data["alert"],
                    "description" => $data["description"],
                    // "text"   => $data["description"],
                    "form_site" => 2,
                    //  "url"   => $data["url"],
                    // "img_url"   => $data["img_url"],
                    //  "msg_type" =>$data['msg_type'],
                    //  "activity"=>"com.ruitukeji.heshanghui.browser.BrowserActivity",
                ];

            $umeng = $this->iosumeng($msg);
            /*    if($umeng["ret"] == "SUCCESS")
                 {   $data['ticker']= $data["alert"];
                     $data["text"]  = $data["description"];
                     $data["status"] = 1;
                     $data["push_num"] = 1;
                     M("push_message") -> add($data);
                     $this->success('发送成功!',U("Index/push_msg_list"));
                 }else
                 {
                     $data["status"] = 0;
                     $data["push_num"] = 0;
                     M("push_message") -> add($data);
                     $this->success('发送失败,请重新发送!',U("Index/push_msg_list"));
                 }
                 exit();*/

        }


    }

    public function ios_push()
    {
        $this->display();
    }

}