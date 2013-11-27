    <div class="tooltip-hd">
      <h3>
          <span class="title"><a href="<?php echo url_for('@wiki_show?slug='.$wiki->getSlug())?>"><?php echo $wiki->getTitle();?></a></span>
          <?php if($wiki->getReleased() || $wiki->getEpisodes()) :?>
          <small>(<span class="release-date"><?php echo $wiki->getReleased()?></span> <span class="episode-nmb"><?php echo $wiki->getEpisodes()?> 集</span>)</small>
          <?php endif;?>
      </h3>
    </div>
    <div class="tooltip-bd">
      <div class="program">
        <div class="text-block"><span class="label">导演：</span>
        <?php if($Directors = $wiki->getDirector()): $i = 0 ?>
        <?php foreach($Directors as $Director) : $i++;?>
        <?php echo ($i > 1) ? ' /' : ''?>
        <span class="param"><a href="<?php echo url_for('search/index?q=' . urlencode($Director)) ?>" title="<?php echo $Director?>"><?php echo $Director?></a></span>
        <?php endforeach ?>
        <?php endif?>
        </div>
        <div class="text-block"><span class="label">主演：</span>
        <?php if($Stars = $wiki->getStarring()): $i = 0 ?>
        <?php foreach($Stars as $Star) : $i++;?>
        <?php echo ($i > 1) ? ' /' : ''?>
        <span class="param"><a href="<?php echo url_for('search/index?q=' . urlencode($Star)) ?>" title="<?php echo $Star?>"><?php echo $Star?></a></span>
        <?php endforeach?>
        <?php endif ?>
        </div>
        <div class="text-block summary"><span class="label">剧情简介：</span>
            <span class="param"><?php echo $wiki->getHtmlCache(150, ESC_RAW); ?><a href="<?php echo url_for('@wiki_show?slug='.$wiki->getSlug())?>"> 详细 &raquo;</a></span>
        </div>
        <div class="text-block video-resources">
            <?php if ($Videos = $wiki->Videos()) :?>
            <span class="label">片源：</span>
            <?php foreach($Videos as $video) :?>
                <span class="param popup-tip <?php echo $video->getReferer()?>" title="<?php echo $video->getRefererZhcn()?>片源"><?php echo $video->getRefererZhcn()?>视频</span>
            <?php endforeach;?>
            <?php else :?>
                暂无片源 <!--（<a href="#">加入心愿单</a>）-->
            <?php endif;?>
        </div>
        <div class="rating">
        <?php if($wiki->getRating() > 0 ) :?>
            <span class="rating-num"><strong><?php echo $wiki->getRatingInt()?></strong>.<?php echo $wiki->getRatingFloat()?></span> 分 &#47; <?php echo $wiki->getCommentCount()?> 评价
        <?php else :?>
            暂无评价
        <?php endif;?>
        </div>
      </div>
      <?php if($programs) : ?>
      <div class="epg-list">
        <ul>
          <?php foreach($programs as $program): ?>
          <?php $play_status = $program->getPlayStatus();?>
          <li <?php echo ($play_status == 'playing') ? 'class="on-air"' : ''?>>
              <div class="epg-info">
                  <?php if($program->getChannelLogo()):?>
                  <span class="station">
                      <a href="<?php echo lurl_for("channel/show?id=".$program->getChannel()->getId())?>">
                      <img src="<?php echo thumb_url($program->getChannelLogo(), 44, 24) ?>" alt="<?php echo $program->getChannelName() ?>">
                      </a>
                  </span>
                  <?php endif;?>
                  <span class="channel"><a href="<?php echo lurl_for("channel/show?id=".$program->getChannel()->getId())?>"><?php echo $program->getChannelName() ?></a></span>
                  <span class="date"><?php echo $program->getStartTime()->format('m月d日 ') . $program->getWeekChineseName('星期'); ?></span>
                  <span class="time"><?php echo $program->getStartTime()->format('H:i'); ?></span>
                  <span class="title"><?php echo $program->getName() ?></span>
                  <span class="remind"><a href="javascript:void(0)" class="popup-tip" title="播放前提醒">提醒</a></span>
              </div>
              <?php if ($play_status == 'playing') :?>
              <div class="play-progress">
              <span class="start-time"><?php echo $program->getTime(); ?></span>
                  <?php $all = strtotime($program->getEndTime()->format("Y-m-d H:i:s")) - strtotime($program->getTime());?>
                  <?php $plan = time() - strtotime($program->getTime());?>
                  <?php $width = round($plan/$all,2) * 100?>
                  <span class="progress-bar"><span class="track" style="width:<?php echo ($width > 100) ? ($width - 100) : $width?>%;"></span></span>
                  <span class="end-time"><?php echo $program->getEndTime()->format("H:i"); ?></span>
              </div>
              <?php endif;?>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif;?>
    </div>