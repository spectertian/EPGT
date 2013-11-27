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
        	<?php include_partial('toolbarList')?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
                <?php include_partial('search', array('q' => $q))?>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for("developer/index?q=$q&page=".$developer->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for("developer/index?q=$q&page=".$developer->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                <?php $links    = $developer->getLinks(5);?>
                <?php foreach ($links as $key => $value):?>
                    <?php if ($value == $developer->getPage()):?>
                        <span class="present"><?php echo $value;?></span>
                    <?php else:?>
                        <a href="<?php echo url_for("developer/index?q=$q&page=".$value );?>"><?php echo $value;?></a>
                    <?php endif;?>
                <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for("developer/index?q=$q&page=".$developer->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for("developer/index?q=$q&page=".$developer->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $developer->getPage();?>/<?php echo $developer->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>
            </form>
          <form method="post" id="adminForm" name="adminForm" action="<?php echo url_for('@developer');?>/delete">
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col">名称</th>
                  <th scope="col" style="width: 23%;">apikey&secretkey</th>
                  <th scope="col" style="width: 20%;">描述</th>
                  <th scope="col" style="width: 5%;">状态</th>
                  <th scope="col">更新时间</th>
                  <th scope="col">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col">名称</th>
                  <th scope="col">apikey&secretkey</th>
                  <th scope="col">描述</th>
                  <th scope="col">状态</th>
                  <th scope="col">更新时间</th>
                  <th scope="col">操作</th>
                </tr>
              </tfoot>
              <tbody>
              <?php if(isset ($developer)):?>
                <?php foreach ($developer->getResults() as $key => $rs): ?>
                <tr>
                  <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="ids[]"></td>
                  <td><a href="<?php echo url_for('developer/edit?id='.$rs->getId());?>"><?php echo $rs->getName();?></a></td>
                  <td><?php echo $rs->getApikey();?></br><?php echo $rs->getSecretkey();?></td>
                  <td style="word-break:break-all;"><?php echo $rs->getDesc();?></td>
                  <td>
                  <?php $value = $rs->getState()?>
                  <?php if ($value): ?>
                        <?php echo image_tag('accept.png', array('alt' => __('Checked', array(), 'sf_admin'), 'title' => __('Checked', array(), 'sf_admin'))) ?>
                  <?php else: ?>
                        <?php echo image_tag('delete.png', array('alt' => __('Unhecked', array(), 'sf_admin'), 'title' => __('UnChecked', array(), 'sf_admin'))) ?>
                  <?php endif; ?>           
                  </td>
                  <td><?php echo ($updated_at = $rs->getUpdatedAt()) ? $updated_at->format("Y-m-d H:i:s") : $rs->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                  <td><?php if ($rs->getState() == '1'):?><a href="<?php echo url_for("developer/lockDeveloper?id=".$rs->getId())?>" class="recommend">锁定</a> | <?php endif;?>
                  <?php if ($rs->getState() == '0'):?><a href="<?php echo url_for("developer/lockDeveloper?id=".$rs->getId().'&unlock=1')?>" class="recommend">解除锁定</a> | <?php endif;?>
                  <a href="<?php echo url_for("developer/delete?id=".$rs->getId())?>" class="delete"  onClick="return window.confirm('确定删除吗?');">删除</a></td>
                </tr>
                <?php endforeach;?>
              <?php endif;?>
              </tbody>
            </table>
			<input type="hidden" value="" name="batch_action">
		  </form>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for("developer/index?q=$q&page=".$developer->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for("developer/index?q=$q&page=".$developer->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                <?php $links    = $developer->getLinks(5);?>
                <?php foreach ($links as $key => $value):?>
                    <?php if ($value == $developer->getPage()):?>
                        <span class="present"><?php echo $value;?></span>
                    <?php else:?>
                        <a href="<?php echo url_for("developer/index?q=$q&page=".$value );?>"><?php echo $value;?></a>
                    <?php endif;?>
                <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for("developer/index?q=$q&page=".$developer->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for("developer/index?q=$q&page=".$developer->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $developer->getPage();?>/<?php echo $developer->getLastPage();?>)</span>
            </div>

            <div class="clear"></div>
<!--          </form>-->
        </div>
      </div>