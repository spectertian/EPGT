<form id="myForm" method="get" action="" onsubmit="return mySubmit(true);">
    选择类型&nbsp;:
    <select name="condition">
    	<option value ="program" <?php if($condition=='program'){echo "selected=\"selected\""; } ?>">节目名称</option>
		<option value ="channel" <?php if($condition=='channel'){echo "selected=\"selected\""; } ?> ">频道名称</option>
	</select>
    
	&nbsp;名称&nbsp;：<input type="text" name="name" value="<?php echo $name; ?>">
					<input type="hidden" name="date" value="<?php echo $date; ?>">			
	&nbsp;<input type="submit" value="查询">
</form>