<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php include_partial("wiki/screenshots"); ?>
	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content">修改:<?php echo $theme->getTitle()?></h2>
				<nav class="utility">
				 <li class="save"><a href="#" onclick="Save();">另存为新专题</a></li>
				  <li class="save"><a href="#" onclick="$('#themeForm').submit();">保存</a></li>
				  <li class="back"><a href="<?php echo url_for("theme/index")?>">返回列表</a></li>
				  <li class="delete"><a href="<?php echo url_for("theme/delete?id=".$theme->getId())?>" onclick="if (confirm('确认删除吗？')) { var f = document.createElement('form'); f.style.display = 'none'; this.parentNode.appendChild(f); f.method = 'post'; f.action = this.href;var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', 'sf_method'); m.setAttribute('value', 'delete'); f.appendChild(m);var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', '_csrf_token'); m.setAttribute('value', 'cbea55e154ecad51393db77a2719fb46'); f.appendChild(m);f.submit(); };return false;" >删除</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>
            <?php echo $form->renderFormTag(url_for("theme/edit?id=".$theme->getId()),array('name'=>'themeForm','id'=>'themeForm','method'=>'post')) ?>
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
						 <ul id="right">
							<li id="screenshots_index_Wwp6tDBJlBVf8JCxdCsAMJXfXcQ9571811">   
                        <?php if($form->getObject()->getImg()):?>    
								<!--<input id="theme_img" name="theme[img]" value="<?php echo $form->getObject()->getImg();?>" type="hidden" />-->			
								<img style="" id="screenshots_pic_Wwp6tDBJlBVf8JCxdCsAMJXfXcQ9571811" src="<?php echo file_url($form->getObject()->getImg());?>" alt="加载中"> 					    
						 <?php endif;?>	
							</li>
			            </ul>
                        
					 	<?php if($form->getObject()->getImg()):?>
					 	<a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=themescreenshotAdds">更改剧照</a>
					 	<?php else:?>
					 	<a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=themescreenshotAdds">上传剧照</a>
					 	<?php endif;?>
					 </li>

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
<script type="text/javascript">

function Save(){  
    $("#themeForm").attr("action", "/theme/add/ids/<?php echo $theme->getId()?>");  
	$("#themeForm").submit();  
}  

</script>

