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
          <h2 class="content">短视频列表</h2>
          <nav class="utility">
            <li class="add"><a href="<?php echo url_for("short_movie/add")?>">添加</a></li>
          
            <li class="delete"><a class="toolbar" onclick="javascript:submitform('short_movie/batchDelete')" href="#">删除</a></li>
         
            </nav>
        </header>
         <form action="/short_movie/batchDelete" id='adminForm'  method="post">
            <?php //include_partial('toolbarlist',array('pageTitle'=>$pageTitle))?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">


            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('short_movie/index?page='.$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('short_movie/index?page='.$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('short_movie/index?page='.$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('short_movie/index?page='.$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('short_movie/index?page='.$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col" class="list_id">名称</th>
                  <th scope="col" class="list_model">图片</th>
                  <th scope="col" class="list_updated_at">标签</th>
                  <th scope="col" class="list_created_at">来源</th>
                  <th scope="col" class="list_updated_at">状态</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col" class="list_id">名称</th>
                  <th scope="col" class="list_model">图片</th>
                  <th scope="col" class="list_updated_at">标签</th>
                  <th scope="col" class="list_created_at">来源</th>
                  <th scope="col" class="list_updated_at">状态</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                  <?php 
                  foreach ($pager->getResults() as $i => $rs): ?>
                            <tr>
                              <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="id[]"></td>
                              <td><a href="/short_movie/edit?id=<?php echo $rs->getId();?>"><?php echo $rs->getName();?></a></td>
                              <td><img src="<?php echo file_url($rs->getCover())?>" width="120px;"></td>
                              <td><?php $tags = $rs->getTag(); echo $tags[0];?></td>
                              <td><?php echo $rs->getRefer(); ?></td>
                              <td><?php $state = $rs->getState();?>
                                <?php if($state==0): ?>
                                <a onclick="if(!confirm('确定发布本视频？')) return false;" href="/short_movie/publishon?id=<?php echo $rs->getId();?>"><img src="/images/delete.png" title="已发布：点击取消发布" alt="Checked"></a>
                                <?php endif; ?>
                                <?php if($state==1): ?>
                                <a onclick="if(!confirm('确定取消发布本视频？')) return false;" href="/short_movie/publishoff?id=<?php echo $rs->getId();?>"><img src="/images/accept.png" title="已发布：点击取消发布" alt="Checked"></a>
                                <?php endif; ?>
                              </td>
                              <td><a href="/short_movie/edit?id=<?php echo $rs->getId();?>" class="recommend">编辑</a> | <a href="<?php echo "/short_movie/delete?id=".$rs->getId()?>" onclick="if(!confirm('确定删除吗？')) return false;">删除</a></td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>

            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('short_movie/index?page='.$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('short_movie/index?page='.$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('short_movie/index?page='.$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('short_movie/index?page='.$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('short_movie/index?page='.$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
</form>
        </div>
      </div>