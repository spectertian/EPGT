<script type="text/javascript">
//全选
function checkAll(object)
{
    var flag    = $(object).attr('checked');
    var box     = $("input[type=checkbox]");
    if (flag) {
        box.attr('checked',true);
    }else{
        box.attr('checked',false);
    }

}

function submitform(action){
    if (action) {
        document.adminForm.action=action;
    }
    document.adminForm.submit();
    
}
</script>
    <div id="content">
        <div class="content_inner">
          
            <header>
              <h2 class="content">紧急下线日志</h2>
            </header>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
                <form method="get" action="">
                    关键词：<input type="text" name="title" value="<?php echo $title?>"/>
                            <input type="submit" value="查询">
                </form>
                <div class="paginator">
                  <span class="first-page">
                      <a href="<?php echo url_for('count/offlineLog?page='.$pager->getFirstPage()."&title=$title");?>">
                      最前页
                      </a>
                  </span>
                  <span class="prev-page">
                     <a href="<?php echo url_for('count/offlineLog?page='.$pager->getPreviousPage()."&title=$title");?>">上一页</a>
                  </span>
                  <span class="pages">
                      <?php foreach ($pager->getLinks(5) as $page ):?>
                            <?php if ($page == $pager->getPage()):?>
                                <span class="present"><?php echo $page;?></span>
                            <?php else:?>
                                <a href="<?php echo url_for('count/offlineLog?page='.$page."&title=$title");?>"><?php echo $page;?></a>
                            <?php endif;?>
                      <?php endforeach;?>
                  </span>
                  <span class="next-page"><a href="<?php echo url_for('count/offlineLog?page='.$pager->getNextPage()."&title=$title");?>">下一页</a></span>
                  <span class="last-page"><a href="<?php echo url_for('count/offlineLog?page='.$pager->getLastPage()."&title=$title");?>">最末页</a></span>
                  <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
                </div>

              <div class="clear"></div>
            </div>

            <form action="<?php echo url_for('queueLog/Del');?>" id="adminForm" name="adminForm" method="post" >
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox" width="5%"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from" width="75%">名称</th>
                  <th scope="col" class="list_from" width="20%">下线时间</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"  id="sf_admin_list_batch_checkbox1" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from">名称</th>
                  <th scope="col" class="list_word">下线时间</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                    <?php 
                    foreach ($pager->getResults() as $i => $rs):       
                    ?>
                            <tr>
                              <td class="logid"><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="ids[]"></td>
                              <td><?php echo $rs->getTitle();?></td>
                              <td><?php echo $rs->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>
            <input type="hidden" name="batch_action" value="0" id="batch_action" />
            </form>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('count/offlineLog?page='.$pager->getFirstPage()."&title=$title");?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('count/offlineLog?page='.$pager->getPreviousPage()."&title=$title");?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('count/offlineLog?page='.$page."&title=$title");?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('count/offlineLog?page='.$pager->getNextPage()."&title=$title");?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('count/offlineLog?page='.$pager->getLastPage()."&title=$title");?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
            
        
        </div>
      </div>