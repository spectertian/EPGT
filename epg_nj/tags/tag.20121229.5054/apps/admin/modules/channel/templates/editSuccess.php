<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<script type="text/javascript">
/*插入显示图片*/
function tv_logo(key,link) {
    $("#tv_logo").val(key);
    $(".admintable img").attr('src',link);
     $(".admintable img").show();

    $("#removeLogo").show();
    
    $("#fancybox-overlay") .hide();
    $("#fancybox-wrap").hide();
}

$(document).ready(function(){
  /**
   *是否显示删除台标
   */
  if(  $(".admintable img").attr('src') == 0 ){
      $("#removeLogo").hide();
  }
  
 /**
 * 加载弹出层
 */
$("#file-upload,#file-uploads").fancybox({
        'width'				: 960,
        'height'			: 600,
        'autoScale'			: false,
        'transitionIn'		: 'none',
        'transitionOut'		: 'none',
        'type'                  : 'iframe'
});

/**
 * 删除图片
 */
    $("#removeLogo").click(function(){
        $("#tv_logo").val(0);
        $(".admintable img").attr('src','').hide();
        $(this).hide();
    });



});
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
            <h2 class="content"><?php echo __('更新 %%name%% 频道', array('%%name%%' => $channel->getName()), 'messages') ?></h2>
            <nav class="utility">
              <li class="save"><a href="#" onclick="javascript:submitform()">保存</a></li>
              <li class="back"><a href="<?php echo url_for("channel/index")?>">返回列表</a></li>
              <li class="delete">
              <?php if($form->isNew()):?>
              <a href="#" onclick="alert('无删除内容!')">删除</a>
              <?php else:?>
              <a href="<?php echo url_for("channel/delete?id=".$channel->getId())?>" onclick="if (confirm('确认删除吗？')) { var f = document.createElement('form'); f.style.display = 'none'; this.parentNode.appendChild(f); f.method = 'post'; f.action = this.href;var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', 'sf_method'); m.setAttribute('value', 'delete'); f.appendChild(m);var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', '_csrf_token'); m.setAttribute('value', 'cbea55e154ecad51393db77a2719fb46'); f.appendChild(m);f.submit(); };return false;" >删除</a>
              <?php endif;?>
              </li>
            </nav>
          </header>
          <?php include_partial('global/flashes') ?>
              <?php echo form_tag_for($form, '@channel', array('name'=>'adminForm')) ?>
                <?php echo $form->renderHiddenFields() ?>
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
             <?php if ($name !== 'logo'): ?>
                <li><?php echo $form[$name]->renderLabel($label) ?>
                <?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?>
                <?php echo $form[$name]->renderError()?>
                </li>
            <?php endif;?>
                <?php endforeach; ?>
            <?php endforeach;?>
            </ul>
                </div>
              </div>
            </div>




             <div style="width:33%; float:right;">
            <div class="widget">
                <h3>上传</h3>
                <div class="widget-body">
                    <?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
                       <?php foreach ($fields as $name => $field): ?>
                        <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
                        <?php
                          $attributes = $field->getConfig('attributes', array());
                          $label      = $field->getConfig('label');
                          $help       = $field->getConfig('help');
                         ?>
                         <?php if ($name == 'logo'): ?>
                            <?php $logo = $form->getObject()->getLogo() ?>
                              <div class="admintable">
                                <input type="hidden" name="channel[logo]" id="tv_logo" value="<?php echo (strlen($logo) <= 1 ) ? 0 : $logo; ?>" />
                                <a id="file-upload" href="<?php echo url_for('media/link');?>?function_name=tv_logo">更改或上传台标</a>
                                <a href="#" id="removeLogo">删除台标</a>
                                <a href="#"><img src="<?php echo (strlen($logo) <= 1 ) ? 0 : file_url($logo); ?>" /></a>
                              </div>
                            <?php endif;?>
                        <?php endforeach; ?>
                    <?php endforeach;?>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>



