<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<div class="m">
    <?php echo form_tag_for($form, '@wiki', array('name'=>'adminForm')) ?>
        <?php echo $form->renderHiddenFields(false) ?>
        <?php if ($form->hasGlobalErrors()): ?>
          <?php echo $form->renderGlobalErrors() ?>
        <?php endif; ?>

        <?php $style   = $sf_request->getParameter('style');?>
        <?php if(isset($style)) {?>
        <?php  include_partial('template_'.$style, array('form' => $form));?>
        <?php } ?>
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