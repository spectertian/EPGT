<script type="text/javascript">
function submitform(action){
    if (action) {
            document.adminForm.batch_action.value=action;
    }
    if(typeof document.adminForm.onsubmit == "function"){
            document.adminForm.onsubmit();
    }
    document.adminForm.submit();
}


function categoryChange(category_id){
    tb_remove();
    $('#change_category_id').val(category_id);
    submitform('BatchChangeCategorys');
}
</script>
      <div id="content">
        <div class="content_inner">
            <header>
              <h2 class="content">文件管理</h2>
              <nav class="utility">
                <li class="delete">
                <a href="#" onclick="javascript:if(confirm('确定删除吗？')){submitform('batchDelete')}" class="toolbar">批量删除</a>
                </li>
                <li class="recommended">
                <a href="#" onclick="javascript:submitform('batchChangeCategory')" class="toolbar">批量移动</a>
                </li>
              </nav>
            </header>
            <?php include_partial('global/flashes') ?>
            <div id="file-wrap">
              <div class="inner">
                <aside>
                <?php include_partial('media/categorys', array("popup" => false)) ?>
                </aside>
                <div id="file-content">
                  <div class="content_inner">
                  <form action="<?php echo url_for('attachments_collection', array('action' => 'batch')) ?>" name="adminForm" id="adminForm" method="post">
                      <?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
                            <input type="hidden" name="<?php echo $form->getCSRFFieldName() ?>" value="<?php echo $form->getCSRFToken() ?>" />
                      <?php endif; ?>
                      <input type="hidden" value="" name="batch_action">
                      <input type="hidden" name="change_category_id" id="change_category_id" value="0" />
                      <div id="media_list"><?php include_component("media", "list",array('category_id'=>$category_id,'page'=>$page,'popup'=>$popup,'source_name'=>$source_name)) ?></div>
                   </form>
                    </div>
                    <br>
                    <?php include_partial('media/link_uploader',array('categorys'=>$categorys)) ?>
                    </div>
                  </div>
                </div>
        </div>
      </div>
      