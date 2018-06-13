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

<script src="/Public/Admin/js/jquery.animsition.min.js"></script>
<link rel="stylesheet" type="text/css" href="/Public/Admin/css/admin1.css"/>
<script src="https://cdn.bootcss.com/echarts/3.5.3/echarts.min.js"></script>
<script>
$(document).ready(function(){
    //初始化切换
    $(".animsition").animsition({
      
        inClass               :   'fade-in-right',
        outClass              :   'fade-out',
        inDuration            :    1500,
        outDuration           :    800,
        linkElement           :   '.animsition-link',
        // e.g. linkElement   :   'a:not([target="_blank"]):not([href^=#])'
        loading               :    true,
        loadingParentElement  :   'body', //animsition wrapper element
        loadingClass          :   'animsition-loading',
        unSupportCss          : [ 'animation-duration',
                                  '-webkit-animation-duration',
                                  '-o-animation-duration'
                                ],
        //"unSupportCss" option allows you to disable the "animsition" in case the css property in the array is not supported by your browser.
        //The default setting is to disable the "animsition" in a browser that does not support "animation-duration".
        
        overlay               :   false,
        
        overlayClass          :   'animsition-overlay-slide',
        overlayParentElement  :   'body'
    });
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main_chart'),'macarons');
    // 指定图表的配置项和数据
    var date =  JSON.parse('<?php echo ($num_time); ?>');
    var my_data = JSON.parse('<?php echo ($num_value); ?>');
    var option = {
        tooltip: {
            trigger: 'axis',
            /*formatter: function(data){
                //console.log(data)
                 myChart.setOption({
                    title : {
                        text: '会员数量' + data.value,
                    }
                });
                
               return data.name + '</br>' + '新增数：' + data.value + '</br>';
            }*/
            
        },
        title: {
            text: '教师统计',
            /*subtext: '昨日新增：',
            subtextStyle: {
                fontSize:14,
                color : '#ff4a84'
            }*/
        },
        
        toolbox: {
            show : true,
             feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: true},
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                saveAsImage : {}
            }
        },
        calculable : true,
        xAxis: {
            type : 'category',
            boundaryGap : false,
            data : date
/*          axisLabel: {
                interval : 0,//横轴信息全部显示
                rotate : 60,//60度角倾斜显示
                 formatter:function(val){
                    return val.split("").join("\n"); //横轴信息文字竖直显示
                }
            }*/
        },
        yAxis: {
            type: 'value',
            boundaryGap: [0, '100%']
        },
        dataZoom: [{
            type: 'inside',
            start: 0,
            end: 100
        }, {
            start: 0,
            end: 100,
            handleSize: '80%',
            handleStyle: {
                color: '#fff',
                shadowBlur: 3,
                shadowColor: 'rgba(0, 0, 0, 0.6)',
                shadowOffsetX: 2,
                shadowOffsetY: 2
            }
        }],
        series: [
            {
                name:'新增数',
                type:'line',
                smooth:true,
                itemStyle: {
                    normal: {
                        color: 'rgb(255, 70, 131)'
                    }
                },
                areaStyle: {
                    normal: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                            offset: 0,
                            color: 'rgb(255, 158, 68)'
                        }, {
                            offset: 1,
                            color: 'rgb(255, 70, 131)'
                        }])
                    }
                },
                data : my_data
            }
        ]
    };
    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
    myChart.on('mousemove',function(params){ // 控制台打印数据的名称 
        $('#my_date_set1').html(params.name);
    });
})
</script>
<script type="text/javascript">
window.onload = function(){
    iniDate('begin_date');
    iniDate('end_date');
}
</script>
<div class="bees_main_right">
    <div class="bees_main_content">
		<div class="order_contain" style="border:none">
			<p class="main_title">教师统计</p>
			<form method="get" id="dataForm" action="<?php echo U('Statistic/teacher_data');?>">
				<div class="controller">
				    <div style="padding:20px 0;border-bottom:1px solid #a6a8ab;">
				        <input type="text" placeholder="请选择开始时间" value="<?php echo ($request_data["begin_date"]); ?>" class="time_btn" id="begin_date" name="begin_date" />
				        <input type="text" placeholder="请选择结束时间" value="<?php echo ($request_data["end_date"]); ?>" class="time_btn" id="end_date" name="end_date" />
				        <input type="submit"  value="筛选" class="ct_btn" />
				        <p style="clear:both;"></p>
				    </div>
				</div>
			</form>
			<div class="animsition" style="padding:60px 40px;">
			    <div id="main_chart" style="height:448px;width:100%;border:1px solid #909090;"></div>
			</div>
		</div>
	</div>
</div>  
<div class="clear"></div>
</body>
</html>