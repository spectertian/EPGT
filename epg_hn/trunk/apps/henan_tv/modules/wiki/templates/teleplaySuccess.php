<?php include_partial("controller") ?>

<div class="epg-details">
      <div class="left-col">
        <div class="cover">
            <img src="<?php echo $wiki->getCoverUrl(); ?>" width="" height="" alt="" />
        </div>
        <ul>
          <li>导演: <strong>
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
                                $directors =$sf_data->getRaw("wiki")->getDirector();
//                                $directors = $wiki->getDirector();
                                //$directors = explode(',',$directors);
//                                $directors = $directors->getRaw();
                                array_walk($directors, 'show');
                             ?>
                    </strong></li>
          <li class="col-2">主演:<strong> <?php
                                $starring = $sf_data->getRaw("wiki")->getStarring();
                                array_walk($starring,'show');
                     ?></strong></li>
          <li>地区: <strong><span class="no-warp"><?php echo trim($wiki->getCountry()); ?></span></strong></li>
          <li>集数: <strong><span class="no-warp"><?php echo trim($wiki->getEpisodes()) ?>集</span></strong></li>
<!--          <li>分类: <strong><span class="no-warp"><?php //echo trim($wiki->getTags('/'));?></span></strong></li>-->
        </ul>
      </div>
      <div class="right-col">
        <div class="title"><?php echo $wiki->getTitle()?></div>
        <div class="summary">
            <div class="content-slide" style="top:0;" >
                <?php echo $wiki->getHtmlCache(ESC_RAW); ?>
            </div>
        </div>
        <div class="stills">
          <ul>
             <?php
                $dramatis = $sf_data->getRaw("wiki")->getScreenshots();
                $photo_count = count($dramatis) -1 ;
              for($i=0;$i<3;$i++):
                if(($photo_count >= $i) && ($photo_count > 0) ):
              ?>
                  <li <?php echo ( $i == 2 ) ? 'class="last"' : 'class=""' ?>><img src="<?php echo file_url($dramatis[$i]) ?>" /></li>
              <?php else: ?>
                   <li <?php echo ( $i == 2 ) ? 'class="last"' : 'class=""' ?> ><img src="<?php echo image_path('details_no_still.png') ?>" width="" height="" alt=""></li>
              <?php endif ?>
              <?php endfor ?>
          </ul>
        </div>
        <div class="pagenator"><span>00</span>/<span rel="1">00</span></div>
      </div>
<!--      <div class="player">
        <span>[<?php //echo $program->getChannelName() ?>] 正在直播 <?php //echo $program->getName() ?></span>
        <span class="time"><?php //echo $program->getStartTime()->format('H:i') ?>/<?php //echo $program->getEndTime()->format('H:i') ?></span>
        <div class="progress">
           <div class="track" style="width:<?php //echo $program->getProgress() ?>%"></div>
        </div>
      </div>-->
    </div>
<?php include_partial('footer') ?>