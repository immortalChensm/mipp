<?php
namespace Common\Model;

//通用model类
class CommonModel extends \Think\Model\RelationModel{
    
    protected $pkName = 'id';//主键名称
    
    //保存数据
    public function saveData($data){
        $id = $data[$this->pkName];
        if(empty($id)){//添加
        	unset($data['id']);
            return $this->add($data);
        }else{//修改
            return $this->save($data);
        }
    }
    
    //根据条件删除
    public function delData($where){
        if(is_numeric($where)){
            return $this->where(array($this->pkName=>$where))->limit('1')->delete();
        }else{
            return $this->where($where)->delete();
        }
    }

    public function pages($relation=true,$field,$where,$group,$order,$pageSize=10){
        I('get.p') ? $p=I('get.p') : $p=1;
        $count = $this->where($where)->count();
        $Page = new \Think\Page($count,$pageSize);
        return array(
            'data' => $this->relation($relation)->field($field)->where($where)->group($group)->order($order)->page($p.','.$pageSize)->select(),
            'page' => $Page->show(),
            'sum'  => $count,
        );
    }
    
    /**
     * 数据列表及分页
     * @param mixed $where 查询条件
     * @param int $pageSize 页大小
     * @param string $order 排序
     * @return array array('data'=>数据列表,'page'=>分页)
     */
    public function getPager($where=array(),$pageSize=10,$order='id desc',$relation = false,$group = 'id'){
        I('get.p') ? $p=I('get.p') : $p=1;
        $count = $this->where($where)->count();
        $Page = new \Think\Page($count,$pageSize);
        return array(
            'data' => $this->relation($relation)->where($where)->order($order)->page($p.','.$pageSize)->select(),
            'page' => $Page->show(),
        	'sum'  => $count,
        );
    }
}
