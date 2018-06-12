<?php

namespace Api\Controller;
class CourseController extends BaseController {

	
    public function course_types(){
    	$types = D('CourseType')->order('sort_index asc,create_date desc')->select();
		$types && $this->returnSuccess('',$types);
		$types || $this->returnError('暂无数据');
    }
	
}