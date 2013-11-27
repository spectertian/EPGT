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
          <h2 class="content">修改频道推荐</h2>
          <nav class="utility">
          <li class="add"><a href="<?php echo url_for("channel_recommend/index?type=$type&code=$channelCode")?>">推荐列表</a></li>
          </nav>
        </header>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
				
				<form action="<?php echo url_for("channel_recommend/edit");?>" id="adminForm" name="adminForm" method="post" >
				<div class="widget-body">
				  <ul class="wiki-meta">
                     <li><label>所属频道：</label><?php include_partial('searcha',array('types'=>$types,'type'=>$type,'channelCode'=>$channelCode));?></li>
					 <li><label>Wiki_ID：</label><input type="text" name="wiki_id" value="<?php echo $recommend->getWikiId();?>"></li>  
					 <li><label>推荐标题：</label><input type="text"  name="title" value="<?php echo $recommend->getTitle();?>"></li>
					 <li><label>推荐图片:</label><span id='right'><input type="hidden" name="pic" id="pic" value="<?php echo $recommend->getPic();?>"></span><a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=channelrecommendscreenshotAdds">更改剧照</a><br />
                     <?php if($recommend->getPic()):?>
                     <img src="<?php echo file_url($recommend->getPic());?>" alt="加载中" width="100px"> 
                     <?php endif;?>
                     </li>
					 <li><label>播放时间:</label><input type="text" name="playtime" value="<?php echo $recommend->getPlaytime();?>"></li>
					 <li><label>节目介绍:</label><input type="text" name="remark" value="<?php echo $recommend->getRemark();?>"></li>
					 <li><label>推荐顺序:</label><input type="text" name="sort" value="<?php echo $recommend->getSort();?>"></li>
					 <li><input type="hidden" name="id" id="id" value="<?php echo $recommend->getId();?>"><input type="hidden" name="channel_id" id="channel_id" value="<?php echo $channel_id;?>"><input type="submit" value="提交" onclick="submitform()"></li>
				  </ul>
				</div>
				</form>
            </div>
            <div class="clear"></div>
        </div>
      </div>