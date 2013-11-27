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
    if (action) {
        document.adminForm.batch_action.value=action;
    }
    if (typeof document.adminForm.onsubmit == "function") {
        document.adminForm.onsubmit();
    }
    document.adminForm.submit();
}
</script>
    <div id="content">
        <div class="content_inner">
<!--          <form action="" method="get">-->
            <?php include_partial('toolbarlist',array('pageTitle'=>$pageTitle))?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">


            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('terminal/index?page='.$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('terminal/index?page='.$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('terminal/index?page='.$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('terminal/index?page='.$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('terminal/index?page='.$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col" class="list_id">品牌</th>
                  <th scope="col" class="list_model">类型</th>
                  <th scope="col" class="list_created_at">版本</th>
                  <th scope="col" class="list_updated_at">创建时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col" class="list_id">品牌</th>
                  <th scope="col" class="list_model">类型</th>
                  <th scope="col" class="list_created_at">版本</th>
                  <th scope="col" class="list_updated_at">创建时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                  <?php foreach ($pager->getResults() as $i => $rs): ?>
                            <tr>
                              <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="id[]"></td>
                              <td><a href="<?php echo url_for('terminal/edit?id='.$rs->getId());?>"><?php echo $rs->getBrand();?></a></td>
                              <td><?php echo $rs->getClienttype();?></td>
                              <td><?php //echo json_encode((array)$rs->getVersion())?>
                              <?php foreach($rs->getVersion() as $key=>$val):?>
                              <?php echo $key.':'.$val['sp'];?><br />
                              <?php endforeach;?>
                              </td>
                              <td><?php echo ($created_at = $rs->getCreatedAt()) ? $created_at->format("Y-m-d H:i:s") : $rs->getUpdatedAt()->format("Y-m-d H:i:s");?></td>
                              <td><a href="<?php echo url_for("terminal/edit?id=".$rs->getId())?>" class="recommend">编辑</a> | <a href="<?php echo url_for("terminal/delete?id=".$rs->getId())?>" onclick="if(!confirm('确定删除吗？')) return false;">删除</a></td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>

            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('terminal/index?page='.$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('terminal/index?page='.$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('terminal/index?page='.$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('terminal/index?page='.$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('terminal/index?page='.$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
    </div>

            <div class="clear"></div>
<!--          </form>-->
        </div>
      </div>