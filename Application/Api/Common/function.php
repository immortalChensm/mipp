<?php

function jsonReturn($status = 0, $msg = '', $data = ''){
    if(empty($data)){
        $data = new stdClass();
    }
    $info['status'] = $status;
    $info['msg'] = $msg;
    $info['result'] = $data;
    header('Content-Type:text/json;charset=utf-8');
    exit(json_encode($info));
}

/**周薪星汉字排序***/
function chinese_character_sort($arr)
{


    $rows = [];

    foreach ($arr as $value){

        $rows[$value['id']] = iconv("UTF-8", "GBK", $value['name']);
    }

    $res = asort($rows);

    $new = [];

    foreach ($rows as $key=>$value){

        $new[$key] = iconv("GBK", "UTF-8", $value);
    }

    return $new;


}


//经纬度转换
function locationtransform($latitude,$longitude){
     
    $ak = C("BAIDU_KEY");

    $url = "http://api.map.baidu.com/geocoder/v2/?ak={$ak}&location=".$latitude.",".$longitude."&output=json&pois=1";

    $place = json_decode(file_get_contents($url),true);
     
    return $place;
}


/**
 * 高德地图逆地理位置编码[经纬度转换]  调用次数日300w次　
 * @param number $lng 经度
 * @param number $lat 纬度
 * @return string 返回位置名称
 * **/
function gdlocation($lng,$lat)
{
    $gdkey = C("GDMAP")['key'];
    $url = "http://restapi.amap.com/v3/geocode/regeo?key={$gdkey}&location={$lng},{$lat}&poitype=&radius=100&extensions=all&batch=false&roadlevel=0";
    $result = file_get_contents($url);
    $result = json_decode($result,true);
    if($result['info']=="OK"){
        return $result['regeocode']['formatted_address'];
    }else{
        return null;
    }
}

