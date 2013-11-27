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
          	<li class="add"><a href="/videos_zhui/add" >添加</a></li>
            <li class="delete"><a class="toolbar" onclick="javascript:submitform('batchDelete')" href="#">删除</a></li>
            </nav>
        </header>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
			<?php include_partial('select', array('wikiId'=>$wikiId,'wikiName'=>$wikiName,'state'=>$state))?>
		<form action="/videos_zhui/batchDelete" id='adminForm'  method="post">
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo '/videos_zhui/index?page='.$pager->getFirstPage().'&wikiid='.$wikiId.'&wikiname='.$wikiName.'&state='.$state; ?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo '/videos_zhui/index?page='.$pager->getPreviousPage().'&wikiid='.$wikiId.'&wikiname='.$wikiName.'&state='.$state; ?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo '/videos_zhui/index?page='.$page.'&wikiid='.$wikiId.'&wikiname='.$wikiName.'&state='.$state; ?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo '/videos_zhui/index?page='.$pager->getNextPage().'&wikiid='.$wikiId.'&wikiname='.$wikiName.'&state='.$state; ?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo '/videos_zhui/index?page='.$pager->getLastPage().'&wikiid='.$wikiId.'&wikiname='.$wikiName.'&state='.$state; ?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col" class="list_wikiname">WikiID</th>
                  <th scope="col" class="list_wikiname">节目名称</th>
                  <th scope="col" class="list_category">总集数</th>
                  <th scope="col" class="list_is_default">已抓集数</th>
                  <th scope="col" class="list_start_time">抓取状态</th>
                  <th scope="col" class="list_start_time">更新时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col" class="list_wikiname">WikiID</th>
                  <th scope="col" class="list_wikiname">节目名称</th>
                  <th scope="col" class="list_category">总集数</th>
                  <th scope="col" class="list_is_default">已抓集数</th>
                  <th scope="col" class="list_start_time">抓取状态</th>
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
                              <td style="word-break:break-all;word-wrap:break-word;"><font color="<?php echo ($rs->getState()==2)?'':'red'?>"><?php echo $rs->getWikiId();?></font></td>
                              <td>
                              	<a target="_blank" href="<?php echo '/wiki/edit?id='.$rs->getWikiId();?>" ><font color="<?php echo ($rs->getState()==2)?'':'red'?>"><?php echo $rs->getWikiName();?></font></a>
                              </td>
                              <td><font color="<?php echo ($rs->getState()==2)?'':'red'?>"><?php echo $rs->getTotal(); ?></font></td>
                              <td><font color="<?php echo ($rs->getState()==2)?'':'red'?>"><?php echo $rs->getLocal(); ?></font></td>
                              <td><?php $state = $rs->getState();?>
                                <?php if($state==0 || $state==1): ?>
                                <a onclick="if(!confirm('确定要暂停抓取吗？')) return false;" href="/videos_zhui/publishoff?id=<?php echo $rs->getId();?>"><img src="/images/delete.png" title="已发布：点击取消发布" alt="Checked"></a>
                                <?php endif; ?>
                                <?php if($state==2): ?>
                                <a onclick="if(!confirm('确定要重新抓取吗？')) return false;" href="/videos_zhui/publishon?id=<?php echo $rs->getId();?>"><img src="/images/accept.png" title="已发布：点击取消发布" alt="Checked"></a>
                                <?php endif; ?>
                              </td>
                              <td><font color="<?php echo ($rs->getState()==2)?'':'red'?>"><?php echo $rs->getUpdatedAt()?$rs->getUpdatedAt()->format("Y-m-d H:i:s"):$rs->getCreatedAt()->format("Y-m-d H:i:s"); ?></font></td>
                              <td>
                              	<a href="<?php echo "/videos_zhui/edit/id/".$rs->getId()?>" ><font color="<?php echo ($rs->getState()==2)?'':'red'?>">编辑</font></a>&nbsp;
                              	<a href="<?php echo "/videos_zhui/list/id/".$rs->getId()?>" ><font color="<?php echo ($rs->getState()==2)?'':'red'?>">状态查看</font></a>&nbsp;
                              	<a href="<?php echo "/videos_zhui/delete?id=".$rs->getId()?>" onclick="if(!confirm('确定删除吗？')) return false;"><font color="<?php echo ($rs->getState()==2)?'':'red'?>">删除</font></a></td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo '/videos_zhui/index?page='.$pager->getFirstPage().'&wikiid='.$wikiId.'&wikiname='.$wikiName.'&state='.$state; ?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo '/videos_zhui/index?page='.$pager->getPreviousPage().'&wikiid='.$wikiId.'&wikiname='.$wikiName.'&state='.$state; ?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo '/videos_zhui/index?page='.$page.'&wikiid='.$wikiId.'&wikiname='.$wikiName.'&state='.$state; ?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo '/videos_zhui/index?page='.$pager->getNextPage().'&wikiid='.$wikiId.'&wikiname='.$wikiName.'&state='.$state; ?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo '/videos_zhui/index?page='.$pager->getLastPage().'&wikiid='.$wikiId.'&wikiname='.$wikiName.'&state='.$state; ?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
</form>
        </div>
      </div>