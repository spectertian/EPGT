<form id="myForm" method="get" action="" onsubmit="return mySubmit(true);">
    tvsou_id&nbsp;:&nbsp;&nbsp;<input type="text" name="tvsouid" value="<?php echo $tvsouId; ?>">
	&nbsp;维基名称&nbsp;：&nbsp;&nbsp;<input type="text" name="wikititle" value="<?php echo $wikiTitle; ?>">&nbsp;
	标题对比&nbsp;:
    <select name="compare">
		<option value ="1" <?php if($compare==1){echo "selected=\"selected\""; } ?>">全部</option>
    	<option value ="2" <?php if($compare==2){echo "selected=\"selected\""; } ?>">相同</option>
    	<option value ="3" <?php if($compare==3){echo "selected=\"selected\""; } ?>">不同</option>
	</select>	
	&nbsp;<input type="submit" value="查询">
</form>