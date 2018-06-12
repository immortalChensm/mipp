/*弹出信息*/
function jsalert(msg,type,backfun){
	type = type ? type : 1;
	var layer_index =  layer.alert(msg, {icon: type});
	setTimeout(function(){
		if(backfun != null && backfun != 'undefined' && backfun != ''){
			backfun();
		}else{
			layer.close(layer_index);
		}
		
	},1500);
}
/*弹出确认框*/
function jsconfirm(msg,surefun,btntext1,btntext2){
	if(msg == undefined || msg == '') return false;
	if(surefun == undefined) surefun = function(index){
		layer.close(index);
		return false;
	}
	btntext1 = btntext1 ? btntext1 : '确定';
	btntext2 = btntext2 ? btntext2 : '取消';
	layer.confirm(msg, {
		  btn: [btntext1,btntext2] //按钮
		},surefun, function(index){
			layer.close(index);
			return false;// 取消
		}
	);
}
function upload(ele_id,backfun){
	if(ele_id == null || ele_id == '') return false;
	$.ajaxFileUpload ({
		 url:'/Admin/Upload/index',
		 secureuri:false,
		 fileElementId:ele_id,
		 dataType: 'json',
		 success: backfun});
	 return false;
}
function upload_vdo(ele_id,backfun){
	if(ele_id == null || ele_id == '') return false;
	$.ajaxFileUpload ({
		 url:'/Admin/Upload/upvideo',
		 secureuri:false,
		 fileElementId:ele_id,
		 success: backfun});
	 return false;
}
function upload_file(ele_id,backfun){
	if(ele_id == null || ele_id == '') return false;
	$.ajaxFileUpload ({
		 url:'/Admin/Upload/upfile',
		 secureuri:false,
		 fileElementId:ele_id,
		 dataType: 'json',
		 success: backfun});
	 return false;
}