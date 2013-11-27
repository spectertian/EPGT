<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<content class="clr">
        	<menu class="menu" data-role="menu">
            	<ul>
            		<li class="there"><a href="<?php echo url_for("/") ?>" class="index">首页</a></li>
                    <li><a href="<?php echo url_for("live/index") ?>" class="zb">直播</a></li>
                    <li><a href="#" class="jj">剧集</a></li>
                    <li><a href="#" class="lm">栏目</a></li>
                    <li><a href="#" class="sz">设置</a></li>	
                </ul>
            </menu>
            
            <div class="content clr" data-role="page">
            	<div class="il">
                    <div class="showlist">
                     	<ul>
                        <?php 
                            //$styles=array('one there','two','three','four','five','six');
                            //$i=0;
                            foreach($images as $image) :
                        ?>  
                            <li><h2><a href="<?php echo url_for('wiki/show?slug='.$image->getTitle()) ?>"><img src="<?php echo thumb_url($image->getPic(), 815, 330)?>" alt="" title=""/><span><strong><?php echo $image->getTitle(); ?></strong><?php echo $image->getDesc(80);?></span></a></h2></li>
                        <?php 
                            //$i++;
                            endforeach;
                        ?>
                        </ul> 
                        <p><b class="one there"></b><b class="two"></b><b class="three"></b><b class="four"></b><b class="five"></b><b class="six"></b></p>   
                    </div>
                    
                    <div class="mlist">
                    	<h2 class="tit">播出栏目<a href="<?php echo url_for("live/index") ?>">查看更多</a></h2>
                        <ul class="clr">
                        <?php foreach($recommends as $recommend):?>
                            <?php $wiki = $recommend->getWiki() ?>
                        	<li>
                            <?php if(!empty($wiki)):?>
						    <a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug()) ?>"><img src="<?php echo  thumb_url($wiki->getCover(), 78, 98);?>" alt=""/><?php echo mb_strcut($wiki->getTitle(), 0, 12, 'utf-8');?></a>
						    <?php endif;?>
                            </li>
                        <?php endforeach;?>
                        </ul>
                    </div>
                </div>
                
                <div class="ir">
                	<div class="playnow">
                    	<h2 class="tit">正在播出<a href="<?php echo url_for("live/index") ?>">查看更多</a></h2>
                        <div id="wrapper_index">
                        <ul>
                            <?php include_component('default','liveList',array('module'=>'default','location'=>$location));?>
                        </ul>
                        </div>
                    </div>
                    
                    <div class="weekhot">
                    	<h2 class="tit">本周热点</h2>
                        <ul class="clr">
                        	<li><a href="#"><img src="/movcover/3.jpg" alt="" />海洋</a></li>
                            <li><a href="#"><img src="/movcover/3.jpg" alt="" />海洋</a></li>
                            <li><a href="#"><img src="/movcover/3.jpg" alt="" />海洋</a></li>
                            <li><a href="#"><img src="/movcover/3.jpg" alt="" />海洋</a></li>
                            <li><a href="#"><img src="/movcover/3.jpg" alt="" />海洋</a></li>
                        </ul>
                    </div>
                    
                </div>
            </div>
        </content>
<script type="text/javascript">
$(document).ready(function() {
    loaded_index();
})
function loaded_index() {
	scroll_index = new iScroll('wrapper_index', { vScrollbar: false});
}
</script>           