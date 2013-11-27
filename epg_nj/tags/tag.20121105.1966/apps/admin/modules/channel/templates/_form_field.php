<?php if ($field->isPartial()): ?>
  <?php include_partial('channel/'.$name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php elseif ($field->isComponent()): ?>
  <?php include_component('channel', $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php else: ?>
<?php if($name == 'logo'):?>
<?php $logo = $form->getObject()->getLogo() ?>
<tr>
    <td class="key">台标:</td>
    <td><a class="thickbox" href="<?php echo url_for('media/link');?>?function_name=tv_logo&amp;height=600&amp;width=1000&amp;TB_iframe=true">点击上传</a></td>
    <td style="display: <?php echo ( strlen($logo) <= 1 ) ? 'none' : '' ; ?>;"><a href="#" id="removeLogo">删除台标</a></td>
    <td><input type="hidden" name="channel[logo]" id="tv_logo" value="<?php echo (strlen($logo) <= 1 ) ? 0 : $logo; ?>" /></td>
</tr>
<tr class="anticipation" style="display:<?php echo (strlen($logo) <= 1 ) ? 'none' : '' ; ?>;">
    <td class="key">台标预览</td>
    <td><img src="<?php echo (strlen($logo) <= 1 ) ? 0 : file_url($logo); ?>" /></td>
</tr>
<?php else: ?>
<tr>
    <td class="key"><?php echo $form[$name]->renderLabel($label) ?></td>
    <td>
        <?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?>
    </td>
</tr>
<?php endif ?>
<?php endif; ?>