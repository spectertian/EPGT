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
            <?php include_partial('toolbarList',array('types'=>$types,'type'=>$type,'channelCode'=>$channelCode))?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
				<?php include_partial('search',array( 'types'=>$types,'type'=>$type,'channelCode'=>$channelCode));?>
                <br />
                <form action="#" id="adminForm" name="adminForm" method="post" >
                <table cellspacing="0">
                  <thead>
                    <tr>
                      <th scope="col" class="list_checkbox" style="width: 5%;"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll(this);" /></th>
                      <th scope="col" class="list_id" style="width: 18%;">Wiki_Id</th>
                      <th scope="col" class="list_model" style="width: 15%;">推荐标题</th>
                      <th scope="col" class="list_model" style="width: 25%;">节目介绍</th>
                      <th scope="col" class="list_modified_by" style="width: 12%;">播放时间</th>
                      <th scope="col" class="list_modified_by"  style="width: 15%;">创建/更新时间</th>
                      <th scope="col" class="list_action" style="width: 10%;">操作</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th scope="col" class="list_checkbox"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll(this);" /></th>
                      <th scope="col" class="list_id">Wiki_Id</th>
                      <th scope="col" class="list_model">推荐标题</th>
                      <th scope="col" class="list_model">节目介绍</th>
                      <th scope="col" class="list_modified_by">播放时间</th>
                      <th scope="col" class="list_modified_by">创建/更新时间</th>
                      <th scope="col" class="list_action">操作</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach($recommends as $recommend) :?>
                    <tr>
                      <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $recommend->getId();?>" name="ids[]"></td>
                      <td><a href="<?php echo url_for("wiki/edit?id=".$recommend->getWikiId())?>"><?php echo $recommend->getWikiId()?></a></td>
                      <td><?php echo $recommend->getTitle()?></td>
                      <td><?php echo mb_substr($recommend->getRemark(), 0, 80, 'utf-8');?></td>
                      <td><?php echo $recommend->getPlaytime()?></td>
                      <td><?php echo $recommend->getCreatedAt() ?> <br/>
    				      <?php echo $recommend->getUpdatedAt()?>
    				  </td>
                      <td><a href="<?php echo url_for("channel_recommend/edit?type=$type&code=$channelCode&id=".$recommend->getId())?>" class="recommend">编辑</a> | <a href="<?php echo url_for("channel_recommend/delete?type=$type&code=$channelCode&id=".$recommend->getId())?>" onclick="if(!confirm('确定删除吗？')) return false;">删除</a></td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>    
                <input type="hidden" name="publish" value="0" id="publish_off" /> 
                </form>         

            </div>
            <div class="clear"></div>
        </div>
      </div>