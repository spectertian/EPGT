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
            <h2 class="content">修改:<?php echo $tv_station->getName()?></h2>
            <nav class="utility">
              <li class="save"><a href="#" onclick="javascript:submitform()">保存</a></li>
              <li class="back"><a href="<?php echo url_for("tv_station/index")?>">返回列表</a></li>
              <li class="delete">
              <?php if($form->isNew()):?>
              <a href="#" onclick="alert('无删除内容!')">删除</a>
              <?php else:?>
              <a href="<?php echo url_for("tv_station/delete?id=".$tv_station->getId())?>" onclick="if (confirm('确认删除吗？')) { var f = document.createElement('form'); f.style.display = 'none'; this.parentNode.appendChild(f); f.method = 'post'; f.action = this.href;var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', 'sf_method'); m.setAttribute('value', 'delete'); f.appendChild(m);var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', '_csrf_token'); m.setAttribute('value', 'cbea55e154ecad51393db77a2719fb46'); f.appendChild(m);f.submit(); };return false;" >删除</a>
              <?php endif;?>
              </li>
            </nav>
          </header>
          <?php include_partial('global/flashes') ?>
           <?php echo form_tag_for($form, '@tv_station', array('name'=>'adminForm')) ?>
<!--           <input type="hidden"  name="tv_station[code]" value="<?php echo $tv_station->getCode(); ?>" />-->
            <?php echo $form->renderHiddenFields(); ?>
            <?php if ($form->hasGlobalErrors()): ?>
              <?php echo $form->renderGlobalErrors() ?>
            <?php endif; ?>
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3>基本资料</h3>
            <div class="widget-body">
           <ul class="wiki-meta">
           <?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
           <?php foreach ($fields as $name => $field): ?>
            <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
            <?php 
              $attributes = $field->getConfig('attributes', array());
              $label      = $field->getConfig('label');
              $help       = $field->getConfig('help');
             ?>
             <?php if ($fieldset !== 'params'): ?>
                <li><?php echo $form[$name]->renderLabel($label) ?>
                    <?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?>
                    <?php echo $form[$name]->renderError(); ?>
                </li>
            <?php endif;?>
                <?php endforeach; ?>
            <?php endforeach;?>
            <li>
                    <label for="province">省</label>
                    <input type='text' name='tv_station[province]' id='province' value='<?php echo $tv_station->getProvince(); ?>'>
                </li>
                <li>
                    <label for="city">市</label>
                    <input type='text' name='tv_station[city]' id='city' value='<?php echo $tv_station->getCity(); ?>'>
                </li>
            </ul>
                </div>
              </div>
            </div>


             <div style="width:33%; float:right;">
              <div class="widget">
                <h3>辅助函数</h3>
                <div class="widget-body">
                   <?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
                   <?php foreach ($fields as $name => $field): ?>
                    <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
                    <?php
                      $attributes = $field->getConfig('attributes', array());
                      $label      = $field->getConfig('label');
                      $help       = $field->getConfig('help');
                     ?>
                     <?php if ($fieldset == 'params'): ?>
                        <?php echo $form[$name] ?>
                    <?php endif;?>
                        <?php endforeach; ?>
                    <?php endforeach;?>
                    <div class="clear"></div>
                </div>
              </div>
            </div>   
          </form>
        </div>
      </div>
