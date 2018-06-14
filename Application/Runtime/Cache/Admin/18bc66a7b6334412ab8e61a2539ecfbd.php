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
 

<link href="/Public/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
<script src="/Public/plugins/daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="/Public/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script>
function iniDate(id){
    if(!id) return false;
    var date = $.trim($('id').val()) != '' ? $.trim($('id').val()) : new Date();
    $('#'+id).daterangepicker({
		format:"YYYY-MM-DD",
		singleDatePicker: true,
		showDropdowns: true,
		minDate:'2000-01-01',
		maxDate:'2050-01-01',
		startDate:date,
		//endDate:date,
	    locale : {
            applyLabel : '确定',
            cancelLabel : '取消',
            fromLabel : '起始时间',
            toLabel : '结束时间',
            customRangeLabel : '自定义',
            daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
            monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月','七月', '八月', '九月', '十月', '十一月', '十二月' ],
            firstDay : 1
        }
	});
}
</script>
<script>
window.onload = function(){
    iniDate('start_date');
    iniDate('end_date');
}
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
				          <form class="navbar-form form-inline" action="<?php echo U('Order/index');?>" method="get">
				            <div class="form-group">
				              	<input type="text" name="keywords" class="form-control" placeholder="订单号" value="<?php echo ($request_data["keywords"]); ?>">
				              	<select name="status" class="form-control">
				              	<option value="">订单状态</option>
				              	<option value="1" <?php echo $request_data['status'] == 1 ? 'selected':'';?> >已支付</option>
				              	<option value="2" <?php echo $request_data['status'] == 2 ? 'selected':'';?> >待支付</option>
				              	<option value="3" <?php echo $request_data['status'] == 3 ? 'selected':'';?> >已关闭</option>
				              	</select>
				              	<input type="text" id="start_date" name="begin_date" class="form-control" placeholder="开始日期" value="<?php echo I('begin_date');?>"> ~ 
				              	<input type="text" id="end_date" name="end_date" class="form-control" placeholder="结束日期" value="<?php echo I('end_date');?>">
				            </div>
				            <button type="submit" class="btn btn-default">搜索</button>
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
			                   <th>订单号</th>
			                   <th>课程名称</th>
			                   <th>授课老师</th>
			                   <th>学生</th>
							   <th>手机号</th>
							   <th>价格</th>
							   <th>状态</th>
			                   <th>下单时间</th>
			                   <th>操作</th>
		                   </tr>
		                 </thead>
						<tbody>
						  <?php if(is_array($list)): foreach($list as $k=>$vo): ?><tr role="row" align="center">
		                     <td><?php echo ($vo["id"]); ?></td>
		                     <td><?php echo ($vo["order_sn"]); ?></td>
		                     <td><?php echo ((isset($vo["course"]["name"]) && ($vo["course"]["name"] !== ""))?($vo["course"]["name"]):'--'); ?></td>
							 <td><?php echo ((isset($vo["teacher"]["name"]) && ($vo["teacher"]["name"] !== ""))?($vo["teacher"]["name"]):'--'); ?></td>
							 <td><?php echo ((isset($vo["name"]) && ($vo["name"] !== ""))?($vo["name"]):'--'); ?></td>
		                     <td><?php echo ((isset($vo["phone"]) && ($vo["phone"] !== ""))?($vo["phone"]):'0'); ?></td>
		                     <td><?php echo ($vo["price"]); ?></td>
		                     <?php if($vo["status"] == 1): ?><td style="color:#25be13;">已付款</td>
		                     <?php elseif($vo["status"] == 2): ?>
		                     <td style="color:#f39c12;">待付款</td>
		                     <?php elseif($vo["status"] == 3): ?>
		                     <td style="color:#aaa;">已关闭</td><?php endif; ?>
		                     <td><?php echo ($vo["create_date"]); ?></td>
		                     <td>
		                       <a title="订单详情" class="btn btn-primary" href="javascript:void(0)" onclick="view_detail('<?php echo ($vo["id"]); ?>')"><i class="fa fa-eye"></i></a>
		                       <?php if($vo["status"] < 3): ?><a title="关闭订单" class="btn btn-danger" href="javascript:void(0)" onclick="close_order('<?php echo ($vo["id"]); ?>')"><i class="fa fa-power-off"></i></a><?php endif; ?>
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
<!-- dialog_begin -->
<style>
/*弹窗*/
.dialog{ background:#FFF;-moz-border-radius:3px; -webkit-border-radius:3px; border-radius:3px;box-shadow: 1px 1px 2px #BBBBBB;}
.dialog_top{ height:34px; width:auto; line-height:34px; border-bottom:1px solid #e7e7e7; background:#f6f6f6;-moz-border-radius:3px 3px 0px 0px; -webkit-border-radius:3px 3px 0px 0px; border-radius:3px 3px 0px 0px;}
.dialog_top_left{ float:left; line-height:34px; color:#666; font-size:14px; padding-left:10px;font-weight:bold;}
.dialog_top_right{ float:right; padding-right:10px; font-size:14px;}

.list_content_box ul{width:100%;padding:0!important;}
.list_content_box ul li{width:100%;list-style:none;float:left;/*min-height:50px;*/ border-bottom:1px solid #ebebeb;}
.list_content_box ul li:hover{ background:#fbfbfb;}
.list_content_box ul .image_list{float:left;width:150px;height:150px;margin-left:12px;margin-top:8px;cursor:pointer;}
.list_content{ float:left; font-size:14px; text-align:center; padding-top:10px; padding-bottom:10px; color:#565656;}
.list_content img{ border:1px solid #ccc;}
.list_content select{height:30px;}
.list_text{border:solid 1px #ccc;border-radius:3px;padding-left:5px;}
.list_text[type=text]{height:30px;}
.dialog-btn{padding: 4px 20px;border:1px solid #337ab7;cursor: pointer;background: none repeat scroll 0% 0% #337ab7;border-radius: 5px;color: #FFF;font-family: "Microsoft YaHei","黑体","宋体";font-size: 14px;}
</style>
<div id="dialog" style="display:none;">
    <div class="dialog" style="width:500px;">
        <div class="dialog_top">
            <div class="dialog_top_left" id="logtit">信息</div>
<!--             <div class="dialog_top_right"> -->
<!--                 <a href="javascript:void(0)" title="关闭窗口" onclick="close_box()">关闭</a> -->
<!--             </div> -->
        </div>
        <div class="dialog_box">
            <div class="mainlist">
                <div class="list_content_box">
                    <ul id="dialogHtml">
                    
                    </ul>
                </div>
                <div class="list_content" style="width:100%; padding-top:15px; padding-bottom:15px;">
                	<span class="dialog-btn" id="dialog_btn">确定</span>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
    </div>
</div>
<script>
function dialog(html,title,backfun){
	$('#logtit').text(title);
	$("#dialogHtml").html(html);
	$('#dialog_btn').unbind('click');//先解除其他的点击事件绑定
	if(backfun == null){
		$('#dialog_btn').bind('click',function(){layer.closeAll();});
	}else{
		$('#dialog_btn').bind('click',backfun);
	}
    var close = function(){
        $('#dialogHtml').empty();
    }
    layer.open({
	    type:1,
	    skin: 'layui-layer-demo', //样式类名
	    closeBtn: 1, //不显示关闭按钮
	    title:false,
	    shift: 1,
	    shadeClose: false, //开启遮罩关闭
	    area:['500px','auto'],
	    shade:'#fff',
	    content:$("#dialog"),
        cancel :close
	});
}
</script>
<!-- dialog_end -->
<script>
function view_detail(id){
	$.post("<?php echo U('Order/detail');?>",{id:id},function(res){
		dialog(res,'订单详情');
	});
}
function close_order(id){
	jsconfirm('确定要关闭此订单吗？',function(){
		$.post("<?php echo U('Order/close_order');?>",{id:id},function(res){
			if(res.status == '1') {
				location.reload();
				return false;
			}else{
				jsalert(res.info,2);
			}
		});
	});
}
</script>
</body>
</html>