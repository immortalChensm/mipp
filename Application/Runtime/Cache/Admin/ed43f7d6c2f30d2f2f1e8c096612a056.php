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
 

<!-- <link href="/Public/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
<script src="/Public/plugins/daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="/Public/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script> -->
<script src="/Public/js/ajaxfileupload.js" type="text/javascript"></script>
<style>
.group-list li{list-style-type: none;float:left;width:180px;height:30px;}
.group-list li input{margin-right:5px;}
.col-sm-8 .image-contain{width:60%;}
.col-sm-8 .image-contain .image-div{width:120px;height:120px;border:1px solid #ccc;float:left;margin-right: 4px;margin-bottom: 4px;position:relative;}
.col-sm-8 .image-contain .image-div .delete-img{width:100%;height:30px;bottom:0;cursor:pointer;background:#000;opacity:0.6;line-height:30px;text-align:center;opacity: 0.5;position: absolute;color:#fff;display:none;}
.col-sm-8 .image-contain #image_btn{background:url(/Public/Admin/images/default.png) no-repeat center;background-size:cover;cursor:pointer;}
.all_tags{width:80%;border:1px solid #ccc;border-radius:5px;padding:2px 8px;padding:5px 8px;min-height:50px;overflow:hidden;}
.all_tags span{float:left;padding:8px 12px !important;margin:3px 5px;}
.select_tags{width:80%;border:1px solid #ccc;border-radius:5px;padding:2px 8px;padding:5px 8px;min-height:50px;overflow:hidden;margin-top:10px;}
.select_tags span{float:left;padding:8px 12px !important;margin:3px 5px;}
</style>
<script type="text/javascript" src="/Public/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/Public/ueditor/ueditor.all.js"></script>
<script>
function myeditor(id){
    //初始化编辑器
    UE.getEditor(id, {
        toolbars: [['source','bold','italic','underline','fontsize','forecolor','backcolor','pasteplain',
        'insertorderedlist','cleardoc','link','simpleupload','emotion','map','inserttable','insertimage','insertvideo',
        //'insertimage',//多图上传
        ]],
        initialFrameWidth : '80%',
        initialFrameHeight: 450
    });
} 
</script>
<script>
myeditor('content');
</script>
<div class="wrapper">
    <div class="breadcrumbs" id="breadcrumbs">
	<ol class="breadcrumb">
	<?php if(is_array($navigate_admin)): foreach($navigate_admin as $k=>$v): if($k == '后台首页'): ?><li><a href="<?php echo ($v); ?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<?php echo ($k); ?></a></li>
	    <?php else: ?>    
	        <li><a href="<?php echo ($v); ?>"><?php echo ($k); ?></a></li><?php endif; endforeach; endif; ?>          
	</ol>
</div>

    <section class="content" style="padding:0px 15px;">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
            </div>
            <div class="panel panel-default">     
            	<div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $info ? '编辑课程信息' : '添加课程';?></h3>
                </div>      
                <div class="panel-body ">   
                    <!--表单数据-->
                    <form method="post" id="dataForm" >
                    <input type="hidden" name="info[id]" value="<?php echo ($info["id"]); ?>"/>    
                    <input type="hidden" name="info[teacher_id]" value="<?php echo ($info["teacher_id"]); ?>"/>    
                    <!--通用信息-->
                    <div class="tab-content" style="padding:20px 0px;">
                        <div class="tab-pane active" id="tab_tongyong">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="col-sm-2">课程名称：</td>
                                    <td class="col-sm-8">
                                        <input type="text" class="form-control" name="info[name]" value="<?php echo ($info["name"]); ?>" placeholder="请输入课程名称"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-2">课程类型：</td>
                                    <td class="col-sm-8">
                                        <select class="form-control" name="info[type]">
                                            <option value="">请选择课程类型</option>
                                            <?php if(is_array($types)): foreach($types as $key=>$val): ?><option value="<?php echo ($key); ?>" <?php echo $info['type'] == $key ? 'selected' :'';?>><?php echo ($val); ?></option><?php endforeach; endif; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-2">课程价格：</td>
                                    <td class="col-sm-8">
                                        <input type="number" class="form-control" name="info[price]" value="<?php echo ($info["price"]); ?>" placeholder="请输入课程价格"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-2">课程图片：</td>
                                    <td class="col-sm-8">
										<div  class="image-contain" >
											<?php if(is_array($info["pics"])): foreach($info["pics"] as $key=>$val): ?><div class="image-div" style="background:url(<?php echo ($val); ?>) no-repeat center;background-size:contain;">
												<div class="delete-img">删除</div>
												<input type="hidden" name="info[pics][]" value="<?php echo ($val); ?>"/>
											</div><?php endforeach; endif; ?>
											<div class="image-div" id="image_btn" onclick="$(this).next('input[name=upimg]').click()"></div>
											<input type="file" id="upimg" name="upimg" onchange="upload_img(this.id)" style="display:none;"/>
										</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-2">介绍视频：</td>
                                    <td class="col-sm-8">
                                        <input style="cursor:pointer" type="text" class="form-control" onclick="upVideos()" id="link" readonly name="info[link]" value="<?php echo ($info["link"]); ?>" placeholder="点击上传视频，视频大小最大为10M">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-2">课程简介：</td>
                                    <td class="col-sm-8">
                                        <textarea style="resize:none;height:80px;"  class="form-control" name="info[profile]"><?php echo ($info["profile"]); ?></textarea>
                                    </td>                                                                       
                                </tr>
                                <tr>
                                    <td class="col-sm-2">课程标签：</td>
                                    <td class="col-sm-8">
                                        <div class="all_tags">
                                        	<?php if(is_array($tags)): foreach($tags as $key=>$val): ?><span class="btn <?php echo in_array($val,$info['tags'])? 'btn-primary' : 'btn-default';?>"><?php echo ($val); ?></span><?php endforeach; endif; ?>
                                        </div>
                                        <div class="select_tags">
                                        	<?php if(is_array($info["tags"])): foreach($info["tags"] as $key=>$val): ?><span data-val="<?php echo ($val); ?>" class="btn btn-default"><?php echo ($val); ?>  <i class="fa fa-remove"></i></span><?php endforeach; endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-2">课程详情：</td>
                                    <td width="85%" class="col-sm-8">
                                        <textarea class="span12 ckeditor" id="content" name="info[content]" title=""><?php echo ($info["content"]); ?></textarea>
                                    </td>                                                                       
                                </tr>  
                                </tbody> 
                                <tfoot>
                                	<tr>
                                	<td><!-- <input type="hidden" name="inc_type" value="<?php echo ($inc_type); ?>"> --></td>
                                	<td class="text-right"><input class="btn btn-primary" type="button" onclick="save()" value="保存"></td></tr>
                                </tfoot>                               
                                </table>
                        </div>                           
                    </div>              
			    	</form><!--表单数据-->
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/plain" id="myEditor"></script>
<script type="text/plain" id="upload_ue"></script>
<script>
//重新实例化一个编辑器，防止在上面的editor编辑器中显示上传的图片或者文件
var _editor = UE.getEditor('upload_ue');
_editor.ready(function () {
    //设置编辑器不可用
    _editor.setDisabled();
    //隐藏编辑器，因为不会用到这个编辑器实例，所以要隐藏
    _editor.hide();
    //侦听图片上传
    _editor.addListener('beforeInsertImage', function (t, arg) {
        //将地址赋值给相应的input,只去第一张图片的路径
        $("#picture").attr("value", arg[0].src);
        //图片预览
        $("#preview").attr("src", arg[0].src);
    })
    //侦听文件上传，取上传文件列表中第一个上传的文件的路径
    _editor.addListener('afterUpfile', function (t, arg) {
        $("#file").attr("value", _editor.options.filePath + arg[0].url);
    })
});
//弹出图片上传的对话框
function upImage() {
    var myImage = _editor.getDialog("insertimage");
    myImage.open();
}
//弹出文件上传的对话框
function upFiles() {
    var myFiles = _editor.getDialog("attachment");
    myFiles.open();
}
//弹出视频上传的对话框
function upVideos() {
    var myFiles = _editor.getDialog("insertvideo");
    myFiles.open();
}
</script> 
<script>
$(function(){
	$('.image-contain').on('mouseenter','.image-div',function(){
		$(this).find('.delete-img').show();
	})
	$('.image-contain').on('mouseleave','.image-div',function(){
		$(this).find('.delete-img').hide();
	})
	$('.image-contain').on('click','.image-div>.delete-img',function(){
		$(this).parent().remove();
	})
	
	$('.all_tags>span').click(function(){
		if($(this).hasClass('btn-default')){
			var tag = $(this).text();
			$('.select_tags').append('<span data-val="'+tag+'" class="btn btn-default">'+tag+'  <i class="fa fa-remove"></i><input type="hidden" name="info[tags][]" value="'+tag+'"/></span>');
			$(this).removeClass('btn-default').addClass('btn-primary')
		}else{
			jsalert('该标签已选中',2);
		}
	})
	$('.select_tags').on('click','span',function(){
		var tag = $(this).attr('data-val');
		$(this).remove();
		$('.all_tags>.btn-primary').each(function(){
			if(tag == $(this).text()){
				$(this).removeClass('btn-primary').addClass('btn-default')
			}
		})
	})
})
function upload_img(ele_id){
	upload(ele_id,function(data){
		if(data.status == '1'){
	 		$('#image_btn').before('<div class="image-div" name="course_pic" style="background:url('+data.info.image_url+') no-repeat center;background-size:contain;"><div class="delete-img">删除</div><input type="hidden" name="info[pics][]" value="'+data.info.image_url+'"/></div>');
	 	}else{
	 		jsalert(data.msg,2);
	 	}
	});
}
function save(){
	$.post("<?php echo U('Course/edit');?>",$('#dataForm').serializeArray(),function(data){
		if(data.status == '1'){
			jsalert(data.info,1,function(){
				location.href = data.url;
			});
		}else{
			jsalert(data.info,2);
		}
	});
}
</script>
</body>
</html>