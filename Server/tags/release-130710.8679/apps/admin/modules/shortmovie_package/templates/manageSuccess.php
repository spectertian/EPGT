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
            <header>
                <h2>专题:<?php echo $smpname; ?></h2>
				<nav class="utility">
				  <li class="add"><a href="/shortmovie_package/addshortmovie?smpid=<?php echo $smpid; ?>&smpname=<?php echo $smpname; ?>">关联短视频</a></li>
				  <li class="back"><a href="<?php echo url_for("shortmovie_package/index")?>">返回列表</a></li>
				</nav>
			</header>
            <?php include_partial('global/flashes') ?>
            
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_model"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col" class="list_id">标题</th>
                  <th scope="col" class="list_updated_at">图片</th>
                  <th scope="col" class="list_id">来源</th>
                  <th scope="col" class="list_updated_at">标签</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col">标题</th>
                  <th scope="col">图片</th>
                  <th scope="col">来源</th>
                  <th scope="col" class="list_updated_at">标签</th>
                  <th scope="col">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset($items)):?>
                  <?php foreach ($items as $i => $item): ?>
                        <?php if (!empty ($item)):?>
                            <tr>
                              <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $item->getId();?>" name="id[]"></td>
                              <td><a href='<?php echo "/short_movie/edit?id=".$item->getId();?>'><?php echo $item->getName(); ?></a></td>
                              <td><a href="<?php echo file_url($item->getCover())?>" target="_blank"><img src="<?php echo file_url($item->getCover())?>" width="100"></a></td>
                              <td><?php echo $item->getRefer(); ?></td>
                              <td><?php $tags = $item->getTag(); echo $tags[0];?></td>
                              <td>
                              <a href="<?php echo url_for("shortmovie_package/delsm?smpid=".$smpid."&smid=".$item->getId())?>" class="delete" onclick="if(!confirm('确定移除吗？')) return false;">移除</a></td>
                            </tr>
                        <?php endif;?>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>
            <div class="clear"></div>
        </div>
      </div>