<?php include_partial("detailScript") ?>
    <div class="page" id="main-nav-sizer">
        <div class="detail-show">
            <div class="ll">                
                <div class="binfo">
                    <div class="bs"><img alt="<?php echo $wiki->getTitle()?>" src="<?php echo thumb_url($wiki->getCover(),260,332)?>"></div>
                    <div class="dinfo" id="dinfo">
                        <h2><?php echo $wiki->getTitle()?></h2>
                        <ul>
						<strong><?php if($wiki->getModel()=='television') : //综艺节目?>
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
						<?php if($wiki->getChannel()): ?>	
						<li>播出频道：      
								<?php echo $wiki->getChannel()?>                  
						</li>
						<?php endif; ?> 
						
						<?php if($wiki->getPlayTime()): ?>	
						<li>播出时间：     
								<?php echo substr($wiki->getPlayTime(),30)?>                   
						</li>
						<?php endif; ?> 
						<?php if($wiki->getHtmlCache()): ?>
						<li class="smry">介绍：      
							<?php echo $wiki->getHtmlCache(160, ESC_RAW); ?><span class="action button" rel="<?php echo $wiki->getId(); ?>" >查看详情</span>
						</li>
						<?php endif; ?>                                                                                                            
						<?php elseif($wiki->getModel()=='teleplay'):  //电视剧?>					  
						<?php if($Stars = $wiki->getStarring()): $i = 0 ?>    
						<li>主演:     
						<?php foreach($Stars as $Star) : $i++;
							  if($i<6):
						?>
						<?php echo ($i > 1) ? ' /' : ''; echo $Star;?>
						<?php 
							  endif;
							  endforeach;?>                           
						</li>  
						<?php endif; ?>  
						<?php if($wiki->getCountry()): ?>
						<li>国家:      
								<?php echo $wiki->getCountry()?>                   
						</li>
						<?php endif; ?>
						<?php if($wiki->getTags()): ?>
						<li>类型:      
						<?php $i=0; foreach($wiki->getTags() as $Tags) : $i++;
						?>
						<?php echo ($i > 1) ? ' /' : ''; echo $Tags;?>
						<?php 
							  endforeach;?>           
						</li>
						<?php endif; ?>
						<?php if($wiki->getReleased()): ?>
						<li>年代:     
								<?php echo $wiki->getReleased()?>                 
						</li>
						<?php endif; ?>
						<?php if($wiki->getHtmlCache()): ?>	
						<li class="smry">简介:     
								<?php echo $wiki->getHtmlCache(160, ESC_RAW);?> <span class="action button" rel="<?php echo $wiki->getId(); ?>" >查看详情</span>      
						</li>
						<?php endif; ?>                                                 
				<?php else:  //电影?> 		  
						<?php if($Stars = $wiki->getStarring()): $i = 0 ?>    
						<li>主演:       
						<?php foreach($Stars as $Star) : $i++;
							  if($i<6):
						?>
						<?php echo ($i > 1) ? ' /' : ''; echo $Star;?>
						<?php 
							  endif;
							  endforeach;?>                           
						</li>  
						<?php endif; ?>  
						
						<?php if($wiki->getCountry()): ?>
						<li>国家:     
								<?php echo $wiki->getCountry()?>                   
						</li>
						<?php endif; ?> 
						<?php if($wiki->getTags()): ?>
						<li>类型:      
								<?php $i=0; foreach($wiki->getTags() as $Tags) : $i++;
						?>
						<?php echo ($i > 1) ? ' /' : ''; echo $Tags;?>
						<?php 
							  endforeach;?>                     
						</li>
						<?php endif; ?>
						<?php if($wiki->getReleased()): ?>
						<li>上映时间:    
								<?php echo $wiki->getReleased()?>          
						</li>
						<?php endif; ?> 		
						<?php if($wiki->getHtmlCache()): ?>	
						<li class="smry">简介:  
								<?php echo $wiki->getHtmlCache(160, ESC_RAW)?>  <span class="action button" rel="<?php echo $wiki->getId(); ?>" >查看详情</span>     
						</li>
						<?php endif; ?>                                                
				<?php endif;?></strong>
                        </ul>
                    </div>      
                </div>
                <div class="btab" id="btab">
                    <ul id="tab" class="infotab">
                    	<?php if (!empty($programs_ing)):?>
                        <li class="<?php echo (empty($programs_ing))?'disabled':'action actived';?>" contentid="playlist"><span class="aactived"><span class="ahover">当前播放</span></span></li>
                        <?php endif;?>
                        <?php if (!empty($unplayed_programs)):?>
                        <li class="<?php echo (empty($unplayed_programs))?'disabled':'action';?><?php if (empty($programs_ing)&&($unplayed_programs)){echo ' actived';}?>" contentid="nextlist"><span class="aactived"><span class="ahover">本周预约</span></span></li>
                        <?php endif;?>
                        <li class="action<?php echo (empty($programs_ing)&&empty($unplayed_programs))?' actived':'';?>" contentid="stills" ><span class=aactived><span class="ahover">精彩剧照</span></span></li>
                    </ul>
                 </div>
                 <div id="content" class="bcct">
                        	<div id="contentList">
                             	<?php if ($programs_ing) :?>
                             		<ul class="playlist display-block" id="playlist" >
					                    <?php foreach($programs_ing as $ing) :?>
						                <li class="action" sid="<?php echo $ing->getServiceId();?>">
						                    <div><p><?php $t = explode('：',$ing->getName()); $t = explode(':',$t[1]?$t[1]:$t[0]); echo $t[1]?$t[1]:$t[0];?></p></div>
						                    <div class="info">
						                         <span class="progress">
						                   	         <span style="width: <?php echo $ing->getProgress() ?>%;" class="track"></span>
						                         </span>
						                    </div>
						                    <div><?php echo $ing->getChannelName() ?><span class="play"></span></div>
						                </li>
										<?php endforeach;?>
					                 </ul>
                                <?php endif;?>
                             	<?php if($unplayed_programs) :?>
                             		 <ul class="playlist nextlist <?php echo (empty($programs_ing))?'display-block':'display-none';?>" id="nextlist">
										<?php foreach($unplayed_programs as $program):?>
					                    <li class="action" sid="<?php echo $program->getServiceId();?>" date="<?php echo $program->getStartTime()->format("Y-m-d") ?>" time="<?php echo $program->getStartTime()->format("H:i") ?>">
					                    	<div><p><?php $t = explode('：',$program->getName()); $t = explode(':',$t[1]?$t[1]:$t[0]); echo $t[1]?$t[1]:$t[0];?></p></div><div><?php echo $program->getStartTime()->format("m/d  H:i") ?></div>
					                    	<div><?php echo $program->getChannelName() ?><span class="ring"></span></div>
					                    </li>
										<?php endforeach;?>                                
					                 </ul>
                                <?php endif;?>
                             		<ul class="stills <?php echo (empty($programs_ing)&&empty($unplayed_programs))?'display-block':'display-none';?>" id="stills">
					                    <?php
					                         $dramatis = $sf_data->getRaw("wiki")->getScreenshots();
					                         $photo_count = count($dramatis) -1 ;
					                         for($i=0;$i<3;$i++):
					                             if(($photo_count >= $i) && ($photo_count > 0) ):
					                    ?>
					                         <li class="action disabled"><img src="<?php echo file_url($dramatis[$i]) ?>" /></li>
					                         <?php else: ?>
					                         <li class="action disabled"><img src="<?php echo image_path('details_no_still.png') ?>" width="" height="" alt=""></li>
					                         <?php endif;?>
					                         <?php endfor ?>
					                 </ul>
                        </div>
                        <span id="dup" class="up"></span>
						<span id="ddown" class="down"></span>
                  </div>
            </div>
            <div class="lr" id="lr">
                <h2>正在热播</h2>
                <ul class="prelist" id="prelist">
				<?php $j=0; $i=0; foreach($hot_programs as $programs): if($j<13&&$programs->getName()!='转播中央台新闻联播'): $j++ ;?>
                    <?php if($i%2!=0):?>
        				<li class="action" rel="<?php echo $programs->getWikiId();?>">
        					<span class="button"><p><?php $t = explode('：',$programs->getName()); $t = explode(':',$t[1]?$t[1]:$t[0]); echo $t[1]?$t[1]:$t[0];?></p></span>
        				</li>
        				<?php else:?>
                        <li class="action even" rel="<?php echo $programs->getWikiId();?>">
                        	<span class="button"><p><?php $t = explode('：',$programs->getName()); $t = explode(':',$t[1]?$t[1]:$t[0]); echo $t[1]?$t[1]:$t[0];?></p></span>
                        </li>
                    <?php endif ; $i++;?>
                <?php endif ;  endforeach; ?>
                </ul>
            </div>
        <div class="footer">
        <div id="footer_back" class="return">
             <div class="action  button">返回</div>
        </div>        
        <div class="help">
            <span>按</span>
            <span class="arrows">&lt; &gt;</span>
            <span>键选择，按</span>
            <span class="button">OK</span>
            <span>键确认</span>
        </div>
    </div>  
 </div>
