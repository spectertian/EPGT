<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php include_partial("wiki/screenshots"); ?>
	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content">修改运营商</h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="$('#spForm').submit()">保存</a></li>
				  <li class="back"><a href="<?php echo url_for("sp/index")?>">返回列表</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>
			<!--
            <form method="POST" id="spForm" name="spForm" action="<?php echo url_for("sp/edit");?>">
            -->
            <?php echo $form->renderFormTag(url_for("sp/edit"),array('name'=>'spForm','id'=>'spForm')) ?>
			<?php echo $form->renderHiddenFields(); ?>
            <?php if ($form->hasGlobalErrors()): ?>
              <?php echo $form->renderGlobalErrors() ?>
            <?php endif; ?>
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3>运营商添加</h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
                     <!--
					 <li><label>运营商名称：</label><?php echo $form['name']->render(array("size" => "50"));?><?php echo $form['name']->getError() ?></li>  
					 <li><label>运营商简介：</label><?php echo $form["remark"]->render(array("cols" => "90", "rows" => "6", "style" => "width:100%")); ?></li>
                     -->
                     <?php foreach ($form as $field): ?>
                     <li>
                     <?php echo $field->renderLabel() ?>
                     <?php echo $field->render() ?>
                     </li>
                     <?php endforeach; ?> 
					 <li>
                        <input type="hidden" value="<?php echo $form['signal']->getValue();?>" name="id"/>
					 	<label>运营商图片:</label>
					 	<a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=spitemAdds">上传剧照</a></li>
                        <?php if($form->getDocument()->getLogo()):?>   
                        <ul id="right"> 	
                             <li>
								<img style="" id="tupian" src="<?php echo file_url($form->getDocument()->getLogo());?>" alt="加载中"/> 
                             </li>	   
                        </ul>        					    
						<?php endif;?>	                        
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
