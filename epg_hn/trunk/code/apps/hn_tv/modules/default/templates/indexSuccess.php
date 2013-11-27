<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<div class="main">
	<div class="ctrl_box">
    	<div class="wkbox">
        	<h2>河南卫视 <span><?php echo date('H:s');?><a href="<?php echo url_for('wiki/currentProgram') ?>">详情</a><a href="<?php echo url_for('channel/index?channel_code=2c854868563485135dd486801057dd6e') ?>">节目表</a><a href="#">推荐</a><a href="<?php echo url_for('search/index') ?>">搜索</a></span></h2>
            <?php include_component('default','liveList',array('location'=>$location));?>
        </div>
        <?php foreach($images as $image):?>         
        <a href="<?php echo url_for('wiki/show?slug='.$image->getTitle()) ?>" class="ad">
            <img src="<?php echo thumb_url($image->getSmallPic(), 200, 130)?>" alt="<?php echo $image->getTitle(); ?>" title=""/>
        </a>
       <?php  endforeach;?>  
    </div>
</div>
<script>
	$(function(){
		var $w=$('body').width();
		var $h=$('body').height();
		//alert($w);
		
		//$('.list h3').text($w);
		//$('.wkbox h2').text($h);
	});
</script>