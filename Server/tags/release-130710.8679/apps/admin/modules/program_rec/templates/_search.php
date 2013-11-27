<script>
function getUrl(arg)
{
	var timeArea = arg.val();
	location.href = "program_rec?name=<?php echo $name; ?>&date=<?php echo $date; ?>&timeArea="+timeArea;
}
function getBranchUrl(arg)
{
	var timeSon = arg.val();
	location.href = "program_rec?name=<?php echo $name; ?>&date=<?php echo $date; ?>&timeArea=<?php echo $timeArea?>&timeSon="+timeSon;
}
</script>
<form id="myForm" method="get" action="">
	选择按时间段查询&nbsp;:
    <select name="timeArea" onchange="getUrl($(this));">
	    <?php $timeConfig = sfConfig::get("app_rec_time_area")?>
	    <?php foreach( $timeConfig as $k => $v){?>
			<option value ="<?php echo $k ?>" <?php if($k == $timeArea){echo "selected=\"selected\""; } ?>"><?php echo $v ?></option>
		<?php }?>
		</select>
		&nbsp;&nbsp;子时间段查询&nbsp;:
    <select name="timeSon" onchange="getBranchUrl($(this));">
	    <?php $timeBlock = sfConfig::get("app_rec_time_block")?>
	    <?php $timeBlockConfig = $timeBlock[$timeArea]?>
	    <?php foreach( $timeBlockConfig as $k1 => $v1){?>
			<option value ="<?php echo $k1 ?>" <?php if($k1 == $timeSon){echo "selected=\"selected\""; } ?>"><?php echo $v1 ?></option>
		<?php }?>
		</select>
		<input type="text" name="name" value="<?php echo $name; ?>">
	<input type="hidden" name="date" value="<?php echo $date; ?>">			
	&nbsp;<input type="submit" value="查询">
</form>