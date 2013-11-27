<script type="text/javascript">
<?php if(!$form->isNew()):?>
$(document).ready(function(){
    $.ajax({
       type: "GET",
       url: "<?php echo url_for('admin/auths') ?>",
       data: "admin_id=<?php echo $form->getObject()->getId(); ?>",
       dataType: "json",
       success: function(data){
          $.each(data, function(i, v){
              $('input[value="' + v.credential +'"]').attr('checked', 'checked');
          })
       }
    });
});
<?php endif;?>

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
          <header>
            <h2 class="content">管理用户</h2>
            <nav class="utility">
              <li class="save"><a href="#" onclick="javascript:submitform()">保存</a></li>
              <li class="back"><a href="<?php echo url_for("admin/index")?>">返回列表</a></li>
              <li class="delete">
              <?php if($form->isNew()):?>
              <a href="#" onclick="alert('无删除内容!')">删除</a>
              <?php else:?>
                  <?php echo link_to("".__("Delete", array(), 'sf_admin'), $helper->getUrlForAction('delete'), $form->getObject(), array('method' => 'delete', 'confirm' => "确认删除吗？" ? __("确认删除吗?", array(), 'sf_admin') : "确认删除吗?")) ?>
              <?php endif;?>
              </li>
            </nav>
          </header>
          <?php include_partial('global/flashes') ?>           
          <?php echo form_tag_for($form, '@admin', array('name'=>'adminForm')) ?>
                <?php echo $form->renderHiddenFields(); ?>
                <?php if ($form->hasGlobalErrors()): ?>
                  <?php echo $form->renderGlobalErrors() ?>
                <?php endif; ?>
            <div style="float:left; width:100%;">
              <div class="widget">
                <h3>基本资料</h3>
                <div class="widget-body">
                  <?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
                    <?php include_partial('admin/form_fieldset', array('admin' => $admin, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      