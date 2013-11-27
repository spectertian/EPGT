<?php if($program_now):?>
    <?php $wiki=$program_now->getWiki();?>
    <?php if($wiki):?>
        <?php $all = strtotime($program_now->getEndTime()->format("Y-m-d H:i:s")) - strtotime($program_now->getTime());?>
        <?php $plan = time() - strtotime($program_now->getTime());?>
        <?php $width = round($plan/$all,2) * 100?>
        <div class="thistv">
        	<img src="<?php echo thumb_url($wiki->getCover(), 100, 150)?>" alt=""/>
            <ul class="ctrl">
            	<li><a href="#" class="play"></a></li>
                <li><a href="#" class="score"></a></li>
                <li><a href="#" class="sc"></a></li>
                <li><a href="#" class="full"></a></li>
            </ul>
          	<div class="playtime">
            	<b><?php echo mb_strcut($program_now->getChannelName(), 0, 27, 'utf-8');?>：<?php echo $program_now->getWikiTitle()?></b>
                <strong>下一个节目：档案午间版</strong>
                <span><em style="width:<?php echo ($width > 100) ? ($width - 100) : $width?>%;"></em></span>
                <i><?php echo date("H:i",$program_now->getStartTime()->getTimestamp());?></i><dfn><?php echo date("H:i",$program_now->getEndTime()->getTimestamp());?></dfn>
            </div>
        </div>
        
        <div class="tvinfor clr">
        	<a href="#" class="cover"><img src="<?php echo thumb_url($wiki->getCover(), 110, 150)?>" alt=""/></a>
            <ul>
            	<li><h2><?php echo $program_now->getWikiTitle()?></h2></li>
                <li>类型：
                    <?php 
                    $tags=$wiki->getTags();
                    $i=0;
                    foreach($tags as $tag) : $i++; 
                    echo ($i > 1) ? ' /' : ''; echo $tag;
                    endforeach; 
                    ?>
                </li>
        <?php if($wiki->getModel()=='television') : //综艺节目?> 
                <?php if($hosts = $wiki->getHost()): $i = 0 ?>  
                <li>主持人：
                    <?php foreach($hosts as $host) : $i++;?>
                    <?php echo ($i > 1) ? ' /' : ''; echo $host;?>
                    <?php endforeach;?>   
                </li>
                <?php endif; ?>        
                <?php if($guests = $wiki->getGuests()): $i = 0 ?>
                <li>嘉宾：
                    <?php foreach($guests as $guest) : $i++;?>
                    <?php echo ($i > 1) ? ' /' : ''; echo $guest;?>
                    <?php endforeach;?>           
                </li>
                <?php endif; ?> 
        <?php elseif($wiki->getModel()=='telplay'):  //电视剧?>
                <?php if($Stars = $wiki->getStarring()): $i = 0 ?>    
                <li>主演：
                    <?php foreach($Stars as $Star) : $i++;?>
                    <?php if($i<6):?>
                    <?php echo ($i > 1) ? ' /' : ''; echo $Star;?>
                    <?php endif; ?>
                    <?php endforeach;?>
                </li>
                <?php endif; ?>  
                <?php if($wiki->getEpisodes()): ?>
                <li>集数：<?php echo $wiki->getEpisodes()?></li>
                <?php endif; ?> 
        <?php else:  //电影?>        
                <?php if($Stars = $wiki->getStarring()): $i = 0 ?>    
                <li>主演：
                    <?php foreach($Stars as $Star) : $i++;?>
                    <?php if($i<6):?>
                    <?php echo ($i > 1) ? ' /' : ''; echo $Star;?>
                    <?php endif; ?>
                    <?php endforeach;?>
                </li>
                <?php endif; ?>  
                <?php if($wiki->getRuntime()): ?>
                <li>片长：<?php echo $wiki->getRuntime()?>分钟</li>
                <?php endif; ?> 
        <?php endif;?>  
            </ul>
            <a href="<?php echo url_for('wiki/show?slug='.$program_now->getWiki()->getSlug())?>" class="more">查看详情</a>
        </div>
    <?php endif;?>
<?php endif;?>