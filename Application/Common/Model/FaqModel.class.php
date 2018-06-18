<?php
namespace Common\Model;

//通用model类
class FaqModel extends CommonModel{

	protected $_validate = array(
			array('question','require','请输入问题'),
			array('answer','require','请输入答案'),
	);
}
