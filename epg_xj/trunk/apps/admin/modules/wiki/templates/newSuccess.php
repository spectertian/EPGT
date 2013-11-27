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
<!-- <meta http-equiv="expires" content="Fri, 12 Jan 2001 18:18:18 GMT">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache"> 
 -->
<div id="content">
    <div class="content_inner">
      <header>
        <h2 class="content">新建：<?php echo $form->getDocument()->getDisplayName();?></h2>
          <nav class="utility">
      <li class="save"><a href="#" onclick="javascript:submitform()" class="toolbar">保存</a></li>
      <li class="back"><a href="<?php echo url_for("wiki/index")?>">返回列表</a></li>
    </nav>
      <?php if( $form->getDocument()->getModelName() == "teleplay" ): ?>
            <div class="header-meta">
              <a href="#" class="button" onClick="javascript:showMain();">编辑主条目</a>
              <input type='hidden' name='htmlformcode' value='<?php echo $formcode; ?>'>
              <label>
                请选择：
                  <select id="showDrama" onchange="showDramaAction();">
                    <?php if (!empty ($dramas)): ?>
                        <?php foreach ($dramas as $key => $drama):?>
                        <option value="<?php echo $key;?>" id="opt_<?php echo $key;?>">第 <?php echo $key;?> 集</option>
                        <?php endforeach;?>
                    <?php else:?>
                        <option>还没有分集</option>
                    <?php endif;?>
                   </select>
              </label>
              <a href="javascript:dramaAdd();" class="button">添加分集剧情</a>
              <a href="javascript:dramaTotalAdd();" class="button">添加总集数</a>
            </div>
      <?php endif;?>
      </header>
      <?php include_partial('global/flashes') ?>
      <?php include_partial($form->getDocument()->getModelName().'_form', array('form'=>$form))?>
    </div>
  </div>