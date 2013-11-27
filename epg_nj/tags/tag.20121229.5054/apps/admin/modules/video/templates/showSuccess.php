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

      <div id="content">
        <div class="content_inner">
          <form method="post" name="adminForm" action="<?php echo url_for('video/delete');?>">
            <input type="hidden" value="7ae5f9bb4952382f3637ea68bfafe589" name="_csrf_token">
            <input type="hidden" value="" name="batch_action">
            <header>
              <h2 class="content"><?php echo $pageTitle;?></h2>
              <nav class="utility">
                  <li class="back"><a href="<?php echo url_for('video/index');?>">返回列表</a></li>
                  <li class="delete"><a href="javascript:submitform('delete');">删除</a></li>
              </nav>
            </header>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
              <div class="paginator">
                <span class="first-page"> <a href="<?php echo url_for('video/show?id='.$wiki->getId().'&page='.$pager->getFirstPage())?>">最前页</a></span>
                <span class="prev-page"><a href="<?php echo url_for('video/show?id='.$wiki->getId().'&page='.$pager->getPreviousPage());?>">上一页</a></span>
                <span class="pages">
                  <?php $links  = $pager->getLinks(5);?>
                    <?php foreach ($links as  $link):?>
                        <?php if ($link == $pager->getPage()):?>
                            <span class="present"><?php echo $link;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('video/show?id='.$wiki->getId().'&page='.$link);?>"><?php echo $link;?></a>
                        <?php endif;?>
                    <?php endforeach;?>
                </span>
                <span class="next-page"><a href="<?php echo url_for('video/show?id='.$wiki->getId().'&page='.$pager->getNextPage());?>">下一页</a></span>
                <span class="last-page"><a href="<?php echo url_for('video/show?id='.$wiki->getId().'&page='.$pager->getLastPage());?>">最末页</a></span>
                <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
              </div>
              <div class="clear"></div>
            </div>
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox" onclick="checkAll();" name="toggle" id="sf_admin_list_batch_checkbox"></th>
                  <th scope="col" class="list_id">标题</th>
                  <th scope="col" class="list_id">视频来源</th>
                  <th scope="col" class="list_created_at">创建时间</th>
                  <th scope="col" class="list_updated_at">更新时间</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox" onclick="checkAll();" name="toggle" id="sf_admin_list_batch_checkbox"></th>
                  <th scope="col" class="list_id">标题</th>
                  <th scope="col" class="list_id">视频来源</th>
                  <th scope="col" class="list_created_at">创建时间</th>
                  <th scope="col" class="list_updated_at">更新时间</th>
                </tr>
              </tfoot>
              <tbody>
                <?php foreach ($pager->getResults() as $i => $video):?>
                <tr>
                  <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $video->getId();?>" name="id[]"></td>
                  <td><a href="<?php echo $video->getPlayUrl()?>" target="_blank"><?php echo $video->getTitle()?></a></td>
                  <td><?php echo $video->getRefererZhcn()?></td>
                  <td><?php echo $video->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                  <td><?php echo ($updated_at = $wiki->getUpdatedAt()) ? $updated_at->format("Y-m-d H:i:s") : $video->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
              <div class="paginator">
                <span class="first-page"> <a href="<?php echo url_for('video/show?id='.$wiki->getId().'&page='.$pager->getFirstPage())?>">最前页</a></span>
                <span class="prev-page"><a href="<?php echo url_for('video/show?id='.$wiki->getId().'&page='.$pager->getPreviousPage());?>">上一页</a></span>
                <span class="pages">
                  <?php $links  = $pager->getLinks(5);?>
                    <?php foreach ($links as  $link):?>
                        <?php if ($link == $pager->getPage()):?>
                            <span class="present"><?php echo $link;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('video/show?id='.$wiki->getId().'&page='.$link);?>"><?php echo $link;?></a>
                        <?php endif;?>
                    <?php endforeach;?>
                </span>
                <span class="next-page"><a href="<?php echo url_for('video/show?id='.$wiki->getId().'&page='.$pager->getNextPage());?>">下一页</a></span>
                <span class="last-page"><a href="<?php echo url_for('video/show?id='.$wiki->getId().'&page='.$pager->getLastPage());?>">最末页</a></span>
                <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
              </div>
              <div class="clear"></div>
          </form>
        </div>
      </div>
