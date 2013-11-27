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
<style type="text/css">
#channels{
    width:100%;
}
#channels li{
    width: 20%;
    float: left;
    text-align: left;
}
</style>
<?php $path="&date=$date&channel=$channel";?>
    <div id="content">
        <div class="content_inner">
          
            <header>
              <h2 class="content">EPG发送频道查询</h2>
              <nav class="utility">
              </nav>
            </header>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
                <form method="get" action="">
                    日期：<input type="text" name="date" value="<?php echo $date?>" class="datepicker"/>
                    频道：<input type="text" name="channel" value="<?php echo $channel?>"/>
                            <input type="submit" value="查询">
                </form>
                <div class="paginator">
                  <span class="first-page">
                      <a href="<?php echo url_for('count/epgLog?page='.$pager->getFirstPage().$path);?>">
                      最前页
                      </a>
                  </span>
                  <span class="prev-page">
                     <a href="<?php echo url_for('count/epgLog?page='.$pager->getPreviousPage().$path);?>">上一页</a>
                  </span>
                  <span class="pages">
                      <?php foreach ($pager->getLinks(5) as $page ):?>
                            <?php if ($page == $pager->getPage()):?>
                                <span class="present"><?php echo $page;?></span>
                            <?php else:?>
                                <a href="<?php echo url_for('count/epgLog?page='.$page.$path);?>"><?php echo $page;?></a>
                            <?php endif;?>
                      <?php endforeach;?>
                  </span>
                  <span class="next-page"><a href="<?php echo url_for('count/epgLog?page='.$pager->getNextPage().$path);?>">下一页</a></span>
                  <span class="last-page"><a href="<?php echo url_for('count/epgLog?page='.$pager->getLastPage().$path);?>">最末页</a></span>
                  <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
                </div>

              <div class="clear"></div>
            </div>

            <form action="<?php echo url_for('epgLog/Del');?>" id="adminForm" name="adminForm" method="post" >
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox" width="5%"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from" width="15%">日期</th>
                  <th scope="col" class="list_from" width="65%">发送频道</th>
                  <th scope="col" class="list_from" width="20%">时间</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"  id="sf_admin_list_batch_checkbox1" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from">日期</th>
                  <th scope="col" class="list_from">发送频道</th>
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
                              <td><ul id="channels">
                              <?php 
                                  $channels=$rs->getChannels();
                                  foreach($channels as $channel){
                                      echo '<li>',$channel,'</li>';
                                  }
                              ?></ul>
                              </td>
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
                  <a href="<?php echo url_for('count/epgLog?page='.$pager->getFirstPage().$path);?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('count/epgLog?page='.$pager->getPreviousPage().$path);?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('count/epgLog?page='.$page.$path);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('count/epgLog?page='.$pager->getNextPage().$path);?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('count/epgLog?page='.$pager->getLastPage().$path);?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
            
        
        </div>
      </div>