<?php
namespace Common\Model;
//通用model类
class BannerModel extends CommonModel{

		protected $_validate = array(
			array('image','require','请上传图片'),
	);
}
?>