<form id="myForm" method="get" action="" onsubmit="return mySubmit(true);">
    wikiId&nbsp;:&nbsp;&nbsp;<input type="text" name="wikiid" value="<?php echo $wikiId; ?>">
	&nbsp;节目名称&nbsp;：&nbsp;&nbsp;<input type="text" name="wikiname" value="<?php echo $wikiName; ?>">&nbsp;
	抓取状态&nbsp;:
    <select name="state">
		<option value ="3" <?php if($state==3){echo "selected=\"selected\""; } ?>">全部</option>
    	<option value ="1" <?php if($state==1){echo "selected=\"selected\""; } ?>">抓取中</option>
    	<option value ="2" <?php if($state==2){echo "selected=\"selected\""; } ?>">抓取完成</option>
	</select>	
	&nbsp;<input type="submit" value="查询">
</form>