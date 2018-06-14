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
	               	<nav class="navbar navbar-default">	     
				        <div class="collapse navbar-collapse">
				          <form class="navbar-form form-inline" action="<?php echo U('Course/apply_list');?>" method="get">
				            <div class="form-group">
				              	<input type="text" name="keywords" class="form-control" placeholder="课程名称" value="<?php echo I('keywords');?>">
				              	<select name="type" class="form-control">
				              	<option value="">课程类型</option>
				              	<?php if(is_array($course_types)): foreach($course_types as $key=>$val): ?><option value="<?php echo ($key); ?>" <?php if($request["type"] == $key): ?>selected<?php endif; ?> ><?php echo ($val); ?></option><?php endforeach; endif; ?>
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
			                   <th>图片</th>
			                   <th>老师</th>
			                   <th>名称</th>
			                   <th>类型</th>
			                   <th>价格</th>
			                   <th>审核状态</th>
			                   <th>添加时间</th>
			                   <th>审核</th>
		                   </tr>
		                 </thead>
						<tbody>
						  <?php if(is_array($list)): foreach($list as $k=>$vo): ?><tr role="row" align="center">
		                     <td><?php echo ($vo["id"]); ?></td>
		                     <td><img src="<?php echo ($vo['pics'][0]); ?>" style="height:50px;"></td>
		                     <td><?php echo ($vo["teacher"]["name"]); ?></td>
		                     <td><?php echo ($vo["name"]); ?></td>
		                     <td><?php echo $course_types[$vo['type']];?></td>
							 <td><?php echo ($vo["price"]); ?></td>
							 <?php if($vo["status"] == 4): ?><td style="color:red;">审核不通过</td>
		                     <?php elseif($vo["status"] == 3): ?>
		                     <td style="color:orange;">待审核</td><?php endif; ?>
		                     <td><?php echo ($vo["create_date"]); ?></td>
		                     <td>
		                      <a title="审核通过" class="btn btn-primary" href="javascript:void(0)" onclick="check_course('<?php echo ($vo["id"]); ?>',1)">通过</a>
		                      <?php if($vo["status"] == 3): ?><a title="审核不通过" class="btn btn-danger" href="javascript:void(0)" onclick="check_course('<?php echo ($vo["id"]); ?>',4)">不通过</a><?php endif; ?>
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
function check_course(id,status){
	$.post("<?php echo U('Course/check_course');?>",{id:id,status:status},function(res){
		if(res.status == '1'){
			jsalert(res.info,1,function(){
				location.reload();
			});
		}else{
			jsalert(res.info,2);
		}
	});
}
</script> 
</body>
</html>