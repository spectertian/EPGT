<script type="text/javascript">
$(document).ready(function(){
    $.datepicker.setDefaults($.datepicker.regional['zh_CN']);
    $('.datepicker').datepicker({
        showButtonPanel: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        showWeek: true,
        firstDay: 1,
        defaultDate: +0,
        model:false
    });
});
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
              <h2 class="content">直播频道点击次数统计</h2>
              <nav class="utility">
              </nav>
            </header>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
                <form method="get" action="">
                    日期：<input type="text" name="date1" value="<?php echo $date1?>"  class="datepicker"/>到<input type="text" name="date2" value="<?php echo $date2?>"   class="datepicker"/>
                     <input type="submit" value="查询并统计">
                     总次数：<?php echo $hitCount;?>
                </form>
                <div class="paginator">
                  <span class="first-page">
                      <a href="<?php echo url_for('count/liveLog').'?page='.$pager->getFirstPage().$path;?>">
                      最前页
                      </a>
                  </span>
                  <span class="prev-page">
                     <a href="<?php echo url_for('count/liveLog').'?page='.$pager->getPreviousPage().$path;?>">上一页</a>
                  </span>
                  <span class="pages">
                      <?php foreach ($pager->getLinks(5) as $page ):?>
                            <?php if ($page == $pager->getPage()):?>
                                <span class="present"><?php echo $page;?></span>
                            <?php else:?>
                                <a href="<?php echo url_for('count/liveLog').'?page='.$page.$path;?>"><?php echo $page;?></a>
                            <?php endif;?>
                      <?php endforeach;?>
                  </span>
                  <span class="next-page"><a href="<?php echo url_for('count/liveLog').'?page='.$pager->getNextPage().$path;?>">下一页</a></span>
                  <span class="last-page"><a href="<?php echo url_for('count/liveLog').'?page='.$pager->getLastPage().$path;?>">最末页</a></span>
                  <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
                </div>

              <div class="clear"></div>
            </div>

            <form action="<?php echo url_for('liveLog/Del');?>" id="adminForm" name="adminForm" method="post" >
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox" width="5%"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from" width="15%">日期</th>
                  <th scope="col" class="list_from" width="65%">点击次数</th>
                  <th scope="col" class="list_from" width="20%">时间</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"  id="sf_admin_list_batch_checkbox1" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from">日期</th>
                  <th scope="col" class="list_from">点击次数</th>
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
                              <td><?php echo $rs->getDate();?></td>
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
                  <a href="<?php echo url_for('count/liveLog').'?page='.$pager->getFirstPage().$path;?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('count/liveLog').'?page='.$pager->getPreviousPage().$path;?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('count/liveLog').'?page='.$page.$path;?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('count/liveLog').'?page='.$pager->getNextPage().$path;?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('count/liveLog').'?page='.$pager->getLastPage().$path;?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
            
        
        </div>
      </div>