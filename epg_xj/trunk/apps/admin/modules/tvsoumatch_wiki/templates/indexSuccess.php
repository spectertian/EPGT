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
	$('#adminForm').submit();
}
</script>
    <div id="content">
      <div class="content_inner">
        <header>
          <h2 class="content"><?php echo $pageTitle; ?></h2>
          <nav class="utility">
          	<li class="add"><a href="/tvsoumatch_wiki/add" >添加</a></li>
            <li class="delete"><a class="toolbar" onclick="javascript:submitform('batchDelete')" href="#">删除</a></li>
            </nav>
        </header>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
			<?php include_partial('select', array('tvsouId'=>$tvsouId,'wikiTitle'=>$wikiTitle,'compare'=>$compare))?>
		<form action="/tvsoumatch_wiki/batchDelete" id='adminForm'  method="post">
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo '/tvsoumatch_wiki/index?page='.$pager->getFirstPage().'&tvsouid='.$tvsouId.'&wikititle='.$wikiTitle.'&compare='.$compare; ?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo '/tvsoumatch_wiki/index?page='.$pager->getPreviousPage().'&tvsouid='.$tvsouId.'&wikititle='.$wikiTitle.'&compare='.$compare; ?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo '/tvsoumatch_wiki/index?page='.$page.'&tvsouid='.$tvsouId.'&wikititle='.$wikiTitle.'&compare='.$compare; ?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo '/tvsoumatch_wiki/index?page='.$pager->getNextPage().'&tvsouid='.$tvsouId.'&wikititle='.$wikiTitle.'&compare='.$compare; ?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo '/tvsoumatch_wiki/index?page='.$pager->getLastPage().'&tvsouid='.$tvsouId.'&wikititle='.$wikiTitle.'&compare='.$compare; ?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col" class="list_name">TvsouID</th>
                  <th scope="col" class="list_name">Tvsou节目名称</th>
                  <th scope="col" class="list_category">维基名称</th>
                  <th scope="col" class="list_is_default">修改人</th>
                  <th scope="col" class="list_start_time">更新时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col" class="list_name">TvsouID</th>
                  <th scope="col" class="list_name">Tvsou节目名称</th>
                  <th scope="col" class="list_category">维基名称</th>
                  <th scope="col" class="list_is_default">修改人</th>
                  <th scope="col" class="list_start_time">更新时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                  <?php 
                  foreach ($pager->getResults() as $i => $rs): ?>
                            <tr>
                              <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="id[]"></td>
                              <td><a target="_blank" href="http://jq.tvsou.com/introhtml/<?php echo substr($rs->getTvsouId(),0,-2);?>/index_<?php echo $rs->getTvsouId();?>.htm"><font color="<?php echo $rs->getCompare()?'':'red'?>"><?php echo $rs->getTvsouId();?></font></a></td>
                              <td><font color="<?php echo $rs->getCompare()?'':'red'?>"><?php echo $rs->getTvsouTitle(); ?></font></td>
                              <td>
                              	<?php if($rs->getWikiId()){ ?>
                              	<a target="_blank" href="<?php echo '/wiki/edit?id='.$rs->getWikiId();?>" ><font color="<?php echo $rs->getCompare()?'':'red'?>"><?php echo $rs->getWikiTitle();?></font></a>
                              	<?php }else{ ?>
                              	<font color="<?php echo $rs->getCompare()?'':'red'?>"><?php echo $rs->getWikiTitle();?></font>
                              	<?php } ?>
                              </td>
                              <td><font color="<?php echo $rs->getCompare()?'':'red'?>"><?php $names = $rs->getAuthor();echo $names['user_name']; ?></font></td>
                              <td><font color="<?php echo $rs->getCompare()?'':'red'?>"><?php echo $rs->getUpdatedAt()?$rs->getUpdatedAt()->format("Y-m-d H:i:s"):$rs->getCreatedAt()->format("Y-m-d H:i:s"); ?></font></td>
                              <td><a href="<?php echo "/tvsoumatch_wiki/edit/id/".$rs->getId()?>" ><font color="<?php echo $rs->getCompare()?'':'red'?>">编辑</font></a>&nbsp;&nbsp;<a href="<?php echo "/tvsoumatch_wiki/delete?id=".$rs->getId()?>" onclick="if(!confirm('确定删除吗？')) return false;"><font color="<?php echo $rs->getCompare()?'':'red'?>">删除</font></a></td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo '/tvsoumatch_wiki/index?page='.$pager->getFirstPage().'&tvsouid='.$tvsouId.'&wikititle='.$wikiTitle.'&compare='.$compare; ?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo '/tvsoumatch_wiki/index?page='.$pager->getPreviousPage().'&tvsouid='.$tvsouId.'&wikititle='.$wikiTitle.'&compare='.$compare; ?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo '/tvsoumatch_wiki/index?page='.$page.'&tvsouid='.$tvsouId.'&wikititle='.$wikiTitle.'&compare='.$compare; ?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo '/tvsoumatch_wiki/index?page='.$pager->getNextPage().'&tvsouid='.$tvsouId.'&wikititle='.$wikiTitle.'&compare='.$compare; ?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo '/tvsoumatch_wiki/index?page='.$pager->getLastPage().'&tvsouid='.$tvsouId.'&wikititle='.$wikiTitle.'&compare='.$compare; ?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
</form>
        </div>
      </div>