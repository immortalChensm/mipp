<?php
namespace Admin\Controller;
//图片上传类
class PicUploadController extends \Think\Controller{
//     private $thumb_type = array(
//     	'1' => 'IMAGE_THUMB_SCALE',//1 等比缩放
//     	'2' => 'IMAGE_THUMB_FILLED',//2 缩放后填充
//     	'3' => 'IMAGE_THUMB_CENTER',//3 居中裁剪
//     	'4' => 'IMAGE_THUMB_NORTHWEST',//4 左上角裁剪
//     	'5' => 'IMAGE_THUMB_SOUTHEAST',//5 右下角裁剪
//     	'6' => 'IMAGE_THUMB_FIXED',//6 固定尺寸缩放
//     );
	public function index(){
		if(IS_POST){
            $upload           = new \Think\Upload();
            $upload->maxSize  = 10145728 ;  
            $upload->exts     = array('jpg', 'gif', 'png', 'jpeg');
            $upload->rootPath = './Upload/';
            $upload->savePath = 'admin/';
            $file = array_values($_FILES);
            $info             = $upload->uploadOne($file[0]);
            if(!$info){
                echo json_encode(array('status'=>0,'info'=>$upload->getError()));die;
            }else{
            	$type = !empty(I('post.type'))?I('post.type'):1;
                $this->thumb($upload->rootPath.$info['savepath'].$info['savename'],$type);//缩略裁剪
                echo json_encode(array('status'=>1,'info'=>$upload->rootPath.$info['savepath'].$info['savename']));die;
            }
		}
	}
	
    //生成做略图
	public function thumb($img,$type = 1, $width = 1600, $height = 1600){
		$image = new \Think\Image();
        $image->open($img);
        $size = $image->size();
        if($size[0] <= $width && $size[1] <= $height) return;
//         $image->thumb($width, $height,\Think\Image::IMAGE_THUMB_CENTER)->save($img);
        $image->thumb($width,$height,$type)->save($img);
    }
}