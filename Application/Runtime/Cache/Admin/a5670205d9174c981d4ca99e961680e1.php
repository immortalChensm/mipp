<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>安聘通管理后台</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link href="/Public/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- FontAwesome 4.3.0 -->
 	<link href="/Public/bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.0 --
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="/Public/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
    	folder instead of downloading all of them to reduce the load. -->
    <link href="/Public/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="/Public/plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />   
    <link href="/Public/plugins/common/common.css" rel="stylesheet" type="text/css" />
    <!-- jQuery 2.1.4 -->
    <script src="/Public/plugins/jQuery/jQuery-2.1.4.min.js"></script>
	<script src="/Public/js/global.js"></script>
    <script src="/Public/js/myFormValidate.js"></script>    
    <script src="/Public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/Public/js/layer/layer-min.js"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
    <script src="/Public/js/myAjax.js"></script>
    <script src="/Public/Admin/js/common.js"></script>
    <style>
    	.btn-power{background:#F39C12;border-color:#F39C12;color:#fff;}
		#tab_tongyong tbody tr .col-sm-2{text-align:right;}
		#tab_tongyong tbody tr .col-sm-8{text-align:left;}
		#tab_tongyong tbody tr .col-sm-8 .form-control{width:50%;}
		#tab_tongyong tbody tr label{margin-left:10px;font-weight:normal;cursor:pointer;}
		#tab_tongyong tbody tr .col-sm-8 .input-group{width:50%;margin-bottom:10px;}
		#tab_tongyong tbody tr .col-sm-8 .input-group input{width:50%;}
		#tab_tongyong tbody tr .col-sm-8 .input-group span{cursor:pointer;}
		.comment-table{border:none;width:100%;}
		.comment-table tr:hover{background:#eee;}
		.comment-table .comment-user{font-weight:bold;text-align:right;width:25%;}
		.comment-table .comment-con{text-align:left;width:75%;}
		.comment-table .comment-con .qdate{margin-left:10px;color:#999;}
		.comment-table .comment-opt {color:#999;width:10%;}
		.comment-table .comment-opt i{cursor:pointer;margin-right:10px;}
		#list-table tr td input[type=text]{border:1px solid #eee;border-radius:3px;text-align:center;}
</style>
    <script type="text/javascript">
    function delfunc(obj){
    	layer.confirm('确认删除？', {
    		  btn: ['确定','取消'] //按钮
    		}, function(){
    		    // 确定
   				$.ajax({
   					type : 'post',
   					url : $(obj).attr('data-url'),
   					data : {act:'del',del_id:$(obj).attr('data-id')},
   					dataType : 'json',
   					success : function(data){
   						if(data==1){
   							layer.msg('操作成功', {icon: 1});
   							$(obj).parent().parent().remove();
   						}else{
   							layer.msg(data, {icon: 2,time: 2000});
   						}
   					}
   				})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }
    
    function selectAll(name,obj){
    	$('input[name*='+name+']').prop('checked', $(obj).checked);
    }   
    
    function get_help(obj){
        layer.open({
            type: 2,
            title: '帮助手册',
            shadeClose: true,
            shade: 0.3,
            area: ['70%', '80%'],
            content: $(obj).attr('data-url'), 
        });
    }
    </script>        
  </head>
  <body style="background-color:#ecf0f5;">
 

<script>
function export_order(){
    var keyword = $.trim($('input[name=keyword]').val());
    var phone = $.trim($('input[name=phone]').val());
    var sex = $.trim($('input[name=sex]').val());
    location.href="<?php echo U('Export/enroll');?>?keyword="+keyword+"&phone="+phone+"&sex="+sex;
}
</script>
<div class="wrapper">
    <div class="breadcrumbs" id="breadcrumbs">
	<ol class="breadcrumb">
	<?php if(is_array($navigate_admin)): foreach($navigate_admin as $k=>$v): if($k == '后台首页'): ?><li><a href="<?php echo ($v); ?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<?php echo ($k); ?></a></li>
	    <?php else: ?>    
	        <li><a href="<?php echo ($v); ?>"><?php echo ($k); ?></a></li><?php endif; endforeach; endif; ?>          
	</ol>
</div>

	<section class="content">
       <div class="row">
       		<div class="col-xs-12">
	       		<div class="box">
	             <div class="box-header">
	             	<ul class="nav nav-tabs" style="margin-bottom:10px;">
    <?php if($admin_info["type"] == 2): ?><li name="edit"><a href="<?php echo U('Teacher/edit',array('id'=>$admin_info.teacher_id));?>">编辑教师信息</a></li>
    <?php else: ?>
    <li name="teacher"><a href="<?php echo U('Teacher/index');?>">教师列表</a></li>                           
<!--     <li name="teacher_type"><a href="<?php echo U('Teacher/teacher_type');?>">教师类型</a></li>                            -->
    <li name="teach_type"><a href="<?php echo U('Teacher/teach_type');?>">授课类型</a></li><?php endif; ?>
</ul>
<script>
$(function(){
	var act_nav = 'teacher';
	$('.nav-tabs').find('li[name='+act_nav+']').addClass('active');
})
</script>
	               	<nav class="navbar navbar-default">	     
				        <div class="collapse navbar-collapse">
				          <form class="navbar-form form-inline" action="<?php echo U('Admin/Teacher/index');?>" method="get">
				            <div class="form-group">
				              	<input type="text" name="keywords" class="form-control" placeholder="姓名/手机号/昵称" value="<?php echo I('keywords');?>">
				              	<select name="teach_type" class="form-control">
				              	<option value="">授课类型</option>
				              	<?php if(is_array($teach_types)): foreach($teach_types as $key=>$val): ?><option value="<?php echo ($key); ?>" <?php if($request["teach_type"] == $key): ?>selected<?php endif; ?> ><?php echo ($val); ?></option><?php endforeach; endif; ?>
				              	</select>
				              	<select name="sex" class="form-control">
				              	<option value="">性别</option>
				              	<option value="1" <?php if($request["sex"] == 1): ?>selected<?php endif; ?> >男</option>
				              	<option value="2" <?php if($request["sex"] == 2): ?>selected<?php endif; ?>>女</option>
				              	</select>

				            </div>
				            <button type="submit" class="btn btn-default">搜索</button>
				            <!-- <div class="form-group pull-right">
				              <a href="javascript:void(0)"  style="margin-left:6px;"  onclick="export_order()" class="btn btn-primary pull-right"><i class="fa fa-upload"></i> 导出报名列表</a>
				            </div> -->		          
				          </form>		
				      	</div>
	    			</nav>
	             </div>	             
	             <div class="box-body">	               
	           		<div class="row">
	            	<div class="col-sm-12">
		              <table id="list-table" class="table table-bordered table-striped dataTable">
		                 <thead>
		                   <tr role="row">
			                   <th>ID</th>
			                   <th>头像</th>
			                   <th>昵称</th>
			                   <th>姓名</th>
			                   <th>性别</th>
			                   <th>手机号</th>
							   <th>授课类型</th>
							   <th>是否推荐</th>
			                   <th>加入时间</th>
			                   <th>操作</th>
		                   </tr>
		                 </thead>
						<tbody>
						  <?php if(is_array($list)): foreach($list as $k=>$vo): ?><tr role="row" align="center">
		                     <td><?php echo ($vo["id"]); ?></td>
		                     <td><img src="<?php echo ($vo["headimgurl"]); ?>" style="width:50px; height:50px;"></td>
		                     <td><?php echo ($vo["nickname"]); ?></td>
		                     <td><?php echo ($vo["name"]); ?></td>
		                     <td><?php if($vo["sex"] == 1): ?>男<?php elseif($vo["sex"] == 2): ?>女<?php else: ?>未知<?php endif; ?></td>
							 <td><?php echo ($vo["phone"]); ?></td>
		                     <td><?php echo $teach_types[$vo['teach_type']];?></td>
		                     <?php if($vo["is_stick"] == 1): ?><td><img style="cursor:pointer;" width="20" height="20" src="/Public/images/yes.png" onclick="set_stick(this,'<?php echo ($vo["id"]); ?>','1')"/>  </td>
		                     <?php else: ?>
		                     <td><img style="cursor:pointer;" width="20" height="20" src="/Public/images/cancel.png" onclick="set_stick(this,'<?php echo ($vo["id"]); ?>','2')"/>  </td><?php endif; ?>
		                     <td><?php echo ($vo["check_time"]); ?></td>
		                     <td>
							  <?php if($vo["has_account"] != null): ?><a title="越权登录" class="btn btn-primary" href="<?php echo U('Admin/over_login',array('teacher_id'=>$vo['id']));?>" ><i class="fa fa-user"></i></a>
							  <?php else: ?>
		                      <a title="添加账号" class="btn btn-warning" href="<?php echo U('Admin/add_adminer',array('teacher_id'=>$vo['id']));?>" ><i class="fa fa-user-plus"></i></a><?php endif; ?>
		                      <a title="编辑信息" class="btn btn-primary" href="<?php echo U('Teacher/edit',array('id'=>$vo['id']));?>"><i class="fa fa-pencil"></i></a>
		                      <a title="注销账号" class="btn btn-danger" href="javascript:void(0)" data-id="<?php echo ($vo["id"]); ?>" onclick="dele_teacher('<?php echo ($vo["id"]); ?>')"><i class="fa fa-trash-o"></i></a>
							 </td>
		                   </tr><?php endforeach; endif; ?>
		                   </tbody>
		                 <tfoot>		                 
		                 </tfoot>
		               </table>	 
	               </div>
	          </div>
              <div class="row">
              	    <div class="col-sm-6 text-left"></div>
                    <div class="col-sm-6 text-right"><?php echo ($page); ?></div>		
              </div>
	         </div>
	        </div>
       	</div>
       </div>
   </section>
</div>
<script>
//设置推荐
function set_stick(obj,id,is_stick){
	$.post("/Admin/Teacher/set_stick",{id:id,is_stick:is_stick},function(data){
		if(is_stick == '1'){
			$(obj).attr('src','/Public/images/cancel.png');
		}else{
			$(obj).attr('src','/Public/images/yes.png');
		}
		location.reload();
	});
}

//注销老师账号
function dele_teacher(id){
	jsconfirm('确认要注销此账号吗？',function(){
		$.ajax({
			type : 'post',
			url : '/Admin/Teacher/dele_teacher',
			data : {id:id},
			dataType : 'json',
			success : function(data){
				if(data.status == '1'){
					jsalert(data.info,1,function(){
						location.reload();
					});
				}else{
					jsalert(data.info,2);
				}
			}
		})
	});
	return false;
}
</script> 
</body>
</html>