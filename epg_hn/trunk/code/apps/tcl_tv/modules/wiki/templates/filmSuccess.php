<?php include_partial("controller") ?>

<div class="epg-details">
      <div class="left-col">
        <div class="cover"><img src="<?php echo $wiki->getCoverUrl(); ?>" width="260" height="300" alt=""></div>
        <ul>
          <li>导演: <strong><span class="no-warp"><?php echo trim($wiki->getDirector()); ?></span></strong></li>
          <li class="col-2">主演: <strong>
                                <?php
                                    function show($item,$key)
                                    {
                                        if($key != 0 )
                                        {
                                            echo ' / <span class="no-warp">'.$item.'</span>';
                                        }else{
                                            echo '<span class="no-warp">'.$item.'</span>';
                                        }
                                    }
                                    $starrings = $sf_data->getRaw("wiki")->getStarring();
                                    //$starrings = explode(",",$starrings);
                                    array_walk($starrings,'show');
                                ?></strong></li>
          <li>分类: <strong><span class="no-warp"><?php echo trim(implode('/', $sf_data->getRaw("wiki")->getTags()));// trim(str_replace(',', ' / ', $sf_data->getRaw("wiki")->getTags()));?></span></strong></li>
          <li>地区: <strong><span class="no-warp"><?php echo trim($wiki->getCountry()); ?></span></strong></li>
          <?php if(strlen($wiki->getRelease()) > 0 ): ?><li>上映时间: <strong><span class="no-warp"><?php echo trim(date('Y年m月d日',strtotime($wiki->getRelease()))); ?></span></strong></li><?php endif ?>
        </ul>
      </div>
      <div class="right-col">
        <div class="title"><?php echo $wiki->getTitle()?></div>
        <div class="summary">
            <div class="content-slide" style="top:0;">
                <?php echo $wiki->getHtmlCache(ESC_RAW); ?>
            </div>
        </div>
        <div class="stills">
              <ul>
              <?php
                $dramatis = $sf_data->getRaw("wiki")->getScreenshots();
                $photo_count = count($dramatis) -1 ;
                for($i=0;$i<=3;$i++):
                if(($photo_count >= $i) && ($photo_count > 0) ):
              ?>
                  <li <?php echo ( $i == 3 ) ? 'class="last"' : 'class=""' ?>><img src="<?php echo file_url($dramatis[$i]) ?>" /></li>
              <?php else: ?>
                   <li <?php echo ( $i == 3 ) ? 'class="last"' : 'class=""' ?> ><img src="<?php echo image_path('details_no_still.png') ?>" width="" height="" alt=""></li>
              <?php endif ?>
              <?php endfor ?>
              </ul>
            </div>
        <div class="pagenator"><span>00</span>/<span rel="1">00</span></div>
      </div>
<!--      <div class="player">
        <span>[江苏卫视] 正在直播 第10集</span>
        <span>[<?php //echo $program->getChannelName() ?>] 正在直播 <?php //echo $program->getName() ?></span>
        <span class="time"><?php //echo $program->getStartTime()->format('H:i') ?>/<?php //echo $program->getEndTime()->format('H:i') ?></span>
         <div class="progress">
             <div class="track" style="width:<?php //echo $program->getProgress() ?>%"></div>
        </div>
      </div>-->
    </div>
<?php include_partial('footer') ?>
