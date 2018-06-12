<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller {

    // 
    public function _initialize() 
    {	
        
    	header("Content-Type:text/html;charset=utf8");
    	//面包线导航
    	$this->assign('navigate_admin',navigate_admin());
    	//方法名
        $this->assign('action',ACTION_NAME);
		
        //过滤不需要登陆的行为
        if(in_array(ACTION_NAME,array('login','logout','vertify')) || in_array(CONTROLLER_NAME,array('Ueditor','Uploadify'))){
        	//return;
        }else{
        	if(session('admin_id') > 0 ){
        		$this->assign('admin_info',admin_info(session('admin_id')));
				
        		$this->check_priv();//检查管理员菜单操作权限
        	}else{     
                exit('<script>
                    if(window.top == window.self){
                        window.location.href="'.U('Admin/login').'";
                    }else{
                        parent.window.location.href="'.U('Admin/login').'";
                    }
                </script>');
 			}
        }
        
        session('over_admin_id') && $this->assign('over_admin_info',D('Admin')->where(array('id'=>(int)session('over_admin_id')))->find());
        if(!IS_AJAX && CONTROLLER_NAME != 'Index' && CONTROLLER_NAME != 'Admin' && ACTION_NAME!='login_task' && ACTION_NAME!='cleanCache'){
        	$param_str = I('get.') ? '?'.http_build_query(I('get.')):'';
            session('cururl',U(CONTROLLER_NAME/ACTION_NAME).$param_str);
        }
    }
    public function check_priv()
    {
    	$ctl = CONTROLLER_NAME;
    	$act = ACTION_NAME;
//     	var_dump($ctl,$act);exit;
		//无需验证的操作
		$uneed_check = array('login','logout','vertifyHandle','vertify','imageUp','upload','login_task');
    	if($ctl == 'Index') return true;
    	if(strpos('ajax',$act) || in_array($act,$uneed_check)) return true;
    	
    	$menus = getAllMenu();
    	$powers = array();
    	foreach ($menus as $key=>$val){
    		$powers = $val['prv'] == null ? $powers : array_merge($val['prv'],$powers);
    	}
    	//检查是否拥有此操作权限
    	if(!in_array($ctl.'/'.$act, $powers))$this->error('抱歉，您没有此操作权限',U('Admin/Index/welcome'));
    }

    public function del($con_name = '')
    {
        $con_name || $con_name = CONTROLLER_NAME;
        $res = D($con_name)->delData(intval(I('request.id')));
        $res ? $this->success('删除成功') : $this->error('删除失败');
    }
}