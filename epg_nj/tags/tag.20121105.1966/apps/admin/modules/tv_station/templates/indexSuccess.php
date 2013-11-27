<?php use_helper('Date');?>
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
            <?php include_partial('toolbarList')?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
              <?php include_partial('search', array('form' => $filters, 'configuration' => $configuration)) ?>
              <div class="paginator">
                <span class="first-page"><a href="<?php echo url_for('@tv_station') ?>?page=1">最前页</a></span>
                <span class="prev-page"><a href="<?php echo url_for('@tv_station') ?>?page=<?php echo $pager->getPreviousPage() ?>">上一页</a></span>
                <span class="pages">
                    <?php foreach ($pager->getLinks() as $page): ?>
                        <?php if ($page == $pager->getPage()): ?>
                         <span class="present"><?php echo $page ?></span>
                        <?php else: ?>
                          <a href="<?php echo url_for('@tv_station') ?>?page=<?php echo $page ?>"><?php echo $page ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </span>
                <span class="next-page"><a href="<?php echo url_for('@tv_station') ?>?page=<?php echo $pager->getNextPage() ?>">下一页</a></span>
                <span class="last-page"><a href="<?php echo url_for('@tv_station') ?>?page=<?php echo $pager->getLastPage() ?>">最末页</a></span>
                <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
              </div>
              <div class="clear"></div>
            </div>
            </form>
             <form action="<?php echo url_for('tv_station_collection', array('action' => 'batch')) ?>" name="adminForm" method="post">
            <?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
              <input type="hidden" name="<?php echo $form->getCSRFFieldName() ?>" value="<?php echo $form->getCSRFToken() ?>" />
            <?php endif; ?>
            <input type="hidden" name="batch_action" value="" />
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll();" /></th>
                  <th scope="col" class="list_id">Id</th>
                  <th scope="col" class="list_model">名称</th>
                  <th scope="col" class="list_created_at">排序</th>
                  <th scope="col" class="list_modified_by">发布</th>
                  <th scope="col" class="list_modified_by">已有频道</th>
                  <th scope="col" class="list_modified_by">创建时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" class="list_checkbox"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll();" /></th>
                  <th scope="col" class="list_id">Id</th>
                  <th scope="col" class="list_model">名称</th>
                  <th scope="col" class="list_created_at">排序</th>
                  <th scope="col" class="list_modified_by">发布</th>
                  <th scope="col" class="list_modified_by">已有频道</th>
                  <th scope="col" class="list_modified_by">创建时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </tfoot>
              <tbody>

                <?php if (count($pager->getResults())>0): ?>
                <?php foreach ($pager->getResults() as $tv_station):?>
                <tr>
                  <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $tv_station->getId();?>" name="ids[]"></td>
                  <td class="sf_admin_text sf_admin_list_td_id"><a href="<?php echo url_for("tv_station/edit?id=".$tv_station->getId())?>"><?php echo $tv_station->getId()?></a></td>
                  <td><a href="<?php echo url_for("tv_station/edit?id=".$tv_station->getId())?>"><?php echo $tv_station->getName()?></a></td>
                  <td><?php echo $tv_station->getSort() ?></td>
                  <td class="sf_admin_list_td_publish" style="cursor:pointer;">
                    <?php $value = $tv_station->getPublish()?>
                    <?php if ($value): ?>
                      <?php echo image_tag('accept.png', array('alt' => __('Checked', array(), 'sf_admin'), 'title' => __('Checked', array(), 'sf_admin'))) ?>
                    <?php else: ?>
                      <?php echo image_tag('delete.png', array('alt' => __('Unhecked', array(), 'sf_admin'), 'title' => __('UnChecked', array(), 'sf_admin'))) ?>
                    <?php endif; ?>
                  </td>
                  <td><a href="<?php echo url_for('@channel').'?id='.$tv_station->getId();?>">管理</a></td>
                  <td><?php echo false !== strtotime($tv_station->getCreatedAt()) ? format_date($tv_station->getCreatedAt(), "y-M-d H:m:s") : '&nbsp;' ?></td>
                  <td><a href="<?php echo url_for("tv_station/edit?id=".$tv_station->getId())?>" class="recommend">编辑</a> | <a href="<?php echo "tv_station/delete?id=".$tv_station->getId()?>" onClick="return window.confirm('确定删除吗?');">删除</a></td>
                </tr>
                <?php endforeach;?>
                <?php else: ?>  
                <tr><td colspan="8">无匹配信息</td></tr>
                <?php endif; ?>

              </tbody>
            </table>
              <div class="paginator">
                <span class="first-page"><a href="<?php echo url_for('@tv_station') ?>?page=1">最前页</a></span>
                <span class="prev-page"><a href="<?php echo url_for('@tv_station') ?>?page=<?php echo $pager->getPreviousPage() ?>">上一页</a></span>
                <span class="pages">
                  <?php foreach ($pager->getLinks() as $page): ?>
                    <?php if ($page == $pager->getPage()): ?>
                    <span class="present"><?php echo $page ?></span>
                    <?php else: ?>
                      <a href="<?php echo url_for('@tv_station') ?>?page=<?php echo $page ?>"><?php echo $page ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
                </span>
                <span class="next-page"><a href="<?php echo url_for('@tv_station') ?>?page=<?php echo $pager->getNextPage() ?>">下一页</a></span>
                <span class="last-page"><a href="<?php echo url_for('@tv_station') ?>?page=<?php echo $pager->getLastPage() ?>">最末页</a></span>
                <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
              </div>
            <div class="clear"></div>
          </form>
        </div>
      </div>