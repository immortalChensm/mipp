<?php
/**
 * 获取入职通告信息
 */
function get_announce(){
	//入职信息
	return  D('Entry')->relation(true)->where(array('status'=>array('NEQ','3')))->order('create_date desc')->limit(6)->select();
}
