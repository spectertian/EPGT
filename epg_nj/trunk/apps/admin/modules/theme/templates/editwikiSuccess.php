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
            <?php include_partial('toolbarListwiki',array('theme'=>$theme,'id'=>$theme->getId(),'add'=>false))?>
            <?php include_partial('global/flashes') ?> 
			<div class="table_nav">
				<form method="POST" action="<?php echo url_for('theme/editwiki?id='.$theme->getId())?>">				
				 <div class="widget-body">
				   <ul class="wiki-meta">
					<li style="z-index: 100;"><label>WIKI名称：</label><input name="wiki_name" id="wiki_name"  value="<?php echo $wiki_title?>" type="text"><input name="wiki_id" id="wiki_id"  value="<?php echo $theme_item->getWikiId();?>" type="hidden"></li>
					<li><label>推荐理由：</label><textarea cols="90" rows="3" name="remark"><?php echo $theme_item->getRemark();?></textarea></li>
                    <li><label>图片：</label><a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=themeitemAdds">上传剧照</a>
                        <?php if($theme_item->getImg()):?>  
                        <br /><img style="" id="tupian" src="<?php echo file_url($theme_item->getImg());?>" alt="加载中..." width="100"/> 
						<?php endif;?>
                    <input name="img" id="img"  value="<?php echo $theme_item->getImg();?>" type="hidden"></li>
					<li><input name="item_id" id="item_id"  value="<?php echo $theme_item->getId();?>" type="hidden"><input type="submit" value="修改"></li>
                    <ul id="right">
		            </ul> 
				  </ul>
				</div>
				</form>
			</div>
            <div class="clear"></div>
        </div>
      </div>