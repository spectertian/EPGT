    <div class="tooltip-hd">
      <h3><span class="title"><a href="<?php echo url_for('@wiki_show?slug='.$wiki->getSlug())?>"><?php echo $wiki->getTitle();?></a></span></h3>
    </div>
    <div class="tooltip-bd">
      <div class="program">
        <?php if($tags = $wiki->getTags()): $i= 0 ?>
        <div class="text-block">
            <span class="label">类型：</span>
            <?php foreach($tags as $tag) : $i++;?>
            <?php echo ($i > 1) ? ' /' : ''?>
            <span class="param"><a href="<?php echo url_for('search/index?q=tag:'. $tag)?>" title="<?php echo $tag?>" property="v:genre"><?php echo $tag?></a></span>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
        <?php if($hosts = $wiki->getHost()): $i= 0 ?>
        <div class="text-block">
            <span class="label">主持人：</span>
            <?php foreach($hosts as $host) : $i++;?>
            <?php echo ($i > 1) ? ' /' : ''?>
            <span class="param"><a href="<?php echo url_for('search/index?q=' . urlencode($host)) ?>" title="<?php echo $host?>" property="v:directedBy"><?php echo $host?></a></span>
            <?php endforeach;?>
        </div>
        <?php endif;?>
        <div class="text-block summary"><span class="label">栏目介绍：</span>
            <span class="param"><?php echo $wiki->getHtmlCache(150, ESC_RAW); ?><a href="<?php echo url_for('@wiki_show?slug='.$wiki->getSlug())?>"> 详细 &raquo;</a></span>
        </div>
        <div class="text-block video-resources">暂无片源 <!--（<a href="#">加入心愿单</a>）--></div>
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