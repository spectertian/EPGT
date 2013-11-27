  <div class="filter-result-bd filter-result-list">
    <ul>
      <?php foreach($archivePager->getResults() as $wikiMeta) :?>
      <li>
        <div class="program">
          <div class="poster">
              <a href="<?php echo url_for('@wiki_show?slug='.$wiki->getSlug(). '&time=' .$wikiMeta->getMark())?>" >
                  <img src="<?php echo $wikiMeta->getOneScreenshot(100, 150) ?>" width="100" height="150" alt="<?php echo $wikiMeta->getTitle()?>">
              </a>
          </div>
          <h3 class="title"><a href="<?php echo url_for('@wiki_show?slug='.$wiki->getSlug(). '&time=' .$wikiMeta->getMark())?>"><?php echo $wikiMeta->getTitle()?></a></h3>
          <div class="text-block"><span class="label">上映时间：</span><span class="param"><?php echo date('Y-m-d', strtotime($wikiMeta->getMark()))?></span></div>
        <?php if($guests = $wikiMeta->getGuests()): $i= 0 ?>
        <div class="text-block">
            <span class="label">嘉宾：</span>
            <?php foreach($guests as $guest) : $i++;?>
            <?php echo ($i > 1) ? ' /' : ''?>
            <span class="param"><a href="#" title="<?php echo $tag?>" property="v:starring"><?php echo $guest?></a></span>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
          <div class="text-block summary"><span class="label">剧情简介：</span><span class="param"><?php echo $wikiMeta->getHtmlCache(150, ESC_RAW)?>...</span></div>
          <?php if ($videos = $wikiMeta->getVideos()) :?>
          <div class="text-block video-resources">
              <span class="label">片源：</span>
              <?php foreach($videos as $video) :?>
              <span class="param popup-tip <?php echo $video->getReferer()?>" title="<?php echo $video->getRefererZhcn()?>片源"><?php echo $video->getRefererZhcn()?>视频</span>
              <?php endforeach;?>
          </div>
          <?php endif;?>
        <!--
            <div class="rating"><span class="rating-num"><strong>9</strong>.8</span> 分 &#47; 15662评价</div>
            --> 
        </div>
      </li>
      <?php endforeach;?>
    </ul>
  </div>