<script language="javascript">

$(document).ready(function(){
    $('#wiki_name').simpleAutoComplete('<?php echo url_for('theme/loadWiki') ?>',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'wiki_id',
        max       : 20
    },function(date){
        var date = eval("("+date+")");
        var id = date.id;
        $('#wiki_id').attr('value',id);
    });
});

</script>
<?php include_partial("wiki/screenshots"); ?>
    <div id="content">
        <div class="content_inner">
            <?php include_partial('toolbarListwiki',array('theme'=>$theme,'id'=>$theme->getId(),'add'=>true))?>
            <?php include_partial('global/flashes') ?> 
			<div class="table_nav">
				<form method="POST" action="<?php echo url_for('theme/addwiki?id='.$theme->getId())?>">				
				 <div class="widget-body">
				   <ul class="wiki-meta">
					<li style="z-index: 100;"><label>WIKI名称：</label><input name="wiki_name" id="wiki_name"  value="" type="text"><input name="wiki_id" id="wiki_id"  value="" type="hidden"></li>
					<li><label>推荐理由：</label><textarea cols="90" rows="3" name="remark"></textarea></li>
                    <li><label>图片：</label><a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=themeitemAdds">上传剧照</a><input name="img" id="img"  value="" type="hidden"></li>
					<li><input type="submit" value="保存"></li>
                    <ul id="right">
		            </ul> 
				  </ul>
				</div>
				</form>
			</div>
            <div class="clear"></div>
        </div>
      </div>