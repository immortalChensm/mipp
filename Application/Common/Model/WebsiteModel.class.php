<?php
namespace Common\Model;

//通用model类
class WebsiteModel extends CommonModel{

	protected $_validate = array();
	public function _initialize(){
		//$this->_validate[] = array('image','require','请上传负责人头像');
		$this->_validate[] = array('name','require','请输入姓名');
		$this->_validate[] = array('phone','require','请输入电话');
	 	//array('phone','/1[3-8][0-9]{9}/','手机号格式有误'), 
	    $this->_validate[] = array('title','require','请输入网点名称');
		$this->_validate[] = array('address','require','请输入网点地址');
		$this->_validate[] = array('lng','require','请输入网点经度');
	    $this->_validate[] = array('lat','require','请输入网点纬度');
	    $this->_validate[] = array('plate_number','require','请输入免费班车车牌');
	    if(ACTION_NAME == 'save'){
	    	$this->_validate[] = array('information','require','请输入网点详情');
	    }elseif(ACTION_NAME =='live_save'){
	    	$this->_validate[] = array('information','require','请输入班车信息');
	    }
	}	
	
}
