<?php
namespace Common\Model;

//通用model类
class CityModel extends CommonModel{
    
	public function getCity($province_id){
		return $province_id ? $this->where(array('pid'=>$province_id))->select() : false;
	}
}
