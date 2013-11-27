<?php use_helper('I18N', 'Date') ?>
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
                <?php include_partial('search',array('form' => $filters, 'configuration' => $configuration))?>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('@program_index') ?>?page=1">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('@program_index') ?>?page=<?php echo $pager->getPreviousPage() ?>">上一页</a>
              </span>
              <span class="pages">
              <?php foreach ($pager->getLinks() as $page): ?>
                <?php if ($page == $pager->getPage()): ?>
                    <span><?php echo $page ?></span>
                <?php else: ?>
                  <a href="<?php echo url_for('@program_index') ?>?page=<?php echo $page ?>"><?php echo $page ?></a>
                <?php endif; ?>
              <?php endforeach; ?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('@program_index') ?>?page=<?php echo $pager->getNextPage() ?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('@program_index') ?>?page=<?php echo $pager->getLastPage() ?>">最末页</a></span>
              <span class="page-total"></span>
            </div>

              <div class="clear"></div>
            </div>
            </form>
           <form action="<?php echo url_for('program_index_collection', array('action' => 'batch')) ?>" name="adminForm" method="post">
               <?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
                  <input type="hidden" name="<?php echo $form->getCSRFFieldName() ?>" value="<?php echo $form->getCSRFToken() ?>" />
               <?php endif; ?>
               <input type="hidden" name="batch_action" value="" />
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll();" /></th>
                  <th scope="col" class="list_id">Id</th>
                  <th scope="col" class="list_model">所属频道</th>
                  <th scope="col" class="list_created_at">模板名称</th>
                  <th scope="col" class="list_modified_by">创建时间</th>
                  <th scope="col" class="list_modified_by">更新时间</th>
                  <th scope="col" class="list_modified_by">已存节目</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" class="list_checkbox"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll();" /></th>
                  <th scope="col" class="list_id">Id</th>
                  <th scope="col" class="list_model">所属频道</th>
                  <th scope="col" class="list_created_at">模板名称</th>
                  <th scope="col" class="list_modified_by">创建时间</th>
                  <th scope="col" class="list_modified_by">更新时间</th>
                  <th scope="col" class="list_modified_by">已存节目</th>
                </tr>
              </tfoot>
              <tbody>
             <?php foreach ($pager->getResults() as $i => $program_index): ?>
                <tr>
                  <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $program_index->getId()?>" name="ids[]"></td>
                  <td><?php echo link_to($program_index->getId(), 'program_index_edit', $program_index) ?></td>
                  <td><?php echo $program_index->getChannel() ?></td>
                  <td><?php echo $program_index->getTitle() ?></td>
                  <td><?php echo false !== strtotime($program_index->getCreatedAt()) ? format_date($program_index->getCreatedAt(), "y-M-d H:m:s") : '&nbsp;' ?></td>
                  <td><?php echo false !== strtotime($program_index->getUpdatedAt()) ? format_date($program_index->getUpdatedAt(), "y-M-d H:m:s") : '&nbsp;' ?></td>
                  <td><?php echo get_partial('program_index/other', array('type' => 'list', 'program_index' => $program_index)) ?></td>
                </tr>
              <?php endforeach;?>
              </tbody>
            </table>
            </form>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('@program_index') ?>?page=1">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('@program_index') ?>?page=<?php echo $pager->getPreviousPage() ?>">上一页</a>
              </span>
              <span class="pages">
              <?php foreach ($pager->getLinks() as $page): ?>
                <?php if ($page == $pager->getPage()): ?>
                    <span><?php echo $page ?></span>
                <?php else: ?>
                  <a href="<?php echo url_for('@program_index') ?>?page=<?php echo $page ?>"><?php echo $page ?></a>
                <?php endif; ?>
              <?php endforeach; ?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('@program_index') ?>?page=<?php echo $pager->getNextPage() ?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('@program_index') ?>?page=<?php echo $pager->getLastPage() ?>">最末页</a></span>
              <span class="page-total"></span>
            </div>
            <div class="clear"></div>
        </div>
      </div>