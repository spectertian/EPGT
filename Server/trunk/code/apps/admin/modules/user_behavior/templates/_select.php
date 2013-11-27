<form id="myForm" method="get" action="" >
	用户名称&nbsp;：<input type="text" name="userName" value="<?php echo $userName; ?>">
    访问时间&nbsp;：<input type="text" name="date1" value="<?php echo $date1; ?>">-<input type="text" name="date2" value="<?php echo $date2; ?>">
	&nbsp;访问的页面&nbsp;：<input type="text" name="access" value="<?php echo $access; ?>">&nbsp;<font color='red'>例子:user_behavior/index</font>				
	&nbsp;<input type="submit" value="查询">
</form>