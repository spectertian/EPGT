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
              <h2 class="content">消息队列失败日志</h2>
              <nav class="utility">
                <li class="delete"><a class="toolbar" onclick="javascript:submitform('/queueLog/del')" href="#">删除</a></li>
                <li class="app-add"><a class="toolbar" onclick="javascript:submitform('/queueLog/addQueue')" href="#">加入队列</a></li>
              </nav>
            </header>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
                <form method="get" action="">
                    关键词：<input type="text" name="title" value="<?php echo $title?>"/>
                            <input type="submit" value="查询">
                </form>
                <div class="paginator">
                  <span class="first-page">
                      <a href="<?php echo url_for('queueLog/index?page='.$pager->getFirstPage()."&title=$title");?>">
                      最前页
                      </a>
                  </span>
                  <span class="prev-page">
                     <a href="<?php echo url_for('queueLog/index?page='.$pager->getPreviousPage()."&title=$title");?>">上一页</a>
                  </span>
                  <span class="pages">
                      <?php foreach ($pager->getLinks(5) as $page ):?>
                            <?php if ($page == $pager->getPage()):?>
                                <span class="present"><?php echo $page;?></span>
                            <?php else:?>
                                <a href="<?php echo url_for('queueLog/index?page='.$page."&title=$title");?>"><?php echo $page;?></a>
                            <?php endif;?>
                      <?php endforeach;?>
                  </span>
                  <span class="next-page"><a href="<?php echo url_for('queueLog/index?page='.$pager->getNextPage()."&title=$title");?>">下一页</a></span>
                  <span class="last-page"><a href="<?php echo url_for('queueLog/index?page='.$pager->getLastPage()."&title=$title");?>">最末页</a></span>
                  <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
                </div>

              <div class="clear"></div>
            </div>

            <form action="<?php echo url_for('queueLog/Del');?>" id="adminForm" name="adminForm" method="post" >
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox" width="5%"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from" width="75%">日志</th>
                  <th scope="col" class="list_from" width="15%">时间</th>
                  <th scope="col" class="list_from" width="15%">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"  id="sf_admin_list_batch_checkbox1" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from">日志</th>
                  <th scope="col" class="list_word">时间</th>
                  <th scope="col" class="list_from">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                    <?php 
                    foreach ($pager->getResults() as $i => $rs):       
                    ?>
                            <tr>
                              <td class="logid"><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="ids[]"></td>
                              <td><?php echo $rs->getContent();?></td>
                              <td><?php echo $rs->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                              <td>
                              <?php if($rs->getState()==0):?>
                              <a href="<?php echo url_for('queueLog/addQueue?id='.$rs->getId());?>">加入队列</a>
                              <?php else:?>
                              已重新加入队列
                              <?php endif;?>
                              </td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>
            <input type="hidden" name="batch_action" value="0" id="batch_action" />
            </form>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('queueLog/index?page='.$pager->getFirstPage()."&title=$title");?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('queueLog/index?page='.$pager->getPreviousPage()."&title=$title");?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('queueLog/index?page='.$page."&title=$title");?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('queueLog/index?page='.$pager->getNextPage()."&title=$title");?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('queueLog/index?page='.$pager->getLastPage()."&title=$title");?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
            
        
        </div>
      </div>