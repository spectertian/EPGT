<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php include_partial("wiki/screenshots"); ?>
	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content">提交新专题</h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="$('#themeForm').submit()">保存</a></li>
				  <li class="back"><a href="<?php echo url_for("theme/index")?>">返回列表</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>
			<!--
            <form method="POST" id="themeForm" name="themeForm" action="<?php echo url_for("theme/add");?>">
            -->
            <?php echo $form->renderFormTag(url_for("theme/add"),array('name'=>'themeForm','id'=>'themeForm')) ?>
			<?php echo $form->renderHiddenFields(); ?>
            <?php if ($form->hasGlobalErrors()): ?>
              <?php echo $form->renderGlobalErrors() ?>
            <?php endif; ?>
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3>基本资料</h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
                     <!--
					 <li><label>专题名称：</label><?php echo $form['title']->render(array("size" => "50"));?><?php echo $form['title']->getError() ?></li>  
					 <li><label>专题简介：</label><?php echo $form["remark"]->render(array("cols" => "90", "rows" => "6", "style" => "width:100%")); ?></li>
                     -->
                     <?php foreach ($form as $field): ?>
                     <li>
                     <?php echo $field->renderLabel() ?>
                     <?php echo $field->render() ?>
                     </li>
                     <?php endforeach; ?> 
					 <li>
					 	<label>专题图片:</label>
					 	<a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=themescreenshotAdds">上传剧照</a></li>
					 </li>
					<ul id="right">
		            </ul> 
				  </ul>
                  
				</div>
              </div>
            </div> 
			</form>
            <div style="width:33%; float:right;">
              <div class="widget">
                <h3>辅助函数</h3>                
              </div>
            </div>   
          </form>
        </div>
      </div>
