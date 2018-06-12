
function upload(ele_id,backfun){
	if(ele_id == null || ele_id == '') return false;
	$.ajaxFileUpload ({
		 url:'/Home/Upload/index',
		 secureuri:false,
		 fileElementId:ele_id,
		 dataType: 'json',
		 success: function(data,status){
			backfun(data);
		 }});
	 return false;
}