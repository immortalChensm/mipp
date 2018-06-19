<?php
namespace Common\Model;

//通用model类
class ArticleModel extends CommonModel{
	protected $_validate = array(
		array('title','require','请输入标题'),
		array('company_name','require','请输入企业名称'),
		array('image','require','请上传图片'),
		array('position','require','请输入职位名称'),		
		array('desc','require','请输入企业愿景'),
		array('welfare','require','请输入公司福利'),
		);
	
}
