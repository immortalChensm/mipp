<?php
namespace Api\Controller;
class SystemController extends BaseController {

    //关于我们和常见问题
    public function index(){
        $info = D('System')->where(array('type'=>I('type')))->field('content')->find();
        $info['content'] = htmlspecialchars_decode($info['content']);
        $this->returnSuccess('',$info);
    }

}