<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<script type="text/javascript">
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
            <h2 class="content">
            <?php if (!$form->isNew()): ?>
                [ <?php echo __('更新 %%title%% 模板', array('%%title%%' => $program_index->getTitle()), 'messages') ?> ]
            <?php else:?>
                [ <?php echo __('创建模板', array(), 'messages') ?> ]
            <?php endif;?>
            </h2>
            <nav class="utility">
              <li class="save"><a href="#" onclick="javascript:submitform()">保存</a></li>
              <li class="back"><a href="<?php echo url_for("program_index/index")?>">返回列表</a></li>
              <?php if (!$form->isNew()): ?>
                <li class="delete"><a href="<?php echo url_for("program_index/delete?id=".$form->getObject()->getId())?>" onclick="if (confirm('确认删除吗？')) { var f = document.createElement('form'); f.style.display = 'none'; this.parentNode.appendChild(f); f.method = 'post'; f.action = this.href;var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', 'sf_method'); m.setAttribute('value', 'delete'); f.appendChild(m);var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', '_csrf_token'); m.setAttribute('value', 'cbea55e154ecad51393db77a2719fb46'); f.appendChild(m);f.submit(); };return false;" >删除</a></li>
              <?php endif;?>
            </nav>
          </header>
            <?php include_partial('global/flashes') ?>
            <?php echo form_tag_for($form, '@program_index', array('name'=>'adminForm')) ?>
                <?php echo $form->renderHiddenFields(false) ?>
                <?php if ($form->hasGlobalErrors()): ?>
                  <?php echo $form->renderGlobalErrors() ?>
                <?php endif; ?>
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3>基本资料</h3>
            <div class="widget-body">
           <ul class="wiki-meta">
            <?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
                <?php if ($fieldset == 'params'): ?>
                    <?php foreach ($fields as $name => $field): ?>
                        <li><?php echo $form[$name]->renderLabel($field->getConfig('label')) ?></li>
                        <li><?php echo $form[$name]->render($field->getConfig('attributes', array()) instanceof sfOutputEscaper ? $field->getConfig('attributes', array())->getRawValue() : $field->getConfig('attributes', array())) ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php if ('NONE' != $fieldset): ?>
                        <legend><?php echo __($fieldset, array(), 'messages') ?></legend>
                    <?php endif; ?>
                        <?php foreach ($fields as $name => $field): ?>
                        <li>
                            <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
                            <?php include_partial('program_index/form_field', array(
                              'name'       => $name,
                              'attributes' => $field->getConfig('attributes', array()),
                              'label'      => $field->getConfig('label'),
                              'help'       => $field->getConfig('help'),
                              'form'       => $form,
                              'field'      => $field,
                              'class'      => 'sf_admin_form_row sf_admin_'.strtolower($field->getType()).' sf_admin_form_field_'.$name,
                            )) ?>
                        </li>
                        <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
           </ul>
                </div>
              </div>
            </div>
            </form>
        </div>
      </div>
