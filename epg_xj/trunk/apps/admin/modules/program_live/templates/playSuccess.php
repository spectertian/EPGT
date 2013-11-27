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
        if(confirm('确定删除吗？')){
	        admin_form = document.getElementById('adminForm');
	        admin_form.action = "<?php echo url_for('program_live/batchdelete')?>";
	        admin_form.submit();
        }
    }
 
}

function updateform(action){
    if (action) {
    
	        admin_form = document.getElementById('adminForm');
	        admin_form.action = "<?php echo url_for('program_live/batchupdate')?>";
	        admin_form.submit();
     
    }
 
}

function Publish(publish)
{
    $("#publish_off").val(publish);
    admin_form = document.getElementById('adminForm');
    admin_form.action = "<?php echo url_for('short_movie/BatchPublish')?>";
    admin_form.submit();
}
</script>	
      <div id="content">
        <div class="content_inner"> 
	       <header>
          <h2 class="content"><?php print $pageTitle;?></h2>
          
  <nav class="utility">
    <li class="app-add"><a href="javascript:updateform('update');">批量更新</a></li>
    <li class="delete"><a href="javascript:submitform('delete');">批量删除</a></li>
  </nav>
         </header>
            <div class="table_nav">
 
              <form method="get" action="">
 
                    过期： 
                     <select name="t" id="channel">
				        <option value="" >请选择</option>
				        <option value="1" <?php if($t=='1'){echo 'selected';}?>>全部</option>
				        <option value="2" <?php if($t=='2'){echo 'selected';}?>>未知</option>
				        <option value="3" <?php if($t=='3'){echo 'selected';}?>>过期</option>
				    </select>
    
                    <input type="submit" value="查询">   
              <div class="paginator">
                <span class="first-page"><a href="<?php echo url_for('program_live/play?page='.$pager->getFirstPage(). ($t ? "&t=".$t : ""));?>">最前页</a></span>
                <span class="prev-page"><a href="<?php echo url_for('program_live/play?page='.$pager->getPreviousPage(). ($t ? "&t=".$t : ""));?>">上一页</a></span>
                <span class="pages">
                   <?php $links  = $pager->getLinks(5);?>
                    <?php foreach ($links as  $link):?>
                        <?php if ($link == $pager->getPage()):?>
                            <span class="present"><?php echo $link;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('program_live/play?page='.$link . ($t ? "&t=".$t : ""));?>"><?php echo $link;?></a>
                        <?php endif;?>
                    <?php endforeach;?>
                </span>
                <span class="next-page"><a href="<?php echo url_for('program_live/play?page='.$pager->getNextPage(). ($t ? "&t=".$t : ""));?>">下一页</a></span>
                <span class="last-page"><a href="<?php echo url_for('program_live/play?page='.$pager->getLastPage(). ($t ? "&t=".$t : ""));?>">最末页</a></span>
                <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
              </div>
              <div class="clear"></div>
            </div>
            </form>
            <form method="post" name="adminForm" id="adminForm" action="<?php echo url_for('program_live/play');?>">
                <input type="hidden" value="7ae5f9bb4952382f3637ea68bfafe589" name="_csrf_token">
                <input type="hidden" value="" name="batch_action">
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox" onclick="checkAll(this);" name="toggle" id="sf_admin_list_batch_checkbox"></th>
                  <th scope="col" class="list_id">频道</th>
                  <th scope="col" class="list_id">当前播放节目</th>
                  <th scope="col" class="list_created_at">开始时间</th>
                  <th scope="col" class="list_updated_at">结束时间</th>
                  <th scope="col" class="list_updated_at">下一个节目</th>
									<th scope="col" class="list_updated_at">操作</th>
									
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox" onclick="checkAll(this);" name="toggle" id="sf_admin_list_batch_checkbox"></th>
                  <th scope="col" class="list_id">频道</th>
                  <th scope="col" class="list_id">当前播放节目</th>
                  <th scope="col" class="list_created_at">开始时间</th>
                  <th scope="col" class="list_updated_at">结束时间</th>
                  <th scope="col" class="list_updated_at">下一个节目</th>
									<th scope="col" class="list_updated_at">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php foreach ($pager as $wiki):?>
                <tr >
				  <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $wiki->getId();?>" name="id[]"></td>
 				  <td  <?php echo $wiki->getName()=='未知' ? 'style="color:#ff0033;"' : '';?> >
			        <?php echo $wiki->getchannelCode() ? $wiki->getChannelName() : "";?>
                  </td>    
                  <td <?php echo $wiki->getName()=='未知' ? 'style="color:#ff0033;"' : '';?> ><?php echo $wiki->getName();?></td>
                  <td  ><?php echo $wiki->getStartTime()?$wiki->getStartTime()->format("H:i"):'';?></td>
                  <?php if($wiki->getEndTime()){?>
                  <td <?php echo time()>strtotime($wiki->getEndTime()->format("Y:m:d H:i:s")) ? 'style="color:#ff0033;"' : '';?> ><?php echo $wiki->getEndTime()?$wiki->getEndTime()->format("H:i"):'';?></td>
				  <?php }else{?>
				   <td></td>
				  <?php }?>
				  <td ><?php echo $wiki->getNextname();?></td>  
                  <td><a href="<?php echo url_for("program_live/update?id=".$wiki->getId().'&channel_code='.$wiki->getChannelCode());?>" class="recommend">更新</a> | <a href="<?php echo url_for("program_live/delete?id=".$wiki->getId())?>" class="delete"  onClick="return window.confirm('确定删除吗?');">删除</a></td>         
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
            <div class="paginator">
                <span class="first-page"><a href="<?php echo url_for('program_live/play?page='.$pager->getFirstPage(). ($t ? "&t=".$t : ""));?>">最前页</a></span>
                <span class="prev-page"><a href="<?php echo url_for('program_live/play?page='.$pager->getPreviousPage(). ($t ? "&t=".$t : ""));?>">上一页</a></span>
                <span class="pages">
                   <?php $links  = $pager->getLinks(5);?>
                    <?php foreach ($links as  $link):?>
                        <?php if ($link == $pager->getPage()):?>
                            <span class="present"><?php echo $link;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('program_live/play?page='.$link . ($t ? "&t=".$t : ""));?>"><?php echo $link;?></a>
                        <?php endif;?>
                    <?php endforeach;?>
                </span>
                <span class="next-page"><a href="<?php echo url_for('program_live/play?page='.$pager->getNextPage(). ($t ? "&t=".$t : ""));?>">下一页</a></span>
                <span class="last-page"><a href="<?php echo url_for('program_live/play?page='.$pager->getLastPage(). ($t ? "&t=".$t : ""));?>">最末页</a></span>
                <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>
            <div class="clear"></div>
          </form>
        </div>
      </div>