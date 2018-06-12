/***********
 * 提示气泡
 * @example:  
 ***********/
function note_info(msg,obj,type){
	var sel = '#'+$(obj).attr('id');
	var color = type=='warn'?'#F58323':'#0099cb';
	var frame = layer.tips(msg, sel, {
	    tips: [1, color] //还可配置颜色
	})
	setTimeout(function(){
		var index = layer.getFrameIndex(window.frame);
		layer.close(index);
	},1000);
}