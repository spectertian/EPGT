<script type="text/javascript"> 
$(function() {
    (function($){
        $.fn.slide = function() {
            var deplay = 3000,
            pageSize = 2,
            current = 1,
            autoplay = false,
            self = this,
            pages = Math.ceil($('#slides .slides_container ul li').length/pageSize);
            $.extend(self,{
                seekTo: function(i) {
                    var left = $('#slides .slides_container ul li').eq(i).position().left
                    $('#slides .slides_container ul').animate({left: -left}, 750, 'swing');
                },

                setActive: function (i){
                    $('#slides .scrollnav .prev a, #slides .scrollnav .next a').removeClass('disabled');
                    if (i == 1) $('#slides .scrollnav .prev a').addClass('disabled');
                    if (i == pages) $('#slides .scrollnav .next a').addClass('disabled');
                    $('#slides .scrollnav li').removeClass();
                    $('#slides .scrollnav li').eq(i-1).addClass('active');
                },

                goPlay: function(statu) {
                    setInterval(function(){
                            self.goNext();
                    }, deplay)

                },

                goNext: function() {
                    if (pages == current){
                            return;
                    }
                    self.seekTo(pageSize*current);
                    self.setActive(current+1);
                    current++;
                },

                goPrev: function() {
                    if (current == 1) return;
                    current--;
                    self.seekTo(pageSize*(current-1));
                    self.setActive(current);
                },

                goPage: function(i){
                    current = i;
                    self.seekTo(pageSize*(current-1));
                    self.setActive(current);
                }
            });

            $('#slides .scrollnav .next a').click(function(){
                self.goNext();
            })

            $('#slides .scrollnav .prev a').click(function(){
                self.goPrev();
            })

            $('#slides .scrollnav li a').click(function(){
                self.goPage(parseInt($(this).text()));
            });

            return self;
        }
    })(jQuery);

    $('#slides .slides_container ul').slide();

});

function addfavorite(channe_id){
    if (loginDialogStatus()){
    if(channe_id.lenth==0) alert("wiki id is null");
        $.ajax ( {
            type: "post",
            url: "<?php echo url_for('channel/channel_favorites');?>",
            data: {"channe_id": channe_id},
            success: function(m)
            {
                   // alert(m);
                    if(m==1){
                        //alert("非法提交数据");
                    }
                    if(m==4){
                       // alert("您没有用户登录");
                    }
                    if(m==2) {
                        //alert("添加成功");
                        $('.fav-channel a').addClass('active');
                    }
                    if(m==3) {
                        //alert("您曾经添加过");
                        $('.fav-channel a').addClass('active');
                    }
            }
        })
    }
}
$(function(){
    // switch-channel
    $('.switch-channel-hd a').click(function() {
        $('.switch-channel-bd').slideToggle('400');
        $(this).toggleClass('toggled');
    });
    // tab-mod
    $('.tab-mod .tab-hd li a').click(function(){
        $(this).addClass('active').parent('.tab-mod .tab-hd li').siblings().children('a').removeClass();
        $(".tab-mod .tab-bd").eq($('.tab-mod .tab-hd li a').index(this)).show().siblings('.tab-bd').hide();
    });
})
</script>

<div class="container channel">
  <div class="container-inner">
    <div class="main-hd">
      <div class="switch-channel-mod">
        <div class="switch-channel-hd"><a href="javascript:void(0)">切换频道</a></div>
        <div class="switch-channel-bd tab-mod">
          <div class="tab-hd">
            <ul>
              <li><a href="javascript:void(0)" class="active">本地频道</a></li>
              <li><a href="javascript:void(0)">央视频道</a></li>
              <li><a href="javascript:void(0)">各省卫视</a></li>
              <li><a href="javascript:void(0)">我的频道</a></li>
            </ul>
          </div>
          <div id="tab1" class="tab-bd" style="display:block;">
            <div class="channel-list">
              <ul>
              <?php foreach($local_station as $station):?>
                <li>
                  <div class="channel-logo"><span class="station"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><img src="<?php echo thumb_url($station->getLogo(), 44, 24) ?>" alt="<?php echo $station->getName()?>"></a></span> <span class="channel"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><?php echo $station->getName()?></a></span> </div>
                </li>
               <?php endforeach;?>
              </ul>
            </div>
          </div>
          <div id="tab2" class="tab-bd" style="display:none;">
            <div class="channel-list">
              <ul>
              <?php foreach($cctv_station as $station):?>
                <li>
                  <div class="channel-logo"><span class="station"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><img src="<?php echo thumb_url($station->getLogo(), 44, 24) ?>" alt="<?php echo $station->getName()?>"></a></span> <span class="channel"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><?php echo $station->getName()?></a></span> </div>
                </li>
                <?php endforeach;?>
              </ul>
            </div>
          </div>
          <div id="tab3" class="tab-bd" style="display:none;">
            <div class="channel-list">
              <ul>
              <?php foreach($tv_station as $station):?>
                <li>
                  <div class="channel-logo"><span class="station"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><img src="<?php echo thumb_url($station->getLogo(), 44, 24) ?>" alt="<?php echo $station->getName()?>"></a></span> <span class="channel"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><?php echo $station->getName()?></a></span> </div>
                </li>
              <?php endforeach;?>
              </ul>
            </div>
          </div>
          <div id="tab4" class="tab-bd" style="display:none;">
            <div class="channel-list">
              <ul>
              <?php if($mytv==NULL) {?>
              <li><div>暂无</div></li>
              <?php } else {?>
              <?php foreach($mytv as $station):?>
                <li>
                  <div class="channel-logo"><span class="station"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><img src="<?php echo thumb_url($station->getLogo(), 44, 24) ?>" alt="<?php echo $station->getName()?>"></a></span> <span class="channel"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><?php echo $station->getName()?></a></span> </div>
                </li>
              <?php endforeach;?>
              <?php } ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="tv-station">
        <h1><?php echo $channel->getName()?></h1>
        <div class="station"><img alt="<?php echo $channel->getName()?>" src="<?php echo thumb_url($channel->getLogo(), 88, 48)?>"></div>
        <div class="fav-channel"><a href="javascript:addfavorite('<?php echo $channel->getId();?>')" class="popup-tip" title="收藏频道">收藏</a></div>
        <?php $programs_tow = $channel->getNowPrograms(2)?>
        <?php if(!empty ($programs_tow)): $i=0?>
        <?php foreach ($programs_tow as $program):$i++?>
        <?php if($i<=1):?>
        <small class="play-now"><span class="label">正在播放：</span><span class="time"><?php echo date("H:i",strtotime($program->getTime())); ?> -  <?php echo date("H:i",strtotime($program->getEndTime()->format("Y-m-d H:i:s"))); ?></span> <span class="title"><?php echo $program->getName() ?></span></small>
        <?php endif ?>
        <?php endforeach ?>
        <?php endif ?>
        </div>
    </div>
    <div class="main-bd clearfix">
      <!-- channel start -->
      <section id="section">
        <?php if($programswiki) : $ids = array();?>
        <div class="recommended">
          <h3>频道推荐</h3>
          <div id="slides">
            <div class="slides_container">
              <ul>
                <?php foreach($programswiki as $program) :?>
                <?php $wiki = $program->getWiki();?>
                <?php if ($wiki && !in_array((string)$wiki->getId(), $ids)) :?>
                <li>
                  <div class="poster">
                  <a href="<?php echo url_for('wiki/show?slug='. $wiki->getSlug())?>" target="_blank"><img alt="<?php echo $wiki->getTitle()?>" src="<?php echo thumb_url($wiki->getCover(), 80, 120)?>"></a></div>
                  <h4>
                      <span class="title"><a href="<?php echo url_for('wiki/show?slug='. $wiki->getSlug())?>"><?php echo $wiki->getTitle()?></a></span>
                      <?php
                        $released = $wiki->getReleased();
                        $episodes = $wiki->getEpisodes();
                        if (!empty($released) || !empty($episodes)) {
                      ?>
                      <small>(<?php if (! empty($released)) {?><span class="release-date"><?php echo date('Y', strtotime($released))?></span> <?php }?>
                      <?php if (! empty($episodes)) {?><span  class="episode-nmb"><?php echo $episodes?> 集</span> <?php }?>)</small>
                      <?php } ?>
                  </h4>
                  <div class="text-block genre"><span class="label">类型：</span>
                  <?php foreach($wiki->getTags() as $tag) :?>
                    <span class="param"><a href="<?php echo url_for('search/index?q=tag:'. $tag)?>" target="_blank"><?php echo $tag?></a></span>
                  <?php endforeach ?>
                  </div>
                  <div class="text-block"><span class="label">剧情简介：</span>
                  <span class="param"><?php echo $wiki->getHtmlCache(60, ESC_RAW); ?><a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug()) ?>#detail" target="_blank">详细&raquo;</a></span></div>
                  <div class="play-time">
                    <?php if(strtotime($program->getTime() > time())):?>
                         <?php echo date("m月d日 H:i",strtotime($program->getTime()));?> 播出
                      <?php else:?>
                         今天 <?php echo date("H:i",strtotime($program->getTime()));?> 播出
                      <?php endif;?>
                  </div>
                </li>
                <?php $ids[] = (string) $wiki->getId();?>
                <?php endif?>
                <?php endforeach?>
              </ul>
            </div>
            <?php $pageSize = ceil(count($ids)/2)?>
            <div class="scrollnav">
              <ul>
                <?php for($i = 1; $i<= $pageSize; $i++) :?>
                <li <?php echo (1 == $i) ? 'class="active"' : '' ?>><a href="javascript:void(0)"><?php echo $i?></a></li>
                <?php endfor;?>
              </ul>
              <span class="prev"><a href="javascript:void(0)" class="disabled">PREV</a></span> <span class="next"><a href="javascript:void(0)">NEXT</a></span> </div>
          </div>
        </div>
        <?php endif?>
        <div class="mod" id="tv-listing-channel">
          <div class="hd">
            <h3>收视指南</h3>
          </div>
          <div class="tab-mod">
            <div class="tab-hd timeline clearfix">
              <ul>
                <?php
                    $timestamp = strtotime($date);
                    $w = date('w', $timestamp);
                    $w = ($w == 0) ? 7 : $w;
                    $weeks_zh = array('日', '一', '二', '三', '四', '五', '六');
                    $week_format = '<li><a href="%s"%s><span class="week">周%s</span> <span class="date">(%s)</span></a></li>';
                    // 循环生产日期
                    for ($i = 1; $i < 8; $i++) {
                        $n = $i - $w;
                        $week_timestamp  = $timestamp + $n * 86400;
                        printf( $week_format,
                                lurl_for('channel/show?id=' . $channel_id . '&date=' .date('Y-m-d',$week_timestamp)),
                                (($week_timestamp == $timestamp) ? ' class="active"' : ''),
                                $weeks_zh[date('w',$week_timestamp)],
                                date('m-d', $week_timestamp)
                              );
                    }
                ?>
              </ul>
            </div>
            <?php if($programs) :?>
            <div class="tab-bd" style="display:block;">
              <ul>
                <?php
                foreach ($programs as $program) :
                    $play_status = $program->getPlayStatus();
                    $wiki = $program->getWiki();
                    $play_style = ($play_status == 'playing') ?  'class="on-air info"' :
                            (($play_status == 'played') ? (($wiki) ? 'class="played info"' : 'class="played"')
                            : (($wiki) ? 'class="info"' : ''));
                ?>
                <?php if($wiki): ?>
                <li <?php echo $play_style?>>
                  <h4 class="title"><a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug()) ?>" target="_blank"><?php echo $program->getName() ?></a></h4>
                  <div class="time"><?php echo date("H:i",strtotime($program->getTime())); ?></div>
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
                  <div class="desc">
                    <p><?php echo $wiki->getHtmlCache(150, ESC_RAW); ?>... <a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug()) ?>#detail" target="_blank">详细&raquo;</a></p>
                  </div>
                  <div class="poster">
                    <a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug()) ?>" target="_blank"><img src="<?php  echo thumb_url($wiki->getCover(), 80, 120) ?>"></a>
                  </div>
                </li>
                <?php else :?>
                <li <?php echo $play_style?>>
                  <h4 class="title"><?php echo $program->getName() ?></h4>
                  <div class="time"><?php echo date("H:i",strtotime($program->getTime())); ?></div>
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
                <?php endif;?>
                <?php endforeach; ?>
              </ul>
            </div>
            <?php else :?>
            <div class="tab-bd" style="display:block;">
              <div class="no-data"><?php echo date('m-d', $timestamp)?> 暂无数据...</div>
            </div>
            <?php endif?>
            <div class="epg-tips">电视节目以电视台当天播出时间为准，如有变更，恕不另行通知；电视收视范围以当地有线电视服务商实际提供内容为准。</div>
          </div>
        </div>
      </section>
      <aside id="aside">
        <?php include_partial('global/ad')?>
        <?php include_component('channel', 'hotplay')?>
      </aside>
      <!-- channel end -->
    </div>
  </div>
</div>