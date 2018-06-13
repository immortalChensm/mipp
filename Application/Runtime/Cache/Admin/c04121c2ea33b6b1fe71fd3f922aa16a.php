<?php if (!defined('THINK_PATH')) exit();?><form id="dataForm">
<input type="hidden" name="info[id]" value="<?php echo ($info["id"]); ?>"/>
<input type="hidden" name="act" value="save"/>
<li>
	<div class="list_content" style="width:100px; text-align:right;">类型名称：</div>
	<div class="list_content" style="width:370px; text-align:left; padding-left:5px;padding-right:5px;">
	    <input type="text" class="list_text" style="width:340px;" name="info[name]" value="<?php echo ($info["name"]); ?>"/>
	</div>
</li>
<li>
	<div class="list_content" style="width:100px; text-align:right;">上传图片：</div>
	<div class="list_content" style="width:370px; text-align:left; padding-left:5px;padding-right:5px;">
		<?php if($info["pic"] != null): ?><img style="cursor:pointer;height:140px;" alt="" src="<?php echo ($info["pic"]); ?>" onclick="$(this).next().click()">
		<?php else: ?>
		<img style="cursor:pointer;height:140px;" alt="" src="/Public/Admin/images/default.png" onclick="$(this).next().click()"><?php endif; ?>
		<input style="display:none;" type="file" id="pic" name="pic" onchange="upload_img($(this).attr('id'),$(this).next('input').attr('id'))">
	    <input type="hidden" id="info_pic" name="info[pic]" value="<?php echo ($info["pic"]); ?>"/>
	</div>
</li>
<li>
	<div class="list_content" style="width:100px; text-align:right; padding-top:15px;">排序序号：</div>
	<div class="list_content" style="width:370px; text-align:left; padding-left:5px; padding-right:5px;">
	    <input type="text" class="list_text" style="width:340px;" name="info[sort_index]" value="<?php echo ((isset($info["sort_index"]) && ($info["sort_index"] !== ""))?($info["sort_index"]):$default_sort); ?>"/>
	</div>
</li>
</form>