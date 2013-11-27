<div class="container epg">
  <div class="container-inner">
    <div class="main-bd">
      <h2>电视节目指南</h2>
      <?php include_component("channel","province");?>
      <!-- epg start -->
      <?php include_partial('mian_nav', array('active' => $active, 'mode'=> $mode, 'location' => $location))?>
      <?php include_partial('tag_nav', array('tag' => $tag, 'date' => $date, 'mode' => $mode, 'location' => $location))?>
      <?php if (!is_null($wikiPlays)) :?>
      <div class="epg-cat-list">
        <ul>
          <?php foreach($wikiPlays as $i => $wikiPlay) :?>
          <?php $wiki = $wikiPlay->getWiki();?>
          <?php if (is_null($wiki))  continue;?>          
          <li class="clearfix">
            <div class="program">
              <h3><span class="title"><a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug())?>"><?php echo $wiki->getTitle()?></a></span>
             <?php 
                $released = $wiki->getReleased();
                $episodes = $wiki->getEpisodes();
                $director = $wiki->getDirector();
                $starring = $wiki->getStarring();
                if (!empty($released) || !empty($episodes)) :
             ?>   
              <small>( <?php if (! empty($released)) {?><span class="release-date"><?php echo date('Y', strtotime($released))?></span><?php }?>
              <?php if (! empty($episodes)) {?><span class="episode-nmb"><?php echo $wiki->getEpisodes()?> 集</span><?php }?>)</small>          
            <?php endif;?>
              </h3>
              <div class="poster"><a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug())?>"><img alt="<?php echo $wiki->getTitle()?>" src="<?php echo thumb_url($wiki->getCover(), 90, 135)?>"></a></div>
              <?php if (! empty($director)) {?>
              <div class="text-block"><span class="label">导演：</span>
              <?php foreach($director as $value) {?>
              <span class="param"><a href="<?php echo url_for('wiki/show?slug='.$value)?>"><?php echo $value;?></a></span>
              <?php } ?>
              </div>
              <?php }?>
              <?php if (! empty($starring)) {?>
              <div class="text-block"><span class="label">主演：</span>
              <?php foreach($starring as $value) { ?>
              <span class="param"><a href="<?php echo url_for('wiki/show?slug='.$value)?>"><?php echo $value;?></a></span>
              <?php } ?>
              </div>
              <?php }?>
              <div class="text-block summary"><span class="label">剧情简介：</span><span class="param"><?php echo $wiki->getHtmlCache(150, ESC_RAW); ?><a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug())?>">详细 &raquo;</a></span></span></div>
              <?php if($wiki)?>
              <?php //if ($videos = $wiki->getVideos()) : 
              if (false) :
              ?>
              <div class="text-block video-resources"><span class="label">片源：</span>
              <?php foreach($videos as $video) :?>
              <span class="param qiyi popup-tip" title="<?php echo $playlist->getRefererZhcn()?>片源"><?php echo $playlist->getRefererZhcn()?>视频</span>
              <?php endforeach;?>
              </div>
              <?php endif ?>
              <div class="rating"><span class="rating-num"><strong><?php echo $wiki->getRatingInt()?></strong>.<?php echo $wiki->getRatingFloat()?></span> 分 &#47; <?php echo $wiki->getCommentCount()?>评价</div>
            </div>
            <?php $programs = $wiki->getUserRelateProgramByDate($province, $datestamp, $datestamp)?>
            <?php if (!is_null($programs)) :?>
            <div class="epg-list">
              <ul>
              <?php foreach ($programs as $program): ?>
              <?php $play_status = $program->getPlayStatus();?>
                <li class="on-air">
                  <div class="epg-info">
                  <span class="station"><a href="<?php echo lurl_for('channel/show?id='. $program->getChannel()->getId())?>"><img src="<?php echo thumb_url($program->getChannel()->getLogo(), 44, 24)?>" alt="<?php echo $program->getChannel()->getName()?>"></a></span> 
                  <span class="channel"><a href="<?php echo lurl_for('channel/show?id='. $program->getChannel()->getId())?>"><?php echo $program->getChannel()->getName()?></a></span> <span class="date"><?php echo $program->getWeekChineseName();?></span>
                  <span class="time"><?php echo $program->getTime()?></span>
                  <span class="title"><?php echo $program->getName()?></span> 
                  <!--<span class="remind"><a href="javascript:void(0)" class="popup-tip" title="播放前提醒">提醒</a></span>-->
                  </div>
                </li>
             <?php endforeach;?>
              </ul>
            </div>
            <?php endif ?>
          </li>
        <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<script type="text/javascript">
$('#tv-listing .remind a').click(function (event){
    $(this).toggleClass('active');
});
</script>