         <div class="filter-result-bd filter-result-list">
          <?php if ($wiki_pager->count() > 0):?>
            <ul>
              <?php foreach ($wiki_pager as $wiki): ?>
              <li>
              <!-- actor model begin -->
              <?php if($wiki->getModel() == 'actor'): ?>
                <div class="program">
                  <h3><?php echo $wikimodel[$wiki->getModel()];?> - <span class="title"><a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>"><?php echo $wiki->getTitle()?></a></span> <small>( <span class="param"><?php echo $wiki->getSex() ?></span> 
                  <span class="release-date"><?php echo $wiki->getBirthday() ?></span> <span class="param"><?php echo $wiki->getBirthplace() ?></span> )</small>
                  
                  </h3>
                  <div class="poster"><a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>"><img src="<?php echo thumb_url($wiki->getCover(), 100, 150)?>" width="100" height="150" alt="<?php echo $wiki->getSlug() ?>"></a></div>
                  <?php if ($wiki->getOccupation()) :?>
                  <div class="text-block"><span class="label">职业：</span> <span class="param"><?php echo $wiki->getOccupation(); ?></span></div>
                  <?php endif;?>
                  
                  <?php if ($wiki->getHeight()) :?>
                  <div class="text-block"><span class="label">身高：</span> <span class="param"><?php echo $wiki->getHeight() ?>cm</span></div>
                  <?php endif;?>
                  
                  <?php if ($wiki->getWeight()) :?>
                  <div class="text-block"><span class="label">体重：</span> <span class="param"><?php echo $wiki->getWeight()?></span></div>
                  <?php endif;?>
                  
                  <?php if ($wiki->getDebut()) :?>
                  <div class="text-block"><span class="label">出道日期：</span> <span class="param"><?php echo $wiki->getDebut() ?></span></div>
                  <?php endif;?>
                  
                  <div class="text-block">
                    <p>
                    <span class="label">人物简介：</span><span class="param">
                    <?php echo $wiki->getHtmlCache(150, ESC_RAW); ?>
                    <a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>#detail">详细&raquo;</a></span></p>
                  </div>
                </div>
                <?php endif; ?>
                <!--actor model end -->
                
                <!--television model begin -->
                <?php if($wiki->getModel() == 'television'): ?>
  				<div class="program">
                  <h3><?php echo $wikimodel[$wiki->getModel()];?> - <span class="title"><a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>"><?php echo $wiki->getTitle()?></a></span></h3>
                  <div class="poster"><a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>" target="_blank"><img src="<?php echo thumb_url($wiki->getCover(), 100, 150)?>" width="100" height="150" alt="<?php echo $wiki->getSlug() ?>"></a></div>
                  
                  <?php if ($wiki->getPlayTime()) :?>
                  <div class="text-block"><span class="label">播出时间：</span><span class="param"><?php echo $wiki->getPlayTime()?></span></div>
                  <?php endif?>
                  
                  <?php if ($wiki->getChannel()) :?>
                  <div class="text-block"><span class="label">播出频道：</span><span class="param"><?php echo $wiki->getChannel()?></span></div>
                  <?php endif?>
                  
                  <?php if($hosts = $wiki->getHost()): $i= 0 ?>
                  <div class="text-block"><span class="label">主持人：</span>
                  <?php foreach($hosts as $host) : $i++;?>
                  	<span class="param"><a href="<?php echo url_for("wiki/show?slug=".$host)?>"><?php echo $host?></a></span> 
                  <?php endforeach;?>
                  </div>
                  <?php endif; ?>                  
                  
                  <?php if($guests = $wiki->getGuest()): $i= 0 ?>
                  <div class="text-block"><span class="label">嘉宾：</span>
                  <?php foreach($guests as $guest) : $i++;?>
                  	<span class="param"><a href="<?php echo url_for("wiki/show?slug=".$guest)?>"><?php echo $guest?></a></span> 
                  <?php endforeach;?>
                  </div>
                  <?php endif; ?>
                  
                  <div class="text-block summary"><span class="label">本期看点：</span><span class="param"><?php echo $wiki->getHtmlCache(150, ESC_RAW); ?><a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>#detail">详细 &raquo;</a></span></div>
                   <?php if($wiki->getCommentCount() > 0): ?>
                   <div class="rating"><span class="rating-num"><strong><?php echo $wiki->getRatingInt()?></strong>.<?php echo $wiki->getRatingFloat()?></span> 分 &#47; <?php echo $wiki->getCommentCount()?> 评价</div>
                   <?php else:?>
                 <div class="rating">暂无评价</div>
                  <?php endif;?>
                </div>              
               <?php endif; ?>                 
                <!-- television model end -->
               
               <!-- film and teleplay model begin -->
               <?php if($wiki->getModel() == 'teleplay'  || $wiki->getModel() == 'film' ): ?>
               <div class="program">
                  <h3><?php echo $wikimodel[$wiki->getModel()];?> - <span class="title"><a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>"><?php echo $wiki->getTitle()?></a></span> 
                  <?php if($wiki->getMoel() == 'teleplay'): ?>
                  <small>( 
                   <?php if($wiki->getReleased()): ?>
                  <span class="release-date"><?php echo $wiki->getReleased()?></span> 
                  <?php endif;?>
                  <?php if($wiki->getEpisodes()): ?>
                  <span class="episode-nmb">><?php echo $wiki->getEpisodes() ?>集</span> 
                  <?php endif;?>
                  )</small>
                  <?php endif;?>
                  </h3>
                  <div class="poster"><a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>" target="_blank"><img src="<?php echo thumb_url($wiki->getCover(), 100, 150)?>" width="100" height="150" alt="<?php echo $wiki->getSlug() ?>"></a></div>
                  <?php if($Directors = $wiki->getDirector()): $i = 0 ?>
                  <div class="text-block">
                  	<span class="label">导演：</span>
                     <?php foreach($Directors as $Director) : $i++;?>
                     <?php echo ($i > 1) ? '  ' : ''?>
                     <span class="param"><a href="<?php echo url_for("wiki/show?slug=".$Director); ?>"><?php echo $Director?></a></span>
                     <?php endforeach;?>
                  </div>
                  <?php endif; ?>
                  
                   <?php if($Stars = $wiki->getStarring()): $i = 0 ?>
                  <div class="text-block"><span class="label">主演：</span>
					<?php foreach($Stars as $Star) : $i++;?>
                    <?php echo ($i > 1) ? '  ' : ''?>                  
                    <span class="param"><a href="#"><?php echo $Star?></a></span>
                     <?php endforeach;?>
                  </div>
                  <?php endif; ?>
                  
                  <div class="text-block summary"><span class="label">剧情简介：</span>
                  	<span class="param"><?php echo $wiki->getHtmlCache(150, ESC_RAW); ?>... <a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>#detail">详细 &raquo;</a></span></div>
                  <?php if ($PlayList = $wiki->getPlaylist()) :?>  
                  <div class="text-block video-resources"><span class="label">片源：</span>
                  <?php foreach($PlayList as $playlist) :?>
                      <span class="param <?php echo $playlist->getReferer()?> popup-tip" title="<?php echo $playlist->getRefererZhcn()?>片源"><?php echo $playlist->getRefererZhcn()?>视频</span> 
				  <?php endforeach; ?>	                    
                  <?php endif; ?>
                   <?php if($wiki->getCommentCount() > 0): ?>
                   <div class="rating"><span class="rating-num"><strong><?php echo $wiki->getRatingInt()?></strong>.<?php echo $wiki->getRatingFloat()?></span> 分 &#47; <?php echo $wiki->getCommentCount()?> 评价</div>
                   <?php else:?>
                 <div class="rating">暂无评价</div>
                  <?php endif;?>                 
                </div>
               <?php endif;?>
               <!-- film and teleplay model end -->
               
              </li>              
              
	              
              
             <?php endforeach;?>
            </ul>
          <?php else:?>
              <div class="no-result">尚未有对应的片源</div>
          <?php endif;?>
          </div>
        </div>