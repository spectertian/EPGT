<script type="text/javascript">
//全选
function checkAll()
{
    var flag    = $("#sf_admin_list_batch_checkbox").attr('checked');
    var box     = $("input[type=checkbox]");
    if (flag) {
        box.attr('checked',true);
    }else{
        box.attr('checked',false);
    }

}
function submitform(action){
    if (action) {
        document.adminForm.batch_action.value=action;//add
    }
    if (typeof document.adminForm.onsubmit == "function") {
        document.adminForm.onsubmit();
    }
    document.adminForm.submit();
}
</script>
<?php include_partial("wiki/screenshots"); ?>
    <div id="content">
        <div class="content_inner">
        <header>
          <h2 class="content">添加频道推荐</h2>
          <nav class="utility">
          <li class="add"><a href="<?php echo url_for("channel_recommend/index?type=$type&code=$channelCode")?>">推荐列表</a></li>
          </nav>
        </header>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
				
				<form action="<?php echo url_for("channel_recommend/add");?>" id="addForm" name="addForm" method="post" >
				<div class="widget-body">
				  <ul class="wiki-meta">
                     <li><label>所属频道：</label><?php include_partial('searcha',array( 'types'=>$types,'type'=>$type,'channelCode'=>$channelCode));?></li>  
					 <li><label>Wiki_ID：</label><input type="text" name="wiki_id"></li>  
					 <li><label>推荐标题：</label><input type="text"  name="title"></li>
					 <li><label>推荐图片:</label><span id='right'></span><a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=channelrecommendscreenshotAdds">上传剧照</a></li>
					 <li><label>播放时间:</label><input type="text" name="playtime"></li>
					 <li><label>节目介绍:</label><input type="text" name="remark"></li>
					 <li><label>推荐顺序:</label><input type="text" name="sort"></li>
					 <li><input type="hidden" name="channel_id" id="channel_id" value="<?php echo $channel_id;?>"><input type="submit" value="提交"></li>
				  </ul>
				</div>
				</form>
            </div>
            <div class="clear"></div>
        </div>
      </div>