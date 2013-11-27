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
            <header>
            	<h2 class="content"><?php echo $pageTitle ?></h2>
				<nav class="utility">
					<li class="delete"><a href="javascript:if(confirm('确定删除吗？')){submitform('delete');}">删除</a></li>
				</nav>
			</header>
           
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
<form method="post" name="adminForm" action="<?php echo url_for('@wiki_liverecommend');?>/batch">
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
                  <th scope="col" class="list_id">维基ID</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col">维基ID</th>
                  <th scope="col">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                  <?php foreach ($pager->getResults() as $key => $rs): ?>
						<tr>
							<td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="id[]"></td>
							<td><?php echo $rs->getWikiId() ?></td>
							<td><a href="<?php echo url_for("wiki_liverecommend/delete?id=".$rs->getId())?>" class="delete" onclick="if(!confirm('确定取消吗？')) return false;">取消推荐</a></td>
						</tr>
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