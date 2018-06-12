<?php
namespace Admin\Controller;
//图片上传类
class UploadController extends \Think\Controller{

    public function index(){
    	$files = array_values($_FILES);
    	
    	$result = curl_request(C('UPIMG_INTERFACE'),array(
    			'sign'=>C('API_SIGN'),
    			'source'=>base64_encode(file_get_contents($files[0]['tmp_name'])),
    			'name'=>$files[0]['name'],
    			'type'=>$files[0]['type'],
    			'size'=>$files[0]['size']
    	));
    	$result = json_decode($result,true);
    	if($result['status'] == '1'){
    		exit(json_encode(array('status'=>'1','info'=>$result['data'])));
    	}else {
    		exit(json_encode(array('status'=>'0','info'=>$result['msg'])));
    	}
    }
    
    public function upfile(){
    	$upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize = 2097152; // 设置附件上传大小
        $upload->exts = array('xls', 'xlsx', 'csv', 'CSV');
        $upload->rootPath = $_SERVER['DOCUMENT_ROOT'].'/Upload/';
        $upload->savePath = 'csv/';
        $file = array_values($_FILES);
    	$info = $upload->uploadOne($file[0]);
        if (!$info) {
        	exit(json_encode(array('status'=>'0','info'=>$upload->getError())));
        } else {
			exit(json_encode(array('status'=>'1','info'=>'./Upload/'.$info['savepath'].$info['savename'])));
        }
    }
    public function upvideo(){
    	$files = array_values($_FILES);
    	var_dump($files);exit;
    	$result = curl_request(C('UPVIDEO_INTERFACE'),array(
    			'sign'=>C('API_SIGN'),
    			'source'=>base64_encode(file_get_contents($files[0]['tmp_name'])),
    			'name'=>$files[0]['name'],
    			'type'=>$files[0]['type'],
    			'size'=>$files[0]['size']
    	));
    	$result = json_decode($result,true);
    	if($result['status'] == '1'){
    		exit(json_encode(array('status'=>'1','info'=>$result['data'])));
    	}else {
    		exit(json_encode(array('status'=>'0','info'=>$result['msg'])));
    	}
    }
}