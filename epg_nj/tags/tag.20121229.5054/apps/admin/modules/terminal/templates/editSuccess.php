<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<script language="javascript">
function textadd(){
	text=$("#terminal_version").attr("value");
	version=$("#version").attr("value");
	sp="";
	$("input[name='sp']:checked").each(function(){
	   if(sp!=""){
		   sp=sp+','+$(this).val();
	   }else{
		   sp=$(this).val();
	   }
    });
	if(text!=''){
		zhi=text+"\n"+'"'+version+'":{"sp":"'+sp+'"}';
	}else{
		zhi='"'+version+'":{"sp":"'+sp+'"}';
	}
	$("#terminal_version").attr("value",zhi);
}
</script>
<?php include_partial("wiki/screenshots"); ?>
	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content">编辑终端类型</h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="$('#terminalForm').submit()">保存</a></li>
				  <li class="back"><a href="<?php echo url_for("terminal/index")?>">返回列表</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>
			<!--
            <form method="POST" id="spForm" name="spForm" action="<?php echo url_for("terminal/edit");?>">
            -->
            <?php echo $form->renderFormTag(url_for("terminal/edit"),array('name'=>'terminalForm','id'=>'terminalForm')) ?>
			<?php echo $form->renderHiddenFields(); ?>
            <?php if ($form->hasGlobalErrors()): ?>
              <?php echo $form->renderGlobalErrors() ?>
            <?php endif; ?>
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3>编辑终端类型 </h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
                     <!--
					 <li><label>品牌：</label><?php echo $form['brand']->render(array("size" => "50"));?></li> 
                     <li><label>类型：</label><?php echo $form['clienttype']->render(array("size" => "50"));?></li> 
					 <li><label>版本：</label><?php echo $form['version']->render(array("cols" => "90", "rows" => "6", "style" => "width:100%")); ?></li> 
                     -->
                     <?php foreach ($form as $name => $field): ?>
                     
                     <li>
                     <?php echo $field->renderLabel() ?>
                     
                     <?php if($name=='version'):?>
                     <?php 
                     $version=$field->getValue();
                     $version_json='';
                     foreach($version as $key=>$val):
                         $version_json.='"'.$key.'"'.':{"sp":"'.$val['sp'].'"}'.chr(13);
                     endforeach;
                     $version_json=rtrim($version_json,chr(13));
                     ?>
                     <textarea rows="6" cols="90" style="width:100%" name="terminal[version]" id="terminal_version"><?php echo $version_json;?></textarea>
                     <?php else:?>
                     <?php echo $field->render();?>
                     <?php endif;?>
                     </li>                     
                     
                     <?php endforeach; ?>      
                     <li><label></label><input type="hidden" value="<?php echo $id;?>" name="id"/>
                       版本号：<input name="version" type="text" id="version" size="10" style="width:50px;"/>支持运营商：
                       <?php foreach($sp as $value):?>
                       <input name="sp" type="checkbox" id="sp" value="<?php echo $value->getSignal()?>" /><?php echo $value->getName()?>
                       <?php endforeach;?>
                       <input type="button" name="button" id="button" value="增加" onclick="javascript:textadd();"/>
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
          
        </div>
      </div>
