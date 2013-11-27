<?php include_partial("screenshots"); ?>
<script type="text/javascript">
//豆瓣信息自动填充  tianzhongsheng-ex@huan.tv 2013-11-06 17:47:00
function douban_filling(id){
    $.ajax({
        url: '<?php echo url_for('wiki/GetDouban')?>',
        type: 'post',
        dataType: 'json',
        data: {'id': id},
        success: function(data){
        	$.each(data,function(index,content){  
				$('#'+index).val(content);
        		});  
//            return

        },
    });
   
}
</script>
<?php 
    $sf_user->setAttribute('formcode',mt_rand(1,1000));
    $formcode = $sf_user->getAttribute('formcode');
?>
<form action="<?php echo url_for('wiki/'.($form->isNew() ? 'create' : 'update').(!$form->isNew() ? '?id='.$form->getDocument()->getId() : '')) ?>" method="post" name="adminForm">
    <input type='hidden' name='htmlformcode' value='<?php echo $formcode; ?>'>
    <div style="float:left; width:65%;">
      <div class="widget">
        <h3>基本资料</h3>
        <div class="widget-body">
          <ul class="wiki-meta">
            <?php echo $form->renderHiddenFields(); ?>
            <?php if ($form->hasGlobalErrors()): ?>
              <?php echo $form->renderGlobalErrors() ?>
            <?php endif; ?>
            <input type="hidden" id="wiki_model" name="model" value="<?php echo $form->getDocument()->getModelName(); ?>" />
            <li><label>采集维基地址：</label> <input type="text" id="wiki-url" value=""> <input id="get-wiki-btn" type="button" value="采集维基" onclick="javascript:getSiteWikiData()"></li>
            <li><label>名称：</label><?php echo $form['title']->render(array("size" => "50"));?><?php echo $form['title']->getError() ?></li>
            <li><label>别名：</label><?php echo $form["alias"]->render(array("size" => "50")); ?></li>
            <li><label>标签：</label><?php echo $form["tags"]->render(array("size" => "70")); ?><?php include_component('wiki', 'auto_tags', array('cate' => '电影')) ?></li>
            <li><label>导演：</label><?php echo $form["director"]->render(array("size" => "40")); ?></li>
            <li><label>编剧：</label><?php echo $form["writer"]->render(array("size" => "40")); ?></li>
            <li><label>主演：</label><?php echo $form["starring"]->render(array("size" => "40")); ?></li>
            <li><label>制片国家/地区：</label><?php echo $form["country"]->render(array("size" => "40")); ?></li>
            <li><label>语言：</label><?php echo $form["language"]->render(array("size" => "40")); ?></li>
            <li><label>制作年份：</label><?php echo $form["produced"]->render(array("size" => "40")); ?></li>
            <li><label>上映日期：</label><?php echo $form["released"]->render(array("size" => "40")); ?></li>
            <li><label>片长：</label><?php echo $form["runtime"]->render(array("size" => "40")); ?></li>
            <li><label>发行商：</label><?php echo $form["distributor"]->render(array("size" => "40")); ?></li>
            <li><label>看点：</label> <?php echo $form["aspect"]->render(array("size" => "40")); ?></li> 
            <li><label>剧情简介：</label>
                <?php echo $form["content"]->render(array("cols" => "90", "rows" => "30", "style" => "width:100%")); ?>
            </li>
            <li><label>TvsouId：</label><?php echo $form["tvsou_id"]->render(array("size" => "20")); ?>
            	<?php if($form->getDocument()->getTvsouId()):?>
            		<a target="_blank" href="http://jq.tvsou.com/introhtml/<?php echo substr($form->getDocument()->getTvsouId(),0,-2);?>/index_<?php echo $form->getDocument()->getTvsouId();?>.htm">查看电视节目</a>
				<?php endif;?>
			</li>
			<li><label>IMDB：</label><?php echo $form["imdb"]->render(array("size" => "60")); ?></li>
			<li><label>DoubanId：</label><?php echo $form["douban_id"]->render(array("size" => "60")); ?> 
				<?php if($form->getDocument()->getDoubanId()):?>
					<a target="_blank" href="http://movie.douban.com/subject/<?php echo $form->getDocument()->getDoubanId();?>/">查看豆瓣</a>
					<input type="button" name="filling" value="自动填充" onclick="douban_filling(<?php echo $form->getDocument()->getDoubanId();?>)"/>
				<?php endif;?>
			</li>
            <input id="wiki_admin_id" type="hidden" name="wiki[admin_id]" value="<?php echo $sf_user->getAttribute('adminid');?>">
          </ul>
        </div>
      </div>
    </div>
    <div style="width:33%; float:right;">
      <div class="widget">
        <h3>视频地址</h3>
        <div class="widget-body">
          <ul class="vod">
            <?php if ($videos = $form->getDocument()->getVideos()) :?>
            <?php foreach ($videos as $o_video):?>
            <li>
                <a href="<?php echo $o_video->getUrl()?>" target="_blank"><?php echo $o_video->getRefererZhcn()?>视频播放链接</a>
                <a href="<?php echo url_for('video/delete?id='.$o_video->getId().'&model='. $form->getDocument()->getModel())?>" 
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
            <a href="<?php echo url_for('video/crawler?id='.$form->getDocument()->getId())?>" class="button add-playlist">添加视频地址</a>
            <a href="<?php echo url_for('video/crawlerDongman?id='.$form->getDocument()->getId())?>" class="button add-playlist">添加动漫视频地址</a>
          </div>
          <?php endif;?>
        </div>
      </div>

     <div class="widget">
        <?php include_partial("cover", array("form" => $form)); ?>
          <div class="filmstill">
            <?php $screenshots = $form->getDocument()->getScreenshots(); ?>
            <ul id="right">
              <?php if (!empty ($screenshots)):?>
                <?php foreach ($screenshots as $key => $screenshot):?>
              <li>
                <input type="hidden" value="<?php echo $screenshot;?>" name="wiki[screenshots][]" id="screenshots_<?php echo $key;?>" />
                 <a href="#"><img src="<?php echo file_url($screenshot);?>" id="screenshots_pic_<?php echo $key;?>" width="100%"></a>
                 <a id="file-uploads" class="update" href="<?php echo url_for('media/link'); ?>?function_name=screenshotAdds">更改</a> | 
                 <a href="#" class="delete" onclick="$(this).parent().remove();">删除</a>
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