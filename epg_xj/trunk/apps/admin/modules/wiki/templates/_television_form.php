<script type="text/javascript">
function add_drama_fancybox(){
   //重新加载插件
   <?php use_javascript('jquery.fancybox-1.3.4.pack.js')?>
//一般情况下的弹出层加载，添加分集的不是这个（待优化）
    $("#file-upload,#file-uploads").fancybox({
		'width'				: 960,
		'height'			: 600,
		'autoScale'			: false,
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'                  : 'iframe'
		//'autoDimensions'    : false
	});
}
//删除分期
function ajaxDelete(){
    $("#message-box").empty(content);
    meta_id = $(".meta_id").val();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo url_for('wiki/deleteStaging') ?>",
        data: "meta_id="+meta_id,
        success: function(data){
            html = "<div class=\"status success\"><p>删除成功<\/p></div>";
            $("#message-box").append(html);
            $("#opt_"+data).hide();
        },
        error: function() {
           html = "<div class=\"status error\"><p>删除失败<\/p></div>";
            $("#message-box").append(html);
       }


    });
}

//保存分期
function ajaxSave(){
    $("#message-box").empty(content);
    meta_wiki_id = $(".meta_wiki_id").val();
    meta_id = $(".meta_id").val();
    meta_title = $.trim($(".meta_title").val());
    meta_content = $.trim($(".meta_content").val());
    meta_guests = $(".meta_guests").val();
    meta_mark = $(".meta_mark").val();
    meta_screenshots = "";
    //得到图片的值
    $("input[type=checkbox]").each(function(){
      if($(this)[0].checked) {
       meta_screenshots += $(this).val()+',';
      }
    });
    
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?php echo url_for('wiki/saveStaging') ?>",
        data: "meta_wiki_id=" + meta_wiki_id + '&meta_id=' + meta_id + '&meta_title=' + meta_title + "&meta_content="+meta_content+"&meta_guests="+meta_guests+"&meta_mark="+meta_mark+"&meta_screenshots="+meta_screenshots,
        beforeSend: function(){
               $(this).val("正在保存栏目分期...").attr("disabled","disabled");
        },

        success: function(data){
            html = "<div class=\"status success\"><p>保存成功<\/p></div>";
            $("#message-box").append(html);
             $.each(eval(data), function(k,v) {
                var len = $("#opt_"+meta_mark).length;
                if (len <= 0) {
                      var html    = '<option value="'+v+'" id="opt_'+meta_mark+'">第 '+meta_mark+' 期</option>';
                      $("#staging").append(html);
                }
            });
            $(this).val("保存").attr("disabled","");

        },
        error: function() {
           html = "<div class=\"status error\"><p>保存失败<\/p></div>";
            $("#message-box").append(html);
       }

    })
}

//显示分期
function showstaging(){
    $("input[value='删除']").show();
    $("#message-box").empty(content);
    var meta_id = $('#staging').val();
    $.ajax({
       type: "POST",
       dataType: "josn",
       url: "<?php echo url_for('wiki/getStaging') ?>",
       data: "meta_id=" + meta_id,
       beforeSend: function(){
           $('#staging').val('栏目分期切换中...').attr('disabled','disabled');
       },
       success: function(data){
            $("#widgets ul").empty();
           $.each(eval("("+data+")"), function(k,v) {
              if( k == "meta_screenshots"){
                     for(var key in v){
                        html = "<li class=\"screenshots"+key+"\" id=\"meta_screenshots\"><span style=\"display:none\"><input type=\"checkbox\" name=\"meta_screenshots[]\" value="+v[key]+" checked=\"checked\"><\/span><\/li>";
                        $("#widgets ul").append(html);
                     }
              }else if( k == "meta_screenshots_url" ){
                  for(var key in v){
                       html = "";
                       html+= "<a href=\"#\"><img src="+v[key]+" width=\"100%\"><\/a>"
                       html+= "<a id=\"file-uploads\" class=\"update\" href=\"#\">更改<\/a> | <a href=\"#\" class=\"delete\" onclick=\"$(this).parent().remove();\">删除<\/a>";
                       $(".screenshots"+key).append(html);
                  }
              }else{
                  $('.'+k).val(v);  
              }
              if( k == "meta_mark" ){
                  meta_mark = v;
                  $('.'+k).text(meta_mark);
              }
                
          });
          $('#staging').attr('disabled', '');
          content = "<a id=\"file-uploads\" class=\"button\" href=\"<?php echo url_for('media/link'); ?>?function_name=columnDramaScreenshot&mark="+meta_mark+">上传剧照<\/a>";
          $("#widgets .action-box").empty(content);
          $("#widgets .action-box").append(content);
          $(".widget").hide();
          $("#widget").show();
          $("#widgets").show();
          //$("#video_1").show();
          //$('#video_add').attr('href', $('#video_add').val()+'&mark='+meta_mark);
          //alert($('#video_add').attr('href'));
          add_drama_fancybox();
       },
       error: function() {
           html = "<div class=\"status error\"><p>数据显示失败<\/p></div>";
           $("#message-box").append(html);
           $('#staging').val('选择分期').attr('disabled', '');
       }
    });
}
</script>
<?php include_partial("screenshots"); ?>
<?php 
    $sf_user->setAttribute('formcode',mt_rand(1,1000));
    $formcode = $sf_user->getAttribute('formcode');
?>

<div class="default" id="message-box"></div>
<form action="<?php echo url_for('wiki/'.($form->isNew() ? 'create' : 'update').(!$form->isNew() ? '?id='.$form->getDocument()->getId() : '')) ?>" method="post" name="adminForm">
    <input type='hidden' name='htmlformcode' value='<?php echo $formcode; ?>'>
    <?php echo $form->renderHiddenFields(); ?>
    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>
    <input type="hidden" id="wiki_model" name="model" value="<?php echo $form->getDocument()->getModelName(); ?>" />
    <input type="hidden" id="wiki_id" name="wiki_id" value="<?php echo $form->getDocument()->getId(); ?>" />
    <div style="float:left; width:65%;">
      <div class="widget">
        <h3>基本资料</h3>
        <div class="widget-body">
          <ul class="wiki-meta">
            <li><label>采集维基地址：</label> <input type="text" id="wiki-url"> <input name="" type="button" value="采集维基" id="get-wiki-btn" onclick="javascript:getSiteWikiData()"></li>
            <li><label>名称：</label><?php echo $form["title"]->render(array("size" => "50")); ?><?php echo $form['title']->getError() ?></li>
            <li><label>标签：</label><?php echo $form["tags"]->render(array("size" => "70")); ?><?php include_component('wiki', 'auto_tags', array('cate' => '栏目')) ?></li>
            <li><label>别名：</label><?php echo $form["alias"]->render(array("size" => "40")); ?></li>
            <li><label>主持人：</label><?php echo $form["host"]->render(array("size" => "40")); ?></li>
            <li><label>嘉宾：</label><?php echo $form["guest"]->render(array("size" => "40")); ?></li>
            <li><label>监制：</label><?php echo $form["producer"]->render(array("size" => "40")); ?></li>
            <li><label>播出时间：</label><?php echo $form["play_time"]->render(array("size" => "40")); ?></li>
            <li><label>播出时长：</label><?php echo $form["runtime"]->render(array("size" => "40")); ?></li>
            <li><label>播出频道：</label><?php echo $form["channel"]->render(array("size" => "40")); ?></li>
            <li><label>国家地区：</label><?php echo $form["country"]->render(array("size" => "40")); ?></li>
            <li><label>语言：</label><?php echo $form["language"]->render(array("size" => "40")); ?></li>
            <li><label>栏目介绍：</label><?php echo $form["content"]->render(array("rows" => "30")); ?></li>
            <li><label>看点：</label> <?php echo $form["aspect"]->render(array("size" => "40")); ?></li>
            <li><label>TvsouId：</label><?php echo $form["tvsou_id"]->render(array("size" => "20")); ?>
            	<?php if($form->getDocument()->getTvsouId()):?>
            		<a target="_blank" href="http://jq.tvsou.com/introhtml/<?php echo substr($form->getDocument()->getTvsouId(),0,-2);?>/index_<?php echo $form->getDocument()->getTvsouId();?>.htm">查看电视节目</a>
				<?php endif;?>
			</li>
            <input id="wiki_admin_id" type="hidden" name="wiki[admin_id]" value="<?php echo $sf_user->getAttribute('adminid');?>">
          </ul>
        </div>
      </div>

       <div class="widget" id="widget" style="display:none">
        <h3>基本资料</h3>
        <div class="widget-body">
          <ul class="wiki-meta">
            <input type="hidden" name="meta_wiki_id" class="meta_wiki_id">
            <input type="hidden" name="meta_id" class="meta_id">
            <input type="hidden" name="meta_mark" class="meta_mark">
            <li><label>期数/名称：</label> <span class="meta_mark"></span>&nbsp; <input class="meta_title" type="text" value="" style="width:70%"></li>
            <li><label>嘉宾：</label> <input type="text" value="" class="meta_guests" value=""></li>
            <li><label>本期介绍：</label> <textarea rows="30" class="meta_content"></textarea></li>
            <li><input type="submit" value="保存" onclick="javasecipt:ajaxSave()"><input onclick="javacsript:ajaxDelete()" type="submit" value="删除"></li>
          </ul>
        </div>
      </div>
    </div>

    <div style="width:33%; float:right;">
        <div class="diversity">
            <div class="widget" id="video_0">
                <h3>视频地址</h3>
                <div class="widget-body">
                  <ul class="vod">
                    <?php if ($PlayList = $form->getDocument()->getPlayList()) :?>
                    <?php foreach ($PlayList as $playlist):?> 
		                    <li>
		                        <a href="/video/videoMan?title=<?php echo $playlist->getTitle() ?>&site=<?php echo $playlist->getReferer()?>&model=television&wiki_id=<?php echo $playlist->getWikiId() ?>" target="_blank"><?php echo $playlist->getRefererZhcn()?>视频播放列表</a>
		                        <a href="<?php echo url_for('video/deleteVideoPlayList?id='.$playlist->getId().'&model='. $form->getDocument()->getModel())?>" 
		                           class="button" onClick="return confirm('确认删除该视频！')">删除</a>
		                    </li>
                    <?php endforeach;?>
                    <?php else:?>
                    <li>暂无视频地址</li>
                    <?php endif;?>
                  </ul>
                  <div class="clear"></div>
                  <?php if (!$form->isNew()) :?>
                  <div class="action-box">
                    <a href="<?php echo url_for('video/addVideoPlayList?id='.$form->getDocument()->getId())?>" class="button add-playlist">添加视频地址</a>
                  </div>
                  <?php endif;?>                  
                </div>
            </div>



            <!-- <div class="widget" id="video_1">
                <h3>分期视频地址</h3>
                <div class="widget-body" style="width: 100%;">
                  <ul class="vod" style="width: 100%;">
                    <?php if ($VideoList = $form->getDocument()->getVideosByWiki()) :?>
                    <?php foreach ($VideoList as $videolist):?> 
		                    <li style="width: 100%;">
		                    <?php if($videolist->getReferer()=='qiyi'):?>
		                    	<a href="<?php echo $videolist->getUrl()?>" target="_blank"><?php echo '【'.$videolist->getRefererZhcn().'】'.$videolist->getTitle()?></a>
		                    <?php else:?>
		                        <a href="<?php echo $videolist->getUrl()?>" target="_blank"><?php echo '【'.$videolist->getRefererZhcn().'】'.$videolist->getMark().' : '.$videolist->getTitle()?></a>
		                    <?php endif;?>
		                        <a href="<?php echo url_for('video/deleteVideo?id='.$videolist->getId().'&model='. $form->getDocument()->getModel())?>" 
		                           class="button" onClick="return confirm('确认删除该分集视频！')">删除</a>
		                    </li>
                    <?php endforeach;?>
                    <?php else:?>
                    <li>暂无分期视频地址</li>
                    <?php endif;?>
                  </ul>
                  <div class="clear"></div>  
                  <?php if (!$form->isNew()) :?>
                  <div class="action-box">
                    <a href="<?php echo url_for('video/addVideo?id='.$form->getDocument()->getId())?>" class="button add-videolist" id="video_add">添加分期视频地址</a>
                  </div>
                  <?php endif;?>                                
                </div>
            </div>     -->    
        </div>
        <!--分期剧照 -->
        <div class="widget" id="widgets" style="display:none">
          <h3>第<span class="meta_mark"></span>期上传剧照</h3>
          <div class="filmstill">
              <ul>
             </ul>
            <div class="action-box">
            </div>
          </div>
        </div>
        <div class="widget">
        <?php include_partial("cover", array("form" => $form)); ?>
          <div class="filmstill">
            <?php $screenshots = $form->getDocument()->getScreenshots(); ?>
            <ul  id="right">
              <?php if (!empty ($screenshots)):?>
                <?php foreach ($screenshots as $key => $screenshot):?>
              <li>
                <input type="hidden" value="<?php echo $screenshot;?>" name="wiki[screenshots][]" id="screenshots_<?php echo $key;?>" />
                <a href="#"><img src="<?php echo file_url($screenshot);?>" id="screenshots_pic_<?php echo $key;?>" width="100%"></a>
                 <a id="file-uploads" class="update" href="<?php echo url_for('media/link'); ?>?function_name=screenshotAdds">更改</a> | <a href="#" class="delete" onclick="$(this).parent().remove();">删除</a>
              </li>
                <?php endforeach;?>
            <?php endif;?>
            </ul>
            <div class="action-box">
              <a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=screenshotAdds">上传剧照</a>
            </div>
          </div>
        </div>
    </div>
  </form>