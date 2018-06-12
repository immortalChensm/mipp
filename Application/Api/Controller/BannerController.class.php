<?php

namespace Api\Controller;
class BannerController extends BaseController {

	
    public function banner(){
    	$banners = D('Banner')->order('sort_index asc,create_date desc')->select();
		$banners && $this->returnSuccess('',$banners);
		$banners || $this->returnError('暂无数据');
    }
	
}