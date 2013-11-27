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
<div class="main">
	<h1 class="city"><?php echo $channel_name;?> <a href="<?php echo url_for('channel/allLive')?>">所有频道</a></h1>
	<div class="clr">
    	<div class="left">
        	<ul class="lister">
            	<li>
                	<a href="#">
                		<img src="/images/aa.jpg" alt=""/>
                        <span>
                        	<em>播出时间<b>节目名称</b></em>
                            剧情简介剧情简介剧情简介剧情简介剧情简介
                        </span>
                    </a>
                </li>
                <li>
                	<a href="#">
                		<img src="/images/bb.jpg" alt=""/>
                        <span>
                        	<em>播出时间<b>节目名称</b></em>
                            剧情简介剧情简介剧情简介剧情简介剧情简介
                        </span>
                    </a>
                </li>
            </ul>
            
            <ul class="lister listhree">
            	<li>
                	<a href="#">
                		<img src="/images/a.jpg" alt=""/>
                        <span>
                        	<em>播出时间<b>节目名称</b></em>
                            剧情简介剧情简介剧情简介剧
                        </span>
                    </a>
                </li>
                <li>
                	<a href="#">
                		<img src="/images/b.jpg" alt=""/>
                        <span>
                        	<em>播出时间<b>节目名称</b></em>
                            剧情简介剧情简介剧情简介剧
                        </span>
                    </a>
                </li>
                 <li>
                	<a href="#">
                		<img src="/images/c.jpg" alt=""/>
                        <span>
                        	<em>播出时间<b>节目名称</b></em>
                            剧情简介剧情简介剧情简介
                        </span>
                    </a>
                </li>
            </ul> 
            
            <ul class="ad50">
            	<li><a href="#"><img src="/images/222.jpg" alt=""/></a></li>
                <li><a href="#"><img src="/images/222.jpg" alt=""/></a></li>
            </ul>             
        </div>
        
        <div class="rights">
        <?php $n = $xingqi-1;$zhouyi = strtotime("$date -$n day");$daxie = array("一","二","三","四","五","六","七")?>
            <ol class="weeklist">
            <?php for($i=0;$i<7;$i++):?>
            <?php $d = date("Y-m-d",$zhouyi+$i*(24*60*60))?>
            <li><a href="<?php echo url_for("channel/index?channel_code=$channel_code&date=$d")?>"  <?php if($d == $date):?><?php echo 'style="background:yellow; color:#111;"'?><?php endif;?>><?php echo $daxie[$i]?></a></li>
            <?php endfor;?>
            </ol>
            <ul class="tvlist">
            	<?php foreach($programs as $program):?>
            		<li><a href="<?php echo url_for("wiki/show?slug=".$program->getWikiSlug())?>"><?php echo $program->getTime()?> <?php echo $program->getName(); ?></a></li>
            	<?php endforeach;?>
            </ul>
        </div>
    </div>

</div>