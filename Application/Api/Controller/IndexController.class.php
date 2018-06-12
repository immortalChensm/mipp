<?php
namespace Api\Controller;
class IndexController extends BaseController {

	//热门推荐职位
    public function index(){
    	$datas = array();
    	
		$this->returnSuccess('',$datas);
    }

}