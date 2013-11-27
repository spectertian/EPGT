<div class="main">
	<div class="clr">
    	<div class="left">
        	<div class="infos">
            	<img src="<?php echo thumb_url($wiki->getCover(), 200, 282)?>" alt=""/>
                <ul>
                	<li>
                    	<h2><?php echo $wiki->getTitle()?><a href="#">点击收藏</a></h2>
                    </li>
                    <?php include_partial('introduction', array('wiki' => $wiki,'model'=>$wiki->getModel()))?>
                </ul>
            </div>
            
            <div class="dbzy">
            	<h2>点播资源 <a href="#">优酷</a><a href="#">新浪</a></h2>
                <ul class="clr">
                    <?php include_partial('dianbo', array('wiki' => $wiki,'model'=>$wiki->getModel()))?>
                </ul>
            </div>
        </div>
        
        <div class="right">
        	<h2>播出预告</h2>
            <ul class="lists">
                <?php include_partial('program_guide', array('programs' => $related_programs,'model'=>$wiki->getModel()))?>
            </ul>
        </div>
    </div>
    
    <form name="" method="post" class="tips">
    	<ul>
        	<li>是否预定该节目</li>
            <li><a href="#">是</a></li>
            <li><a href="#">不是</a></li>
        </ul>
    </form>
</div>
<script>
	$(function(){
		var $w=$('body').width();
		var $h=$('body').height();
		$('.list h3').text($w);
		$('.wkbox h2').text($h);
		
		$('.dbzy a').each(function(){
			$(this).click(function(){
				$('.tips').show('slow');
				$('.tips a').eq(0).focus().addClass('there');
			});	
		});
		$('.tips a').each(function(){
			$(this).click(function(){
				$('.tips').hide('slow');
			});	
			$(this).focus(function(){
				$(this).addClass('there');
			});
			$(this).blur(function(){
				$('.tips a').removeClass('there');
			});
		});
	});
</script>