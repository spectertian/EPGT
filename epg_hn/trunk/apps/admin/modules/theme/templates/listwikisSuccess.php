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
        document.adminForm.batch_action.value=action;
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
            <?php include_partial('toolbarListwiki',array('theme'=>$theme,'id'=>$theme->getId(),'add'=>true))?>
            <?php include_partial('global/flashes') ?> 
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col" class="list_model">标题</th>
                  <th scope="col" class="list_model">图片</th>
                  <th scope="col" class="list_id">推荐理由</th>
                  <th scope="col" class="list_action">更新时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col">标题</th>
                  <th scope="col">图片</th>
                  <th scope="col">推荐理由</th>
                  <th scope="col">更新时间</th>
                  <th scope="col">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset($items)):?>
                  <?php foreach ($items as $i => $item): ?>
                        <?php if (!empty ($item)):?>
                            <tr>
                              <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $item->getId();?>" name="id[]"></td>
                              <td><img src="<?php echo $item->getCoverurl();?>" width="80" height="110"><br/><a href="<?php echo url_for('wiki/edit?id='.$item->getWikiId());?>"><?php echo $item->getTitle();?></a></td>
                              <td><a href="<?php echo file_url($item->getImg())?>" target="_blank"><img src="<?php echo file_url($item->getImg())?>" width="80"></a></td>
                              <td><?php echo mb_substr($item->getRemark(), 0, 80, 'utf-8');?></td>
							  <td><?php echo $item->getCreatedAt();?><br/><?php echo $item->getUpdatedAt();?></td>
                              <td>
                              <a href="<?php echo url_for("theme/editwiki?id=".$theme->getId()."&item_id=".$item->getId())?>" class="edit">修改</a><br />
                              <a href="<?php echo url_for("theme/delwiki?id=".$theme->getId()."&item_id=".$item->getId()."&wiki_id=".$item->getWikiId())?>" class="delete" onclick="if(!confirm('确定删除吗？')) return false;">删除</a></td>
                            </tr>
                        <?php endif;?>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>
            <div class="clear"></div>
        </div>
      </div>