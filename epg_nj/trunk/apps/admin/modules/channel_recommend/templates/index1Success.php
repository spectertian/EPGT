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
            <?php include_partial('toolbarList')?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
				<?php include_partial('search',array( 'topTvStations'=>$parentTvStations,'channels'=>$channels,'channel_id'=>$channel_id,'tvStation_id'=>$tvStation_id ));?>
				<form action="<?php echo url_for("channel_recommend/update?channel_id=".$channel_id);?>" id="listForm" name="listForm" method="post" >
				<?php foreach($recommends as $recommend) :?>
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li><label>Wiki_ID：</label><input type="text" name="recommend[<?php echo $recommend->getId()?>][wiki_id]" value="<?php echo $recommend->getWikiId()?>"></li>  
					 <li><label>推荐标题：</label><input type="text" name="recommend[<?php echo $recommend->getId()?>][title]" value="<?php echo $recommend->getTitle()?>"></li>
					 <li>
					 	<label>推荐图片:</label>
						 <ul id="right_<?php echo $recommend->getId()?>">
					 	 <?php if($recommend->getPic()):?>
							<li id="screenshots_index_Wwp6tDBJlBVf8JCxdCsAMJXfXcQ9571811">     
								<input  name="recommend[<?php echo $recommend->getId()?>][pic]" value="<?php echo $recommend->getPic();?>" type="hidden" />
								<img style="" id="screenshots_pic_Wwp6tDBJlBVf8JCxdCsAMJXfXcQ9571811" src="<?php echo file_url($recommend->getPic());?>" alt="加载中"> 
							</li>
						<?php endif;?>
			            </ul>
					 	 <?php if($recommend->getPic()):?>
					 	<span id="up_<?php echo $recommend->getId()?>"><a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=channelrecommendscreenshotupdate&num=<?php echo $recommend->getId()?>">更改剧照</a></span>
					 	<?php else:?>
					 	<span id="up_<?php echo $recommend->getId()?>"><a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=channelrecommendscreenshotupdate&num=<?php echo $recommend->getId()?>">上传剧照</a></span>
					 	<?php endif;?>
					 </li>
					 <li><label>播放时间:</label><input type="text" name="recommend[<?php echo $recommend->getId()?>][palytime]" value="<?php echo $recommend->getPlaytime()?>"></li>
					 <li><label>节目介绍:</label><input type="text" name="recommend[<?php echo $recommend->getId()?>][remark]" value="<?php echo $recommend->getRemark()?>"></li>
					 </li>
				  </ul>
				</div>				
                <h3><a href="#" onclick="$('#listForm').submit();">提交修改</a></h3>
				<?php endforeach;?>
				</form>	
                <h3>添加</h3>
				<form action="<?php echo url_for("channel_recommend/add?channel_id=".$channel_id);?>" id="addForm" name="addForm" method="post" >
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li><label>Wiki_ID：</label><input type="text" name="wiki_id"></li>  
					 <li><label>推荐标题：</label><input type="text"  name="title"></li>
					 <li><label>推荐图片:</label><span id='right'></span><a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=channelrecommendscreenshotAdds">上传剧照</a></li>
					 <li><label>播放时间:</label><input type="text" name="playtime"></li>
					 <li><label>节目介绍:</label><input type="text" name="remark"></li>
					 <li><label>推荐顺序:</label><input type="text" name="sort"></li>
					 <li><input type="submit" value="提交"></li>
				  </ul>
				</div>
				</form>
            </div>
            <div class="clear"></div>
        </div>
      </div>