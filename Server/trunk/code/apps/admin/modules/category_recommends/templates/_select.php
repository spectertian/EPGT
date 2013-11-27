<form id="myForm" method="get" action="" onsubmit="return mySubmit(true);">
    名称&nbsp;:<input type="text" name="name" value="<?php echo $name; ?>">
	&nbsp;分类标签&nbsp;：<input type="text" name="category" value="<?php echo $category; ?>">
	&nbsp;起始时间&nbsp;：<input type="button" name="re_start_time"  maxlengtjh="10"  value="<?php echo $start_time?$start_time:'起始日期'; ?>" class="datepicker_s">&nbsp;——&nbsp;
	&nbsp;结束时间&nbsp;：<input type="button" name="re_end_time"   maxlengtjh="10"  value="<?php echo $end_time?$end_time:'结束日期'; ?>" class="datepicker_e">&nbsp;&nbsp;<a href='#' onclick='cleardate()'>重置</a>
	<input id="re_start_time" type="hidden" name="start_time" value="<?php echo $start_time; ?>">
	<input id="re_end_time" type="hidden" name="end_time" value="<?php echo $end_time; ?>">					
	&nbsp;<input type="submit" value="查询">
</form>