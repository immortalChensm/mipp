<?php

namespace Admin\Controller;
class SystemController extends BaseController{
	
	/*
	 * 配置入口
	 */
	public function index()
	{          
		$this->assign(getWebConfig());
		$this->display();
	}
	
	/*
	 * 新增修改配置
	 */
	public function handle()
	{
		$param = I('post.');
		foreach ($param as $key=>$val){
			D('Config')->where(array('name'=>$key))->save(array('value'=>$val));
		}
		$this->success("操作成功",U('System/index'));
	}        
        
       /**
        * 自定义导航
        */
    public function navigationList(){
           $model = M("Navigation");
           $navigationList = $model->order("sort desc")->select();
           $this->assign('navigationList',$navigationList);
           $this->display('navigationList');          
     }

    /**
     * 删除前台 自定义 导航
     */
    public function delNav()
    {     
        // 删除导航
        M('Menu')->where("id = {$_GET['id']}")->delete();
        $this->success("操作成功!!!",U('Admin/System/menudiy'));
    }
	
	public function refreshMenu(){
		$pmenu = $arr = array();
		$rs = M('system_module')->where('level>1 AND visible=1')->order('mod_id ASC')->select();
		foreach($rs as $row){
			if($row['level'] == 2){
				$pmenu[$row['mod_id']] = $row['title'];//父菜单
			}
		}

		foreach ($rs as $val){
			if($row['level']==2){
				$arr[$val['mod_id']] = $val['title'];
			}
			if($row['level']==3){
				$arr[$val['mod_id']] = $pmenu[$val['parent_id']].'/'.$val['title'];
			}
		}
		return $arr;
	}
	//faq
	public function faq(){
		if(IS_POST){
			$info = I('post.info');
			$info['type'] = 1;
            $info['content'] = htmlentities($info['content']);
			D('System')->saveData($info) ? $this->success('保存成功!') : $this->error('保存失败！');
		}else {
			$info = D('System')->where(array('type'=>1))->find();
            $info['content'] = html_entity_decode($info['content']);
			$this->assign('info',$info);
			$this->display();
		}
	}
	//关于我们
	public function about(){
		if(IS_POST){
			$info = I('post.info');
			$info['type'] = 2;
            $info['content'] = htmlentities($info['content']);
			D('System')->saveData($info) ? $this->success('保存成功!') : $this->error('保存失败！');
		}else {
			$info = D('System')->where(array('type'=>2))->find();
            $info['content'] = html_entity_decode($info['content']);
			$this->assign('info',$info);
			$this->display();
		}
	}
	/**
	 * 清空系统缓存
	 */
	public function cleanCache(){
		delFile('./Application/Runtime/Cache');// 模板缓存
		delFile('./Application/Runtime/Data');// 项目数据
		delFile('./Application/Runtime/Logs');// logs日志
		delFile('./Application/Runtime/Temp');// 临时数据
		delFile('./Application/Runtime');// 清除所有
		$url = session('cururl') ? session('cururl') : U('Admin/index');
		$this->success("操作完成!!!",$url);
	}

	/**
	 * 清空 文章静态页面缓存
	 */
	public function ClearAritcleHtml(){
		$article_id = I('article_id');
		unlink("./Application/Runtime/Html/Index_Article_detail_{$article_id}.html"); // 清除文章静态缓存
		unlink("./Application/Runtime/Html/Doc_Index_article_{$article_id}_api.html"); // 清除文章静态缓存
		unlink("./Application/Runtime/Html/Doc_Index_article_{$article_id}_phper.html"); // 清除文章静态缓存
		unlink("./Application/Runtime/Html/Doc_Index_article_{$article_id}_android.html"); // 清除文章静态缓存
		unlink("./Application/Runtime/Html/Doc_Index_article_{$article_id}_ios.html"); // 清除文章静态缓存
		$json_arr = array('status'=>1,'msg'=>'操作完成','result'=>'' );
		$json_str = json_encode($json_arr);
		exit($json_str);
	}
    //课程类型
	public function banner()
	{
		$where = array();
		$where['status'] = 1;
		$datapage = D('Banner')->getPager($where,10,'sort_index asc,create_date desc',true);
		$this->assign('list',$datapage['data']);
		$this->assign('page',$datapage['page']);
		$this->assign('request',I('request.'));
		$this->display();
	}
	//编辑课程类型
	public function edit_banner()
	{
		$act = I('post.act');
		if ($act == 'save')
		{
			$info = I('post.info');
			if ($info['id'] == '') unset($info['id']);
			if (!D('Banner')->create($info)) $this->error(D('Banner')->getError());
			if (D('Banner')->saveData($info)) 
			{
				$this->success('保存成功!');
			} 
			else 
			{
				$this->error('非法的操作！');
			}
		} 
		elseif($act == 'show')
		{
			$info = array();
			I('post.id') && $info = D('Banner')->where(array('id'=>(int)I('id')))->find();
			$this->assign('info',$info);
			$this->assign('default_sort',getMaxValue('Banner','sort_index')+1);
			$this->display();
		}
	}
	
	//删除课程类型
	public function dele_banner()
	{
		if(IS_POST){
			$id = (int)I('post.id');
			$id || $this->error('非法的操作');
			D('Banner')->where(array('id'=>$id))->delete() && $this->success('数据删除成功！');
			$this->error('网络异常，删除失败！');
		}
	}

    public function opt_log(){
        $where = array();
        $type = I('get.type') ? (int)I('get.type') : '1';
        $where['type'] = $type;
        $datapage = D('OptLog')->getPager($where,10,'create_date desc',true);
        $this->assign('list',$datapage['data']);
        $this->assign('page',$datapage['page']);
        $this->assign('type',$type);
        $this->display();
    }
}