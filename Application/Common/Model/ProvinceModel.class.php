<?php
namespace Common\Model;

//通用model类
class ProvinceModel extends CommonModel{
    
	public function getProvince($province_id = NULL,$city_id = NULL){
		$area_data = array();
		$area_data['province'] = $this->select();
		if ($area_data['province']) {
			if ($province_id) {
				$area_data['city'] = D('City')->where(array('pid'=>$province_id))->select();
			}
			if ($city_id) {
				$area_data['district'] = D('District')->where(array('pid'=>$city_id))->select();
			}
		}
		return $area_data;
	}
}
