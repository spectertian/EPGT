<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content">添加固定点播推荐</h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="$('#RecommandFixForm').submit()">保存</a></li>
				  <li class="back"><a href="<?php echo url_for("recommand_fix/index")?>">返回列表</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>
            <?php echo $form->renderFormTag(url_for("recommand_fix/add"),array('name'=>'RecommandFixForm','id'=>'RecommandFixForm')) ?>
			<?php echo $form->renderHiddenFields(); ?>
            <?php if ($form->hasGlobalErrors()): ?>
              <?php echo $form->renderGlobalErrors() ?>
            <?php endif; ?>
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3>添加固定点播推荐</h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
                     <?php foreach ($form as $field): ?>
                     <li>
                     <?php echo $field->renderLabel() ?>
                     <?php echo $field->render() ?>
                     </li>
                     <?php endforeach; ?>                 
					<ul id="right">
		            </ul> 
				  </ul>
                  
				</div>
              </div>
            </div> 
			</form>  
        </div>
      </div>
