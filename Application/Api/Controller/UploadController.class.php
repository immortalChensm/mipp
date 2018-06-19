<?php

namespace Api\Controller;
class UploadController extends BaseController {
	
	private $upload_path = '/Upload/images/';//图片保存目录
	private $max_size = 6291375;//6M
	
	private $ext = array(
			'image/gif'=>'gif',//gif
			'image/jpeg'=>'jpg',//jpg
			'image/jpeg'=>'jpeg',//jpeg
			'image/x-png'=>'png',//png
			'image/png'=>'png',//png    
			'image/bmp'=>'bmp',//bmp
			'image/x-icon'=>'ico'//ico
	);
    private $thumb_type = array(
    	'1' => 'IMAGE_THUMB_SCALE',//1 等比缩放
    	'2' => 'IMAGE_THUMB_FILLED',//2 缩放后填充
    	'3' => 'IMAGE_THUMB_CENTER',//3 居中裁剪
    	'4' => 'IMAGE_THUMB_NORTHWEST',//4 左上角裁剪
    	'5' => 'IMAGE_THUMB_SOUTHEAST',//5 右下角裁剪
    	'6' => 'IMAGE_THUMB_FIXED',//6 固定尺寸缩放
    );
    
    private $video_upload_path = '/Upload/videos/';//图片保存目录
    private $video_max_size = 20971520;//20M
    private $video_ext = array(
        'video/avi'=>'avi',//gif
        'application/vnd.rn-realmedia-vbr'=>'rmvb',//
        'video/3gpp'=>'3gp',//
        'video/x-flv'=>'flv',// 
        'video/mp4'=>'mp4',//
        'video/x-ms-wmv'=>'wmv'//
	);
	/**
	 * base64图片资源上传图片
	 */
	public function upload_image(){
		($_POST['size'] > $this->max_size) && $this->returnError('图片大小不能超过2M');
		in_array($_POST['type'], array_keys($this->ext)) || $this->returnError('不支持的图片格式');
		$type = $this->ext[$_POST['type']];
		$save_path = $this->upload_path.date('Ymd').'/';
		if(!file_exists($save_path) ) @mkdir($_SERVER['DOCUMENT_ROOT'].$save_path,0777);   //如果目录不存在就重新建
		$filepath = $save_path.substr(md5(time()),0,16).'.'.$type;
		if(file_put_contents($_SERVER['DOCUMENT_ROOT'].$filepath, base64_decode($_POST['source']))){
			$this->returnSuccess('上传成功！',array('image_url'=>'http://'.$_SERVER['HTTP_HOST'].$filepath));
		}else{
			$this->returnError('系统繁忙，上传失败！');
		}
	}
	/**
	 * 表单提交上传图片
	 */
	public function upImage(){
// 		$this->returnSuccess('上传成功！',$_FILES);
		$upfile = $_FILES['upfile'];
		($upfile['size'] > $this->max_size) && $this->returnError('图片大小不能超过2M');

		in_array($upfile['type'], array_keys($this->ext)) || $this->returnError('不支持的图片格式');
		$type = $this->ext[$upfile['type']];
		$save_path = $this->upload_path.date('Ymd').'/';
		if(!file_exists($save_path) ) @mkdir($_SERVER['DOCUMENT_ROOT'].$save_path,0777);   //如果目录不存在就重新建
		$filepath = $save_path.substr(md5(time()),0,16).'.'.$type;
		//上传图片
		$ok = move_uploaded_file($upfile['tmp_name'],$_SERVER['DOCUMENT_ROOT'].$filepath);
		if($ok){
			$this->returnSuccess('上传成功！',array('image_url'=>'http://'.$_SERVER['HTTP_HOST'].$filepath));
		}else{
			$this->returnError('系统繁忙，上传失败！');
		}
	}
	public function upload_video(){
        // $this->returnSuccess('上传成功！',$_POST);
		($_POST['size'] > $this->video_max_size) && $this->returnError('视频大小不能超过20M');
		in_array($_POST['type'], array_keys($this->video_ext)) || $this->returnError('不支持的视频格式');
		$type = $this->video_ext[$_POST['type']];
		$save_path = $this->video_upload_path.date('Ymd').'/';
		if(!file_exists($dirpath) ) @mkdir($_SERVER['DOCUMENT_ROOT'].$save_path,0777);   //如果目录不存在就重新建
		$filepath = $save_path.substr(md5(time()),0,16).'.'.$type;
		if(file_put_contents($_SERVER['DOCUMENT_ROOT'].$filepath, base64_decode($_POST['source']))){
			$this->returnSuccess('上传成功！',array('video_url'=>'http://'.$_SERVER['HTTP_HOST'].$filepath));
		}else{
			$this->returnError('系统繁忙，上传失败！');
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