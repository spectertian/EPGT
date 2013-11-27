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
    <div id="content">
        <div class="content_inner">
          
            <header>
              <h2 class="content">计划任务日志</h2>
              <nav class="utility">
                <li class="delete"><a class="toolbar" onclick="javascript:submitform('/crontabLog/Del')" href="#">删除</a></li>
              </nav>
            </header>
            <?php include_partial('global/flashes') ?>
            <?php $path="&date=$date&title=$title&state=$state";?>
            <div class="table_nav">
                <form method="get" action="">
                    日期：<input type="text" name="date" value="<?php echo $date?>"  class="datepicker"/> 名称：<input type="text" name="title" value="<?php echo $title?>"/>
                       状态:
                      <select name='state'>
                        <option value='-1'>全部</option>
                        <option value='0' <?php if ($state=='0') echo 'selected'; ?>>失败</option>
                        <option value='1' <?php if ($state=='1') echo 'selected'; ?>>成功</option>
                      </select>
                            <input type="submit" value="查询">
                </form>
                <div class="paginator">
                  <span class="first-page">
                      <a href="<?php echo url_for('crontabLog/index?page='.$pager->getFirstPage().$path);?>">
                      最前页
                      </a>
                  </span>
                  <span class="prev-page">
                     <a href="<?php echo url_for('crontabLog/index?page='.$pager->getPreviousPage().$path);?>">上一页</a>
                  </span>
                  <span class="pages">
                      <?php foreach ($pager->getLinks(5) as $page ):?>
                            <?php if ($page == $pager->getPage()):?>
                                <span class="present"><?php echo $page;?></span>
                            <?php else:?>
                                <a href="<?php echo url_for('crontabLog/index?page='.$page.$path);?>"><?php echo $page;?></a>
                            <?php endif;?>
                      <?php endforeach;?>
                  </span>
                  <span class="next-page"><a href="<?php echo url_for('crontabLog/index?page='.$pager->getNextPage().$path);?>">下一页</a></span>
                  <span class="last-page"><a href="<?php echo url_for('crontabLog/index?page='.$pager->getLastPage().$path);?>">最末页</a></span>
                  <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
                </div>

              <div class="clear"></div>
            </div>

            <form action="<?php echo url_for('crontabLog/Del');?>" id="adminForm" name="adminForm" method="post" >
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox" width="5%"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from" width="20%">名称</th>
                  <th scope="col" class="list_from" width="60%">日志</th>
                  <th scope="col" class="list_from" width="12%">开始时间</th>
                  <th scope="col" class="list_from" width="13%">结束时间</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"  id="sf_admin_list_batch_checkbox1" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from">名称</th>
                  <th scope="col" class="list_from">日志</th>
                  <th scope="col" class="list_word">开始时间</th>
                  <th scope="col" class="list_word">结束时间</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                    <?php 
                    foreach ($pager->getResults() as $i => $rs):       
                    ?>
                            <tr>
                              <td class="logid"><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="ids[]"></td>
                              <td><?php echo $rs->getTitle();?><br />
                              <?php echo $crontabNames[$rs->getTitle()];?>
                              </td>
                              <td><?php if($rs->getState()===0):?><font color="#ff0000">[失败]</font><?php endif;?><?php echo $rs->getContent();?></td>
                              <td><?php echo $rs->getStartTime()?$rs->getStartTime()->format("Y-m-d H:i:s"):'';?></td>
                              <td><?php echo $rs->getUpdatedAt()?$rs->getUpdatedAt()->format("Y-m-d H:i:s"):$rs->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>
            <input type="hidden" name="batch_action" value="0" id="batch_action" />
            </form>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('crontabLog/index?page='.$pager->getFirstPage().$path);?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('crontabLog/index?page='.$pager->getPreviousPage().$path);?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('crontabLog/index?page='.$page.$path);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('crontabLog/index?page='.$pager->getNextPage().$path);?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('crontabLog/index?page='.$pager->getLastPage().$path);?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
            
        
        </div>
      </div>