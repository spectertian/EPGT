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
          <form  action="<?php echo url_for('words/index');?>" id="adminForm" name="adminForm" method="post" >
            <?php include_partial('toolbarlist')?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
			输入关键字：
			<input type="text" name="q" value="<?php echo $q;?>">
			<input type="submit" onclick="submitform()" value="查询">
            
			<input type="text" name="addWord">
			<input type="submit" onclick="submitform('<?php echo url_for('words/add');?>')" value="添加">
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('words/index?page='.$pager->getFirstPage()."&q=$q");?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('words/index?page='.$pager->getPreviousPage()."&q=$q");?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('words/index?page='.$page."&q=$q");?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('words/index?page='.$pager->getNextPage()."&q=$q");?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('words/index?page='.$pager->getLastPage()."&q=$q");?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0" style="table-layout:fixed">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox" colspan="5" style="text-align: left; padding-left: 20px;"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll(this);" /> 全选</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" class="list_checkbox" colspan="5" style="text-align: left; padding-left: 20px;"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll(this);" /> 全选</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                <tr>
                  <?php 
                  $a = 0;
                  foreach ($pager->getResults() as $i => $rs): 
                  echo $a%5 == 0&&$a!=0?'</tr><tr>':'';
                  ?>
                        <td width="20%" style="text-align:left; padding-left: 20px;word-break:break-all;"><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="ids[]"><?php echo $rs->getWord();?></td>
                   <?php $a++; endforeach;?>
                </tr>
                <?php endif;?>
               </tbody> 
            </table>

            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('words/index?page='.$pager->getFirstPage()."&q=$q");?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('words/index?page='.$pager->getPreviousPage()."&q=$q");?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('words/index?page='.$page."&q=$q");?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('words/index?page='.$pager->getNextPage()."&q=$q");?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('words/index?page='.$pager->getLastPage()."&q=$q");?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
            <input type="hidden" name="batch_action" value="0" id="batch_action" />
        </form>
        </div>
      </div>