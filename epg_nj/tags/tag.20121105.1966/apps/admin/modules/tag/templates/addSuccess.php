<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content">添加标签</h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="$('#tagsForm').submit()">保存</a></li>
				  <li class="back"><a href="<?php echo url_for("tag/index")?>">返回列表</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>

            <?php echo $form->renderFormTag(url_for("tag/add"),array('name'=>'tagsForm','id'=>'tagsForm')) ?>
			<?php echo $form->renderHiddenFields(); ?>
            <?php if ($form->hasGlobalErrors()): ?>
              <?php echo $form->renderGlobalErrors() ?>
            <?php endif; ?>
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3>添加标签</h3>
				<div class="widget-body" style="height: 300px;">
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
		
            <div style="width:33%; float:right;">
              <div class="widget">
                <h3>辅助函数</h3>                
              </div>
            </div>   
          </form>
        </div>
      </div>
