<?php include_partial("screenshots"); ?>
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

function eleRemove(id) {
    $('#drama_'+id).remove();
    $('#opt_'+id).remove();
    $("#video_"+id).remove();
    $("#screenshots_"+id).remove();
    //显示主目录
    $("#widget-body").show();
    $(".diversity .widget-body,#video_0, #screenshots_0").show();
}

//显示主目录
function showMain(){
    //分集
    $('.diversity').children().each(function(){
        $(this).hide();
    })
    //主目录
    $("#widget-body").show()
    //右边
    $(".diversity .widget-body,#video_0, #screenshots_0").show();
}

//下拉框变动事件
function showDramaAction(){
    //隐藏显示的分集链接
    $("#widget-body").hide();
    $('.diversity').children().each(function(){
        $(this).hide();
    })
    var value   = $("#showDrama").val();
    var ele     = $("#drama_td_content_"+value);
        if (ele.hasClass("wikiNo")) {
            new TracWysiwyg(ele.get(0));
            ele.removeClass("wikiNo");
        }
    //显示分集
    $("#drama_" + value).show();
    //显示分集视频链接
    $("#video_" + value).show();
    //显示分集剧照
    $("#screenshots_" + value).show();
    return false;
}

//集数下拉添加
function showDramaOptionAdd(id) {
    var html  = '<option value="'+id+'" id="opt_'+id+'">第 '+id+' 集</option>';
    $("#showDrama").append(html);
}

//剧情添加
function dramaAdd() {
    var id  = prompt("请输入集数");
    var len = $("#drama_"+id).length;
    if (len > 0) {
        alert('剧情已存在!');
        return ;
    }

    if (id == '' || isNaN(id)) {
        alert('请输入一个数字 ID');
        return;
    }
    //取消
    if( id == null){
        return ;
    }
    titles = $("#wiki_title").val() + "第" + id + "集";

    //隐藏主目录,已经显示的分集
    $("#widget-body").hide();
    $('.diversity').children().each(function(){
        $(this).hide();
    })
    
    var content = getDramaHtml(id);
    var screenshots = getScreenshotsHtml(id);
    //增加剧集
    $("#drama-list").append(content);
    $("#screenshots_list").append(screenshots);
    add_drama_fancybox();
    showDramaOptionAdd(id);
    var ele = $("#drama_td_content_"+ id);
    if (ele.hasClass("wikiNo")) {
        new TracWysiwyg(ele.get(0));
        ele.removeClass("wikiNo");
    }
    $("#drama_td_title_"+id).focus();
}
//添加总集数
function dramaTotalAdd() {
    var id  = prompt("请输入总集数");
    if (id == '' || isNaN(id)) {
        alert('请输入一个数字 ID');
        return;
    }
    
    //取消
    if( id == null){
        return ;
    }
    
    var i = 1;
    for(i=1;i<=id;i++)
    {
        var len = $("#drama_"+i).length;
        if (len <= 0) 
        {
            titles = $("#wiki_title").val() + "第" + i + "集";

            //隐藏主目录,已经显示的分集
            $("#widget-body").hide();
            $('.diversity').children().each(function(){
                $(this).hide();
            })
            
            var content = getDramaHtml(i);
            var screenshots = getScreenshotsHtml(i);
            //增加剧集
            $("#drama-list").append(content);
            $("#screenshots_list").append(screenshots);
            add_drama_fancybox();
            showDramaOptionAdd(i);
            var ele = $("#drama_td_content_"+ i);
            if (ele.hasClass("wikiNo")) {
                new TracWysiwyg(ele.get(0));
                ele.removeClass("wikiNo");
            }
            $("#drama_td_title_"+i).focus();
        }        
    }



}
function getDramaHtml(id) {
    var str = '';
    str+="      <div class=\"widget-body\" id=\"drama_"+id+"\">";
    str+="          <ul class=\"wiki-meta\">";
    str+="          <li><label>分集/名称：<\/label> <span>第"+id+"集<\/span>&nbsp; <input type=\"text\" value="+titles+" style=\"width:70%\"  name=\"meta[title][]\"><\/li>";
    str+="          <input type=\"hidden\" name=\"meta[mark][]\" value="+id+">";
    str+="          <li><label>分集介绍：<\/label> <textarea rows=\"20\" name=\"meta[content][]\"></textarea></li>";
    str+="      <li><label><\/label>  <input type=\"submit\" onclick=\"javascript:eleRemove('"+id+"');\" value=\"删除\">   <\/li> <\/ul><\/div>";
    return str;
}

function getScreenshotsHtml(id){
    str="";
    str+="  <div  id=\"screenshots_"+id+"\" class=\"widget\">";
    str+="      <h3>第"+id+"集上传剧照<\/h3>";
    str+="      <div class=\"filmstill\">";
    str+="       <ul id=\"right_"+id+"\"><\/ul>";
    str+="      <div class=\"action-box\">";
    str+="          <a id=\"file-uploads\" class=\"button\" href=\"<?php echo url_for('media/link'); ?>?function_name=dramaScreenshot&mark="+id+"\">上传剧照<\/a>";
    str+="              <\/div><\/div><\/div>";
    return str;
}

$(document).ready(function(){
    var value   = $("#showDrama").val();
    drama_content = $("#drama_td_content_"+value);
    //$("#drama_"+value).show();
    if (drama_content.hasClass("wikiNo")) {
        new TracWysiwyg(drama_content.get(0));
        drama_content.removeClass("wikiNo");
    }
});
</script> 
   <?php 
    $sf_user->setAttribute('formcode',mt_rand(1,1000));
    $formcode = $sf_user->getAttribute('formcode');
?>
<form action="<?php echo url_for('wiki/'.($form->isNew() ? 'create' : 'update').(!$form->isNew() ? '?id='.$form->getDocument()->getId() : '')) ?>" method="post" name="adminForm">
    <input type='hidden' name='htmlformcode' value='<?php echo $formcode; ?>'>
    <div style="float:left; width:65%;">
      <div class="widget" id="admintable">
        <h3>基本资料</h3>
        <div class="widget-body" id="widget-body">
          <ul class="wiki-meta">
            <?php echo $form->renderHiddenFields(); ?>
            <?php if ($form->hasGlobalErrors()): ?>
              <?php echo $form->renderGlobalErrors() ?>
            <?php endif; ?>
            <li>
                <label>采集维基地址：</label>
                <input type="text" id="wiki-url">
                <input id="get-wiki-btn" type="button" value="采集维基" onclick="javascript:getSiteWikiData()">
            </li>
            <li><label>名称：</label> <?php echo $form["title"]->render(array("size" => "50")); ?><?php echo $form['title']->getError() ?></li>
            <li><label>别名：</label> <?php echo $form["alias"]->render(array("size" => "50")); ?></li>
            <li><label>标签：</label> <?php echo $form["tags"]->render(array("size" => "70")); ?>
            <?php include_component('wiki', 'auto_tags', array('cate' => '电视剧')) ?></li>
            <li><label>导演：</label> <?php echo $form["director"]->render(array("size" => "40")); ?></li>
            <li><label>编剧：</label> <?php echo $form["writer"]->render(array("size" => "40")); ?></li>
            <li><label>主演：</label> <?php echo $form["starring"]->render(array("size" => "40")); ?></li>
            <li><label>制片国家/地区：</label><?php echo $form["country"]->render(array("size" => "40")); ?></li>
            <li><label>语言：</label> <?php echo $form["language"]->render(array("size" => "40")); ?></li>
            <li><label>制作年份：</label> <?php echo $form["produced"]->render(array("size" => "40")); ?></li>
            <li><label>上映日期：</label> <?php echo $form["released"]->render(array("size" => "40")); ?></li>
            <li><label>总集数：</label> <?php echo $form["episodes"]->render(array("size" => "40")); ?></li>
            <li><label>更新集数：</label> <?php echo $form["update_episodes"]->render(array("size" => "40")); ?></li>
            <li><label>看点：</label> <?php echo $form["aspect"]->render(array("size" => "40")); ?></li>
            <li><label>发行商：</label> <?php echo $form["distributor"]->render(array("size" => "40")); ?></li>
            <li><label>剧情简介：</label><?php echo $form["content"]->render(array( "rows" => "20")); ?></li>
            <li><label>IMDB：</label><?php echo $form["imdb"]->render(array("size" => "60")); ?></li>
			<li><label>DoubanId：</label><?php echo $form["douban_id"]->render(array("size" => "60")); ?></li>
            <li><label>TvsouId：</label><?php echo $form["tvsou_id"]->render(array("size" => "20")); ?>
            	<?php if($form->getDocument()->getTvsouId()):?>
            		<a target="_blank" href="http://jq.tvsou.com/introhtml/<?php echo substr($form->getDocument()->getTvsouId(),0,-2);?>/index_<?php echo $form->getDocument()->getTvsouId();?>.htm">查看电视节目</a>
				<?php endif;?>
			</li>
            <input id="wiki_admin_id" type="hidden" name="wiki[admin_id]" value="<?php echo $sf_user->getAttribute('adminid');?>">
            <input type="hidden" id="wiki_model" name="model" value="<?php echo $form->getDocument()->getModelName(); ?>" />
          </ul>
        </div>
        <div class="diversity" id="drama-list">
        <?php if($metas = $form->getDocument()->getWikiMeta()) :?>
            <?php foreach ($metas as $meta):?>
            <?php if($meta->getMark()!=0):?>
            <div class="widget-body" id="drama_<?php echo $meta->getMark()?>" style="display:none">
              <ul class="wiki-meta">
                <li><label>分集/名称：</label> <span>第<?php echo $meta->getMark()?>集</span>&nbsp; <input type="text" value="<?php echo $meta->getTitle()?>" style="width:70%" name="meta[title][]"></li>
                <input type="hidden" name="meta[mark][]" value="<?php echo $meta->getMark()?>">
                <li><label>分集介绍：</label> <textarea rows="20" name="meta[content][]"><?php echo $meta->getContent()?></textarea></li>
                <li><input type="button" onclick="javascript:eleRemove('<?php echo $meta->getMark();?>');" value="删除"></li>
              </ul>
            </div>
            <?php endif;?>
           <?php endforeach;?>
        <?php endif;?>
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
                    	<?php if($playlist->getReferer() == 'tps') :?>
                    		<?php  $tpsvideos = $playlist->getVideos();?>
                    		<?php foreach ($tpsvideos as $tpsvideo):?> 
								<li>
									<a href="<?php echo $tpsvideo->getUrl()?>" target="_blank"><?php echo $playlist->getRefererZhcn()?>视频播放链接<?php echo $tpsvideo->getMark()?></a>
									<a href="<?php echo url_for('video/delete?id='.$tpsvideo->getId().'&model='. $form->getDocument()->getModel().'&ref=tps')?>" 
                   						class="button" onClick="return confirm('确认删除该视频！')">删除</a>
								</li>                 			
                    		 <?php endforeach;?>
                    	<?php else:?>
		                    <!-- <li>
		                        <a href="<?php echo $playlist->getUrl()?>" target="_blank"><?php echo $playlist->getRefererZhcn()?>视频播放列表</a>
		                        <a href="<?php echo url_for('video/delete?id='.$playlist->getId().'&model='. $form->getDocument()->getModel())?>" 
		                           class="button" onClick="return confirm('确认删除该视频！')">删除</a>
		                    </li>
		                     -->
		                     <li>
		                        <a href="/video/videoMan?title=<?php echo $playlist->getTitle() ?>&site=<?php echo $playlist->getReferer()?>&model=teleplay&wiki_id=<?php echo $playlist->getWikiId() ?>" target="_blank"><?php echo $playlist->getRefererZhcn()?>视频播放列表</a>
		                        <a href="<?php echo url_for('video/delete?id='.$playlist->getId().'&model='. $form->getDocument()->getModel())?>" 
		                           class="button" onClick="return confirm('确认删除该视频！')">删除</a>
		                    </li>
                    	<?php endif;?>
                    <?php endforeach;?>
                    <?php else:?>
                    <li>暂无视频地址</li>
                    <?php endif;?>
                  </ul>
                  <div class="clear"></div>
                  <?php if (!$form->isNew()) :?>
                  <div class="action-box">
                    <a href="<?php echo url_for('video/crawler?id='.$form->getDocument()->getId())?>" class="button add-playlist">添加视频地址</a>
                  </div>
                  <?php endif;?>                  
                </div>
            </div>

            <?php if($metas && $PlayList) :?>
            <?php foreach ($metas as $meta):?>
            <div class="widget" style="display:none" id="video_<?php echo $meta->getMark()?>">
                <h3>视频地址 - 第 <?php echo $meta->getMark()?> 集</h3>
                <div class="widget-body">
                  <ul class="vod">
                    <?php if($videos = $form->getDocument()->getVideosByMark($meta->getMark())) :?>
                        <?php foreach($videos as $video) :?>
                        <li><a href="<?php echo $video->getUrl()?>" target="_blank"><?php echo $video->getRefererZhcn()?>第 <?php echo $meta->getMark()?> 集视频播放</a></li>
                        <?php endforeach;?>
                    <?php else :?>
                        <li>暂无视频地址</li>
                    <?php endif;?>
                  </ul>
                  <div class="clear"></div>
                  
                </div>
            </div>
            <?php endforeach;?>
            <?php endif;?>
        </div>
        <!-- wiki剧照 -->
        <div class="diversity" id="screenshots_list">
            <div class="widget" id="screenshots_0">
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

        <!--分集剧照 -->
        <?php if( !empty ($metas)) :?>
        <?php foreach ($metas as $meta):?>
        <div class="widget" id="screenshots_<?php echo $meta->getMark()?>" style="display:none">
          <h3>第 <?php echo $meta->getMark()?> 集上传剧照</h3>
          <div class="filmstill">
            <?php $screenshots = $meta->getScreenshots(); ?>
              <ul  id="right_<?php echo $meta->getMark()?>">
              <?php if (!empty ($screenshots) ):?>
                <?php foreach ($screenshots as $key => $screenshot):?>
              <li>
                <input type="hidden" value="<?php echo $screenshot;?>" name="meta[screenshots][<?php echo $meta->getMark()?>][]" id="screenshots_<?php echo $key;?>" />
                <a href="#"><img src="<?php echo file_url($screenshot);?>" id="screenshots_pic_<?php echo $key;?>" width="100%"></a>
                 <a id="file-uploads" class="update" href="<?php echo url_for('media/link'); ?>?function_name=screenshotAdds">更改</a> | <a href="#" class="delete" onclick="$(this).parent().remove();">删除</a>
              </li>
                <?php endforeach;?>
            <?php endif;?>
            </ul>
            <div class="action-box">
              <a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=dramaScreenshot&mark=<?php echo $meta->getMark()?>">上传剧照</a>
            </div>
          </div>
        </div>
        <?php endforeach;?>
        <?php endif;?>
    </div>
  </form>