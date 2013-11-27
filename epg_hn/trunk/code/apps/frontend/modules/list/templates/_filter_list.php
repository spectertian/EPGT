          <div class="filter-result-bd filter-result-tile clearfix">
          <?php if ($wiki_pager->count() > 0):?>
            <ul>
            <?php foreach ($wiki_pager as $wiki): ?>
              <li>
                <div class="program">
                  <div class="poster"><a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>" slug="<?php echo $wiki->getSlug()?>">
                  <img src="<?php echo thumb_url($wiki->getCover(), 100, 150)?>" width="100" height="150" alt="<?php echo $wiki->getTitle()?>">
                  </a></div>
                  <h3 class="title"><a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>" target="_blank"><?php echo $wiki->getTitle();?></a></h3>
                  <div class="text-block">
                  <span class="label">主演：</span>
                      <?php if($wiki->getStarring()): ?>
                      <?php foreach($wiki->getStarring() as $starts):?>
                      <span class="param"><a href="<?php echo url_for("wiki/show?slug=".$starts)?>"><?php echo $starts?></a></span>
                      <?php endforeach;?>
                      <?php endif;?>                
                  </div>
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
        <script type="text/javascript">
        $(function(){
            toolTiper('.filter-result-tile li .poster a', 20, 100, 474);
        });
        </script>