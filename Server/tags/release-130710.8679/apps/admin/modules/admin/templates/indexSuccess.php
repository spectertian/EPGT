<?php use_helper('Date');?>
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
</script>

<div id="content">
        <div class="content_inner">
            <?php include_partial('toolbarList')?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
              <?php include_partial('search', array('form' => $filters, 'configuration' => $configuration)) ?>
               <div class="paginator">
                <span class="first-page"><a href="<?php echo url_for('@admin') ?>?page=1">最前页</a></span>
                <span class="prev-page"><a href="<?php echo url_for('@admin') ?>?page=<?php echo $pager->getPreviousPage() ?>">上一页</a></span>
                <span class="pages">
                  <?php foreach ($pager->getLinks() as $page): ?>
                    <?php if ($page == $pager->getPage()): ?>
                    <span class="present"><?php echo $page ?></span>
                    <?php else: ?>
                      <a href="<?php echo url_for('@admin') ?>?page=<?php echo $page ?>"><?php echo $page ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
                </span>
                <span class="next-page"><a href="<?php echo url_for('@admin') ?>?page=<?php echo $pager->getNextPage() ?>">下一页</a></span>
                <span class="last-page"><a href="<?php echo url_for('@admin') ?>?page=<?php echo $pager->getLastPage() ?>">最末页</a></span>
                <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
              </div>
              <div class="clear"></div>
            </div>
            </form>
            <form name="adminForm" action="<?php echo url_for('admin_collection', array('action' => 'batch')) ?>"  method="post">
                <?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
                    <input type="hidden" name="<?php echo $form->getCSRFFieldName() ?>" value="<?php echo $form->getCSRFToken() ?>" />
                <?php endif; ?>
                <input type="hidden" value="" name="batch_action">
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox" width="5%"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll(this);" /></th>
                  <th scope="col" class="list_id" width="5%">Id</th>
                  <th scope="col" class="list_model" width="10%">用户名</th>
                  <th scope="col" class="list_created_at"  width="10%">姓名</th>
                  <th scope="col" class="list_modified_by" width="15%">联系电话</th>
                  <th scope="col" class="list_modified_by" width="5%">状态</th>
                  <th scope="col" class="list_modified_by" width="20%">创建日期</th>
                  <th scope="col" class="list_modified_by" width="20%">登陆时间</th>
                  <th scope="col" class="list_action" width="10%">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox" onclick="checkAll(this);"></th>
                  <th scope="col">Id</th>
                  <th scope="col">用户名</th>
                  <th scope="col">姓名</th>
                  <th scope="col">联系电话</th>
                  <th scope="col">状态</th>
                  <th scope="col">创建日期</th>
                  <th scope="col">登陆时间</th>
                  <th scope="col">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php foreach ($pager->getResults() as $i => $admin):?>
                <tr>
                  <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $admin->getId();?>" name="ids[]"></td>
                  <td><a href="<?php echo url_for("admin/edit?id=".$admin->getId())?>"><?php echo $admin->getId()?></a></td>
                  <td><a href="<?php echo url_for("admin/edit?id=".$admin->getId())?>"><?php echo $admin->getUsername()?></a></td>
			      

                  <td><?php echo $admin->getName() ?></td>
                  <td><?php echo $admin->getPhone() ?></td>
                  <td>
                  <?php $value = $admin->getStatus()?>
                  <?php if ($value): ?>
                        <?php echo image_tag('accept.png', array('alt' => __('Checked', array(), 'sf_admin'), 'title' => __('Checked', array(), 'sf_admin'))) ?>
                  <?php else: ?>
                        <?php echo image_tag('delete.png', array('alt' => __('Unhecked', array(), 'sf_admin'), 'title' => __('UnChecked', array(), 'sf_admin'))) ?>
                  <?php endif; ?>
                </td>
				 <td><?php echo  $admin->getCreatedAt(); ?> </td>
                  <td><?php echo strtotime($admin->getLastLoginAt()) ? format_date($admin->getLastLoginAt(), "f") : '&nbsp;' ?></td>
                  <td><?php echo $helper->linkToEdit($admin, array(  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
                  <?php echo $helper->linkToDelete($admin, array(  'params' =>   array(  ),  'confirm' => '确定删除吗?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
              <?php if(count($pager->getResults())<=0):?><?php echo "<span style='color:red'>无匹配</span>"?><?php endif;?>
            </table>
              <div class="paginator">
                <span class="first-page"><a href="<?php echo url_for('@admin') ?>?page=1">最前页</a></span>
                <span class="prev-page"><a href="<?php echo url_for('@admin') ?>?page=<?php echo $pager->getPreviousPage() ?>">上一页</a></span>
                <span class="pages">
                  <?php foreach ($pager->getLinks() as $page): ?>
                    <?php if ($page == $pager->getPage()): ?>
                    <span class="present"><?php echo $page ?></span>
                    <?php else: ?>
                      <a href="<?php echo url_for('@admin') ?>?page=<?php echo $page ?>"><?php echo $page ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
                </span>
                <span class="next-page"><a href="<?php echo url_for('@admin') ?>?page=<?php echo $pager->getNextPage() ?>">下一页</a></span>
                <span class="last-page"><a href="<?php echo url_for('@admin') ?>?page=<?php echo $pager->getLastPage() ?>">最末页</a></span>
                <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
              </div>
            <div class="clear"></div>
          </form>
        </div>
      </div>