[?php use_stylesheets_for_form($form) ?]
[?php use_javascripts_for_form($form) ?]

<div class="m">
    [?php echo form_tag_for($form, '@<?php echo $this->params['route_prefix'] ?>', array('name'=>'adminForm')) ?]
        [?php echo $form->renderHiddenFields(false) ?]

        [?php if ($form->hasGlobalErrors()): ?]
          [?php echo $form->renderGlobalErrors() ?]
        [?php endif; ?]
        
        <div class="col width-60">
        [?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?]
        [?php if ($fieldset == 'params'): ?]
        </div>
        <div class="col width-40">
            <div id="menu-pane" class="pane-sliders">
                <div class="panel">
                    <h3 class="title jpane-toggler-down" id="param-page"><span>辅助参数</span></h3>
                    <div class="jpane-slider content">
                        <table width="100%" class="paramlist admintable" cellspacing="1">
                            [?php foreach ($fields as $name => $field): ?]
                            <tr>
                                <td width="40%" class="paramlist_key"><span class="editlinktip">[?php echo $form[$name]->renderLabel($field->getConfig('label')) ?]</span></td>
                                <td class="paramlist_value">[?php echo $form[$name]->render($field->getConfig('attributes', array()) instanceof sfOutputEscaper ? $field->getConfig('attributes', array())->getRawValue() : $field->getConfig('attributes', array())) ?]</td>
                            </tr>
                            [?php endforeach; ?]
                        </table>
                    </div>
                </div>
            </div>
        [?php else: ?]
        [?php include_partial('<?php echo $this->getModuleName() ?>/form_fieldset', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?]
        [?php endif; ?]
        [?php endforeach; ?]
        </div>
        
    </form>
    <div class="clr"></div>
</div>
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