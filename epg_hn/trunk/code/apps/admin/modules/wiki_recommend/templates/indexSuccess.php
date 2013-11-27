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
<!--          <form action="" method="get">-->
            <?php include_partial('toolbarList')?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
            <?php include_partial('select', array('m' => $m,'j'=>$j))?>
<form method="post" name="adminForm" action="<?php echo url_for('@wiki_recommend');?>/batch">
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for("wiki_recommend/index?m=$m&j=$j&page=".$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for("wiki_recommend/index?m=$m&j=$j&page=".$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for("wiki_recommend/index?m=$m&j=$j&page=".$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for("wiki_recommend/index?m=$m&j=$j&page=".$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for("wiki_recommend/index?m=$m&j=$j&page=".$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col" class="list_id">标题</th>
                  <th scope="col" class="list_model">模型</th>
                  <th scope="col" class="list_created_at">创建时间</th>
                  <th scope="col" class="list_updated_at">更新时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col">标题</th>
                  <th scope="col">模型</th>
                  <th scope="col">创建时间</th>
                  <th scope="col">更新时间</th>
                  <th scope="col">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                  <?php foreach ($pager->getResults() as $i => $rs): ?>
                    <?php $wiki = $rs->getWiki();?>
                        <?php if (!empty ($wiki)):?>
                            <tr>
                              <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="id[]"></td>
                              <td><a href="<?php echo url_for('wiki/edit?id='.$rs->getWikiId());?>"><?php echo $wiki->getTitle();?></a></td>
                              <td><?php echo $wiki->getDisplayName();?></td>
                              <td><?php echo $wiki->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                              <td><?php echo ($created_at = $wiki->getCreatedAt()) ? $created_at->format("Y-m-d H:i:s") : $wiki->getUpdatedAt()->format("Y-m-d H:i:s");?></td>
                              <td><a href="<?php echo url_for("wiki_recommend/delete?id=".$rs->getId())?>" class="delete" onclick="if(!confirm('确定取消吗？')) return false;">取消推荐</a></td>
                            </tr>
                        <?php endif;?>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>
<input type="hidden" value="7ae5f9bb4952382f3637ea68bfafe589" name="_csrf_token">
<input type="hidden" value="" name="batch_action">
</form>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for("wiki_recommend/index?m=$m&j=$j&page=".$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for("wiki_recommend/index?m=$m&j=$j&page=".$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for("wiki_recommend/index?m=$m&j=$j&page=".$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for("wiki_recommend/index?m=$m&j=$j&page=".$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for("wiki_recommend/index?m=$m&j=$j&page=".$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

            <div class="clear"></div>
<!--          </form>-->
        </div>
      </div>