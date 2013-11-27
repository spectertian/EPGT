         <div class="filter-result-bd filter-result-list">
          <?php if ($wiki_pager->count() > 0):?>
            <ul>
              <?php foreach ($wiki_pager as $wiki): ?>
              <li>
                <div class="program">
                  <div class="poster"><a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>">
                  <img src="<?php echo thumb_url($wiki->getCover(), 100, 150)?>" width="100" height="150" alt="
                  <?php echo $wiki->getTitle()?>">
                  </a>
                  </div>
                  <h3 class="title"><a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>" target="_blank"><?php echo $wiki->getTitle()?></a></h3>
                  <div class="text-block"><span class="label">上映时间：</span><span class="param">2008-04-11</span></div>
                  <div class="text-block"><span class="label">导演：</span><span class="param"><a href="#">Sue Williams</a></span></div>
                  <div class="text-block">
                  	<span class="label">主演：</span>
                      <?php if($wiki->getStarring()): ?>
                      <?php foreach($wiki->getStarring() as $starts):?>
                      <span class="param"><a href="<?php echo url_for("wiki/show?slug=".$starts)?>"><?php echo $starts?></a></span>
                      <?php endforeach;?>
                      <?php endif;?>    
                    </div>
                  <div class="text-block summary"><span class="label">剧情简介：</span><span class="param"><?php echo $wiki->getHtmlCache(150, ESC_RAW); ?>...</span></div>
                  <?php if($wiki->getModel() == 'film'): ?>
                    <?php if ($videos = $wiki->getVideos()) :?>
                      <div class="text-block video-resources"><span class="label">片源：</span>
                        <?php foreach($videos as $video) :?>
                          <span class="param <?php echo $video->getReferer()?> popup-tip" title="<?php echo $video->getRefererZhcn()?>片源"><?php echo $video->getRefererZhcn()?>视频</span>
                        <?php endforeach;?>
                       </div>
                       <?php else: ?>
                       <div class="text-block video-resources">暂无片源</div>
                    <?php endif;?>
                  <?php endif;?>

                  <?php if($wiki->getModel() == 'teleplay'): ?>
                  <?php if ($PlayList = $wiki->getPlaylist()) :?>	
                  <div class="text-block video-resources"><span class="label">片源：</span>
                  	<?php foreach($PlayList as $playlist) :?>
                      <span class="param <?php echo $playlist->getReferer()?> popup-tip" title="<?php echo $playlist->getRefererZhcn()?>片源"><?php echo $playlist->getRefererZhcn()?>视频</span> 
                    <?php endforeach;?>  
                   </div>
                   <?php else: ?>
                   <div class="text-block video-resources">暂无片源</div>
          			<?php endif;?>   
                   <?php endif;?>                   
                   <?php if($wiki->getCommentCount() > 0): ?>
                   <div class="rating"><span class="rating-num"><strong><?php echo $wiki->getRatingInt()?></strong>.<?php echo $wiki->getRatingFloat()?></span> 分 &#47; <?php echo $wiki->getCommentCount()?> 评价</div>
                   <?php else:?>
                 <div class="rating">暂无评价</div>
                  <?php endif;?>
                </div>
              </li>
             <?php endforeach;?>
            </ul>
          <?php else:?>
              <div class="no-result">尚未有对应的片源</div>
          <?php endif;?>
          </div>
        </div>