<script type="text/javascript">
    $(document).ready(function(){
         $('.playing-epg').find("div[class=timeline] > UL > li").each(function(x,e){
             var index = x;
             $(this).click(function(){
                 $('.playing-epg').find("div[class=timeline] > UL > li").removeClass('active');
                 $(this).addClass('active');
                 $('.playing-epg').find('DIV[class=tv-listing]').css('display','none').each(function(x,e){
                     if(x == index){
                         $(this).css('display','block');
                     }
                 });
             });
         });
    });
</script>
<div class="module playing-epg">
<h3>播出进行时</h3>
<div class="timeline">
  <ul>
    <li class="active"><a href="#" onclick="return false"><span class="tv-station"><?php echo $cctv_channels->getName()?></span></a></li>
    <li><a href="#" onclick="return false"><span class="tv-station"><?php echo $tv_HuNan->getName() ?></span></a></li>
    <li><a href="#" onclick="return false"><span class="tv-station"><?php echo $tv_DongFang->getName() ?></span></a></li>
    <li><a href="#" onclick="return false"><span class="tv-station"><?php echo $tv_BeiJing->getName() ?></span></a></li>
    <li><a href="#" onclick="return false"><span class="tv-station"><?php echo $tv_ZheJiang->getName() ?></span></a></li>
  </ul>
  <div class="more-stations">
      <?php if($sf_request->getParameter("location")):?>
      <a href="<?php echo url_for("channel/index?type=all&location=".$sf_request->getParameter("location"))?>">
      <?php else:?>
      <a href="<?php echo url_for('channel/index') ?>">
      <?php endif;?>
          更多本地电视台 &gt;&gt;
      </a>
  </div>
</div>
<div class="tv-listing" style="display:block;">
  <ul>
    <?php
    $i = 0;
    foreach($cctv_programs as $programs) :
    $i++;
    $play_status = $programs->getPlayStatus();
    $play_status = ($play_status == 'playing') ? 'on-air' : '';
    ?>
    <li class="<?php echo ($i%2 == 0) ? 'odd' : '';  ?><?php echo $play_status ?> ">
      <h4 class="title">
            <?php if(strlen($programs->getWikiId()) > 0 ): ?>
            <a href="<?php echo url_for("wiki/show?id=").$programs->getWikiId() ?>"><?php echo $programs->getName() ?></a>
            <?php else: ?>
            <?php echo $programs->getName() ?>
            <?php endif ?>
      </h4>
      <div class="time"><?php echo date("H:i",strtotime($programs->getTime())); ?></div>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
<div class="tv-listing" style="display:none;">
  <ul>
    <?php
    $i = 0;
    foreach($tv_HuNanPrograms as $program) :
    $i++;
    $play_status = $program->getPlayStatus();
    $play_status = ($play_status == 'playing') ? 'on-air' : '';
    ?>
    <li class="<?php echo ($i%2 == 0) ? 'odd' : '';  ?><?php echo $play_status ?> ">
      <h4 class="title">
            <?php if(strlen($program->getWikiId()) > 0 ): ?>
            <a href="<?php echo url_for("wiki/show?id=").$program->getWikiId() ?>"><?php echo $program->getName() ?></a>
            <?php else: ?>
            <?php echo $program->getName() ?>
            <?php endif ?>
      </h4>
      <div class="time"><?php echo date("H:i",strtotime($program->getTime())); ?></div>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
<div class="tv-listing" style="display:none;">
  <ul>
    <?php
    $i = 0;
    foreach($tv_DongFangPrograms as $program) :
    $i++;
    $play_status = $program->getPlayStatus();
    $play_status = ($play_status == 'playing') ? 'on-air' : '';
    ?>
    <li class="<?php echo ($i%2 == 0) ? 'odd' : '';  ?><?php echo $play_status ?> ">
      <h4 class="title">
            <?php if(strlen($program->getWikiId()) > 0 ): ?>
            <a href="<?php echo url_for("wiki/show?id=").$program->getWikiId() ?>"><?php echo $program->getName() ?></a>
            <?php else: ?>
            <?php echo $program->getName() ?>
            <?php endif ?>
      </h4>
      <div class="time"><?php echo date("H:i",strtotime($program->getTime())); ?></div>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
<div class="tv-listing" style="display:none;">
  <ul>
    <?php
    $i = 0;
    foreach($tv_BeiJingPrograms as $program) :
    $i++;
    $play_status = $program->getPlayStatus();
    $play_status = ($play_status == 'playing') ? 'on-air' : '';
    ?>
    <li class="<?php echo ($i%2 == 0) ? 'odd' : '';  ?><?php echo $play_status ?> ">
      <h4 class="title">
            <?php if(strlen($program->getWikiId()) > 0 ): ?>
            <a href="<?php echo url_for("wiki/show?id=").$program->getWikiId() ?>"><?php echo $program->getName() ?></a>
            <?php else: ?>
            <?php echo $program->getName() ?>
            <?php endif ?>
      </h4>
      <div class="time"><?php echo date("H:i",strtotime($program->getTime())); ?></div>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
<div class="tv-listing" style="display:none;">
  <ul>
    <?php
    $i = 0;
    foreach($tv_ZheJiangPrograms as $program) :
    $i++;
    $play_status = $program->getPlayStatus();
    $play_status = ($play_status == 'playing') ? 'on-air' : '';
    ?>
    <li class="<?php echo ($i%2 == 0) ? 'odd' : '';  ?><?php echo $play_status ?> ">
      <h4 class="title">
            <?php if(strlen($program->getWikiId()) > 0 ): ?>
            <a href="<?php echo url_for("wiki/show?id=").$program->getWikiId() ?>"><?php echo $program->getName() ?></a>
            <?php else: ?>
            <?php echo $program->getName() ?>
            <?php endif ?>
      </h4>
      <div class="time"><?php echo date("H:i",strtotime($program->getTime())); ?></div>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
<div class="full-epg">
    <?php if($sf_request->getParameter("location")):?>
    <a href="<?php echo url_for("channel/index?type=all&location=".$sf_request->getParameter("location"))?>">
    <?php else:?>
    <a href="<?php echo url_for('channel/index') ?>">
    <?php endif;?>
        点击进入完整电视节目单 &gt;&gt;
    </a></div>
</div>