<script type="text/javascript">
function tv_logo(key,link) {
    $("#tv_logo").val(key);
    $(".anticipation > TD:eq(1) > IMG").attr('src',link).parents("TR").show();
    $("#removeLogo").parent("TD").show();
    tb_remove();
}

$(document).ready(function(){
    $("#removeLogo").click(function(){
        $("#tv_logo").val(0);
        $(".anticipation > TD:eq(1) > IMG").attr('src','').parents("TR").hide();
        $(this).hide();
    });
});

</script>
<fieldset class="adminform">
    <?php if ('NONE' != $fieldset): ?>
        <legend><?php echo __($fieldset, array(), 'messages') ?></legend>
    <?php endif; ?>
    <table class="admintable">
        <?php foreach ($fields as $name => $field): ?>
            <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
            <?php include_partial('channel/form_field', array(
              'name'       => $name,
              'attributes' => $field->getConfig('attributes', array()),
              'label'      => $field->getConfig('label'),
              'help'       => $field->getConfig('help'),
              'form'       => $form,
              'field'      => $field,
              'class'      => 'sf_admin_form_row sf_admin_'.strtolower($field->getType()).' sf_admin_form_field_'.$name,
            )) ?>
        <?php endforeach; ?>
    </table>
</fieldset>

