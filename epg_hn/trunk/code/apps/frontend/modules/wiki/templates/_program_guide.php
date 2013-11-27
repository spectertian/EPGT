    <?php if($programs) : ?>
    <div class="mod" id="tv-listing">
      <div class="hd">
        <h3>收视指南</h3>
      </div>
      <div class="epg-list" style="display:block;">
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
    </div>
    <div class="epg-tips">电视节目以电视台当天播出时间为准，如有变更，恕不另行通知；电视收视范围以当地有线电视服务商实际提供内容为准。</div>
    <?php endif;?>