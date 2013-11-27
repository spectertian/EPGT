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
<?php $path="&date1=$date1&date2=$date2";?>
    <div id="content">
        <div class="content_inner">
          
            <header>
              <h2 class="content">影片点播量统计</h2>
              <nav class="utility">
              </nav>
            </header>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
                <form method="get" action="">
                    月份：<input type="text" name="date1" value="<?php echo $date1?>" />到<input type="text" name="date2" value="<?php echo $date2?>" />格式：2013-08
                     <input type="submit" value="查询">(总次数：<?php echo $hitCount;?>)
                </form>
                <div class="paginator">
                  <span class="first-page">
                      <a href="<?php echo url_for('count/vodCount').'?page='.$pager->getFirstPage().$path;?>">
                      最前页
                      </a>
                  </span>
                  <span class="prev-page">
                     <a href="<?php echo url_for('count/vodCount').'?page='.$pager->getPreviousPage().$path;?>">上一页</a>
                  </span>
                  <span class="pages">
                      <?php foreach ($pager->getLinks(5) as $page ):?>
                            <?php if ($page == $pager->getPage()):?>
                                <span class="present"><?php echo $page;?></span>
                            <?php else:?>
                                <a href="<?php echo url_for('count/vodCount').'?page='.$page.$path;?>"><?php echo $page;?></a>
                            <?php endif;?>
                      <?php endforeach;?>
                  </span>
                  <span class="next-page"><a href="<?php echo url_for('count/vodCount').'?page='.$pager->getNextPage().$path;?>">下一页</a></span>
                  <span class="last-page"><a href="<?php echo url_for('count/vodCount').'?page='.$pager->getLastPage().$path;?>">最末页</a></span>
                  <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
                </div>

              <div class="clear"></div>
            </div>

            <form action="<?php echo url_for('vodCount/Del');?>" id="adminForm" name="adminForm" method="post" >
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox" width="5%"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from" width="20%">月份</th>
                  <th scope="col" class="list_from" width="45%">名称</th>
                  <th scope="col" class="list_from" width="10%">点击次数</th>
                  <th scope="col" class="list_from" width="20%">记录时间</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"  id="sf_admin_list_batch_checkbox1" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from">月份</th>
                  <th scope="col" class="list_from">名称</th>
                  <th scope="col" class="list_from">点击次数</th>
                  <th scope="col" class="list_word">记录时间</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                    <?php 
                    foreach ($pager->getResults() as $i => $rs):       
                    ?>
                            <tr>
                              <td class="logid"><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="ids[]"></td>
                              <td><?php echo $rs->getDate();?></td>
                              <td><?php echo $rs->getTitle();?></td>
                              <td><?php echo $rs->getHits();?></td>
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
                  <a href="<?php echo url_for('count/vodCount').'?page='.$pager->getFirstPage().$path;?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('count/vodCount').'?page='.$pager->getPreviousPage().$path;?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('count/vodCount').'?page='.$page.$path;?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('count/vodCount').'?page='.$pager->getNextPage().$path;?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('count/vodCount').'?page='.$pager->getLastPage().$path;?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
            
        
        </div>
      </div>