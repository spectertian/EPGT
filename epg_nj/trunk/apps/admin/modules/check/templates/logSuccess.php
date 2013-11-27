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
          <form action="<?php echo url_for('check/logDel');?>" id="adminForm" name="adminForm" method="post" >
            <header>
              <h2 class="content">接口监测日志</h2>
              <nav class="utility">
                <li class="delete"><a class="toolbar" onclick="javascript:submitform('/check/logDel')" href="#">删除</a></li>
              </nav>
            </header>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('check/log?page='.$pager->getFirstPage()."&status=$status");?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('check/log?page='.$pager->getPreviousPage()."&status=$status");?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('check/log?page='.$page."&status=$status");?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('check/log?page='.$pager->getNextPage()."&status=$status");?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('check/log?page='.$pager->getLastPage()."&status=$status");?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox" width="5%"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from" width="85%">日志</th>
                  <th scope="col" class="list_from" width="15%">时间</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"  id="sf_admin_list_batch_checkbox1" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from">日志</th>
                  <th scope="col" class="list_word">时间</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                    <?php 
                    foreach ($pager->getResults() as $i => $rs):       
                    ?>
                            <tr>
                              <td class="logid"><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="ids[]"></td>
                              <td><?php echo $rs->getLog();?></td>
                              <td><?php echo $rs->getTime()->format("Y-m-d H:i:s");?></td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>

            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('check/log?page='.$pager->getFirstPage()."&status=$status");?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('check/log?page='.$pager->getPreviousPage()."&status=$status");?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('check/log?page='.$page."&status=$status");?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('check/log?page='.$pager->getNextPage()."&status=$status");?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('check/log?page='.$pager->getLastPage()."&status=$status");?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
            <input type="hidden" name="batch_action" value="0" id="batch_action" />
        </form>
        </div>
      </div>