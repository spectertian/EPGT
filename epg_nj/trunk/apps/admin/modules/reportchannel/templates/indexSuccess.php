<script type="text/javascript">
//全选
function checkAll(object)
{
    var flag    = object.checked;
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

function state()
{
    admin_form = document.getElementById('adminForm');
    admin_form.action = "<?php echo url_for('reportchannel/batchState')?>";
    admin_form.submit();
}

function delRecommendRsId(rs_id) {
    if(rs_id.length==0){
        alert("recommend id is null");
    }else{
        $.ajax({
            type: "POST",
            data: {'id' : rs_id},
            url: '<?php echo url_for('reportchannel/delete')?>',
            success: function(msg) {
                if(msg==1) {
                    alert("删除成功!");
                    window.location.reload();
                }
                if(msg==2) {
                    alert("删除失败");
                }
            }
        });
    }
}
</script>
    <div id="content">
        <div class="content_inner">
<!--          <form action="" method="get">-->
            <?php include_partial('toolbarList',array('pageTitle'=>'频道别名'))?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
            <?php include_partial('select', array('d' => $d,'n'=>$n,'s'=>$s))?>
            
<form method="post" name="adminForm" id="adminForm" action="<?php echo url_for('reportchannel/index');?>/batch">
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for("reportchannel/index?d=$d&n=$n&s=$s&page=".$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for("reportchannel/index?d=$d&n=$n&s=$s&page=".$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for("reportchannel/index?d=$d&n=$n&s=$s&page=".$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for("reportchannel/index?d=$d&n=$n&s=$s&page=".$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for("reportchannel/index?d=$d&n=$n&s=$s&page=".$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll(this);"></th>
                  <th scope="col">dtvsp</th>
                  <th scope="col">频道别名</th>
                  <th scope="col">创建时间</th>
                  <th scope="col">更新时间</th>
                  <th scope="col">状态</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox" onclick="checkAll(this);"></th>
                  <th scope="col">dtvsp</th>
                  <th scope="col">频道别名</th>
                  <th scope="col">创建时间</th>
                  <th scope="col">更新时间</th>
                  <th scope="col">状态</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                  <?php foreach ($pager->getResults() as $i => $rs): ?>
                            <tr>
                              <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="ids[]"></td>
                              <td><?php echo $rs->getDtvsp();?></td>
                              <td><?php echo $rs->getName();?></td>
                              <td><?php $rs_created_at = $rs->getCreatedAt(); echo $rs_created_at->format("Y-m-d H:i:s");?></td>
                              <td><?php echo ($created_at = $rs->getCreatedAt()) ? $created_at->format("Y-m-d H:i:s") : $rs->getUpdatedAt()->format("Y-m-d H:i:s");?></td>
                              <td>
                                    <?php if ($rs->getState()): ?>
                                      <?php echo image_tag('accept.png', array('alt' => __('Checked', array(), 'sf_admin'), 'title' => __('已处理', array(), 'sf_admin'))) ?></a>
                                    <?php else: ?>
                                       <a href="<?php echo url_for("reportchannel/state?id=".$rs->getId())?>"><?php echo image_tag('delete.png', array('alt' => __('Unhecked', array(), 'sf_admin'), 'title' => __('未处理：点击处理', array(), 'sf_admin'))) ?></a>
                                    <?php endif; ?>
                              </td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>
<input type="hidden" value="7ae5f9bb4952382f3637ea68bfafe589" name="_csrf_token">
<input type="hidden" value="" name="batch_action">
</form>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for("reportchannel/index?d=$d&n=$n&s=$s&page=".$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for("reportchannel/index?d=$d&n=$n&s=$s&page=".$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for("reportchannel/index?d=$d&n=$n&s=$s&page=".$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for("reportchannel/index?d=$d&n=$n&s=$s&page=".$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for("reportchannel/index?d=$d&n=$n&s=$s&page=".$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

            <div class="clear"></div>
<!--          </form>-->
        </div>
      </div>