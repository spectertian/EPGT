<?php slot('v_Movie')?>
<style type='text/css'>
.movie-id {background:#b91b12 url('public/topic_show.jpg') no-repeat center 48px;}
.topic .container { padding:200px 0 25px;}
</style>
<body  xmlns:v="http://rdf.data-vocabulary.org/#" typeof="v:Movie">
<?php end_slot();?>
<div class="container movie">
  <div class="container-inner">
    <?php include_partial('nav_tool', array('wiki' => $wiki, 'related_programs' => $related_programs,'pinglun'=>$pinglun))?>
        <div class="overview drama clearfix">
          <div class="poster"><img width="172" height="255" src="<?php echo thumb_url($wiki->getCover(), 172, 255)?>" alt="<?php echo $wiki->getTitle() ?> 海报" itemprop="photo"></div>
          <div class="info">
            <?php if($wiki->getReleased()): ?>
            <div class="text-block"><span class="label">上映时间：</span><span class="param" property="v:initialReleaseDate" content="<?php echo $wiki->getReleased()?>"><?php echo $wiki->getReleased()?></span></div>
            <?php endif; ?>
            <?php if($wiki->getEpisodes()): ?>
            <div class="text-block"><span class="label">集数：</span><span class="param" property="v:runtime" content="<?php echo $wiki->getEpisodes()?>"><?php echo $wiki->getEpisodes() ?>集</span></div>
            <?php endif; ?>
            <?php if($tags = $wiki->getTags()): $i= 0 ?>
            <div class="text-block">
                <span class="label">类型：</span>
                <?php foreach($tags as $tag) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''?>
                <span class="param"><a href="<?php echo url_for('search/index?q=tag:'. $tag)?>" title="<?php echo $tag?>" property="v:genre"><?php echo $tag?></a></span>
                <?php endforeach;?>
            </div>
            <?php endif; ?>
            <?php if($Directors = $wiki->getDirector()): $i = 0 ?>
            <div class="text-block">
                <span class="label">导演：</span>
                <?php foreach($Directors as $Director) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''?>
                <span class="param"><a href="<?php echo url_for('search/index?q=' . urlencode($Director)) ?>" title="<?php echo $Director?>" rel="v:directedBy"><?php echo $Director?></a></span>
                <?php endforeach;?>
            </div>
            <?php endif; ?>
            <?php if($Writers = $wiki->getWriter()): $i = 0 ?>
            <div class="text-block">
                <span class="label">编剧：</span>
                <?php foreach($Writers as $Writer) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''?>
                <span class="param"><a href="<?php echo url_for('search/index?q=' . urlencode($Writer)) ?>" title="<?php echo $Writer?>"><?php echo $Writer?></a></span>
                <?php endforeach;?>
            </div>
            <?php endif; ?>
            <?php if($Stars = $wiki->getStarring()): $i = 0 ?>
            <div class="text-block">
                <span class="label">主演：</span>
                <?php foreach($Stars as $Star) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''?>
                <span class="param"><a href="<?php echo url_for('search/index?q=' . urlencode($Star)) ?>" title="<?php echo $Star?>" rel="v:starring"><?php echo $Star?></a></span>
                <?php endforeach;?>
            </div>
            <?php endif; ?>
            <?php if($wiki->getCountry()): ?>
            <div class="text-block"><span class="label">国家/地区：</span><span class="param"><?php echo $wiki->getCountry()?></span></div>
            <?php endif;?>
            <?php if($wiki->getLanguage()): ?>
            <div class="text-block"><span class="label">语言：</span><span class="param"><?php echo $wiki->getLanguage() ?></span></div>
            <?php endif;?>
            <?php if($Distributors = $wiki->getDistributor()): $i=0 ?>
            <div class="text-block">
                <span class="label">出品公司：</span>
                <?php foreach($Distributors as $Distributor) : $i++;?>
                <span class="param"><a href="<?php echo url_for('search/index?q=' . urlencode($Distributor)) ?>" title="<?php echo $Distributor?>"><?php echo $Distributor?></a></span><?php echo ($i > 1) ? '/' : ''?>
                <?php endforeach;?>
            </div>
            <?php endif;?>
            <?php if($wiki->getProduced()): ?>
            <div class="text-block"><span class="label">制作日期：</span><span class="param"><?php echo $wiki->getProduced()?></span></div>
            <?php endif;?>
            <div class="text-block summary">
              <p><span class="label">剧情简介：</span><span class="param"><?php echo $wiki->getHtmlCache(150, ESC_RAW); ?>... <a href="#detail">详细&raquo;</a></span></p>
            </div>
          </div>
        </div>
        <div class="mod" id="vedio-resources">
          <div class="hd">
            <h3>版权片源</h3>
            <!--<div class="r"><a href="#">我要提供片源？</a></div>-->
          </div>
          <?php if ($PlayList = $wiki->getPlayList()) :?>
          <div class="bd clearfix">
            <ul>
              <?php foreach($PlayList as $playlist) :?>
              <li class="play-btn">
                <div class="on-demand play-<?php echo $playlist->getReferer()?>">
                    <a href="javascript:void(0)" class="popup-tip" title="播放<?php echo $playlist->getRefererZhcn()?>片源"><?php echo $playlist->getRefererZhcn()?>视频</a>
                </div>
                <div class="episodes-mod">
                  <div class="icon-arrow"></div>
                  <div class="close"><a href="javascript:void(0)">x</a></div>
                  <div class="episodes-hd"><span class="label">分集点播：</span>
                      <?php $countVideo = $playlist->countVideo()?>
                      <?php $loop = $countVideo - 30;?>
                      <?php for($i = 1; $i < $loop; $i+=30) :?>
                      <a href="javascript:void(0)" <?php echo ($i == 1) ? 'class="active"' : ''?>><?php printf("%d-%d", $i, $i+29)?></a>
                      <?php endfor;?>
                      <a href="javascript:void(0)" <?php echo ($i < 30) ? 'class="active"' : ''?>><?php printf("%d-%d", $i, $countVideo)?></a>
                  </div>
                  <?php $videos = $playlist->getVideos()?>
                  <div class="episodes-bd clearfix" style="display:block;">
                    <ul>
                      <?php $j = 0 ?>
                      <?php foreach($videos as $video) : $j++ ?>
                      <li><a href="<?php echo $video->getUrl()?>" target="_blank" title="<?php echo $video->getTitle()?>"><?php echo $video->getMark()?></a></li>
                      <?php if (($countVideo > 30) && (fmod($j, 30) == 0) ) :?>
                    </ul>
                  </div>
                  <div class="episodes-bd clearfix">
                    <ul>
                      <?php endif;?>
                      <?php endforeach;?>
                    </ul>
                  </div>
                </div>
              </li>
              <?php endforeach;?>
            </ul>
          </div>
          <?php else: ?>
          <div class="no-resource">很抱歉，该节目还没有片源！</div>
          <?php endif;?>
        </div>
        <?php if(count($related_programs) > 0): ?>
        <?php use_helper('WeekDays') ?>
        <div class="mod" id="tv-listing">
          <div class="hd">
            <h3>收视指南</h3>
          </div>
          <div class="tab-mod">
          <div class="tab-hd  timeline">
            <ul>
              <?php foreach(weekdays_nav() as $day): ?>
              <li>
                  <a href="javascript:void(0)" <?php echo ($day['date'] == date('m-d')) ? 'class="active"' : ''?>><span class="week"><?php echo $day['week_cn'] ?></span> <span class="date">(<?php echo $day['date'] ?>)</span></a>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php $weekday = (0 == date('N')) ? 7 : date('N'); $today = time();?>
          <?php for($d = 1; $d <= 7; $d++) :?>
          <?php $n = $d - $weekday; $date = date('Y-m-d', $today + $n * 86400);?>
          <?php  ?>
          <div class="tab-bd epg-list" id="date<?php echo $date?>" <?php echo ($date == date('Y-m-d') ? 'style="display:block;"' : '')?>>
            <?php if (isset($related_programs[$date])) :?>
            <ul>
              <?php foreach($related_programs[$date] as $program): ?>
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
            <?php else :?>
            <div class="no-data"><?php echo $date?> 暂无播出数据...</div>
            <?php endif;?>
          </div>
          <?php endfor;?>
          <div class="epg-tips">电视节目以电视台当天播出时间为准，如有变更，恕不另行通知；电视收视范围以当地有线电视服务商实际提供内容为准。</div>
          </div>
        </div>
        <?php endif; ?>
        <div class="tab-mod" id="detail">
          <div class="tab-hd">
            <ul>
              <li><a href="javascript:void(0)" class="active">剧情介绍</a></li>
              <?php if ($dramas_total > 0) :?>
              <li><a href="javascript:void(0)">分集剧情</a></li>
              <?php endif;?>
            </ul>
          </div>
          <div class="tab-bd" style="display:block;">
            <div class="storyline"><?php echo $wiki->getHtmlCache(ESC_RAW); ?></div>
            <?php include_partial('screenshots', array('wiki' => $wiki))?>
          </div>
          <?php if($dramas): ?>
          <div id="dramas" class="tab-bd" style="display:none;">
            <div class="episode-navi">
            <?php $loop = $dramas_total - 10;?>
            <?php for($i = 1; $i <= $loop; $i+=10): ?>
                <?php if ($i == 1) :?>
                <span class="active"><?php printf("%d-%d", $i, $i+9)?></span>
                <?php else :?>
                | <a href="javascript:loadDrama(<?php echo $i?>)"><?php printf("%d-%d", $i, $i+9)?></a>
                <?php endif;?>
            <?php endfor; ?>
            <?php if ($i <= $dramas_total) :?>
            | <a href="javascript:loadDrama(<?php echo $i?>)"><?php printf("%d-%d", $i, $dramas_total)?></a>
            <?php endif;?>
            </div>
            <div class="episodes">
              <dl>
                <?php foreach($dramas as $drama): ?>
                <dt><?php echo $drama->getTitle()?></dt>
                <dd><p><?php echo $drama->getContent()?></p></dd>
                <?php endforeach; ?>
              </dl>
            </div>
            <div class="episode-navi">
            <?php $loop = $dramas_total - 10;?>
            <?php for($i = 1; $i <= $loop; $i+=10): ?>
                <?php if ($i == 1) :?>
                <span class="active"><?php printf("%d-%d", $i, $i+9)?></span>
                <?php else :?>
                | <a href="javascript:loadDrama(<?php echo $i?>)"><?php printf("%d-%d", $i, $i+9)?></a>
                <?php endif;?>
            <?php endfor; ?>
            <?php if ($i <= $dramas_total) :?>
            | <a href="javascript:loadDrama(<?php echo $i?>)"><?php printf("%d-%d", $i, $dramas_total)?></a>
            <?php endif;?>
            </div>
        </div>
       </div>
        <?php endif;?>
        <?php include_partial('comments', array('wiki' => $wiki, 'weibo_sina'=>$weibo_sina, 'weibo_qqt'=>$weibo_qqt))?>
      </section>
      <aside id="aside">
        <?php include_partial('global/ad')?>
        <?php include_component('wiki', 'related_movies')?>
        <?php include_partial('comment_tags', array('commentTags' => $wiki->getCommentTags()))?>
      </aside>
  </div>
</div>
</div>
<script type="text/javascript">
// episodes on demand
$(document).click(function() {
    $('#vedio-resources .episodes-mod').hide();
});

$('#vedio-resources .episodes-mod').click(function (event){
    event.stopPropagation();
});

$('#vedio-resources .episodes-mod').css({ 'z-index': '101' });
$('#vedio-resources .play-btn').css({ 'z-index': '2' });

$('#vedio-resources .on-demand a').click(function (event){
    $(this).parents('#vedio-resources .play-btn').css({ 'z-index': '100' }).siblings().css({ 'z-index': '2' });
    $(this).parents('#vedio-resources .play-btn').siblings().children('.episodes-mod').hide()
    $(this).parents('#vedio-resources .play-btn').children('.episodes-mod').toggle();
    event.stopPropagation();
});

$('#vedio-resources li.play-btn:nth-child(3n+1) .episodes-mod').css({ 'left': '0' });
$('#vedio-resources li.play-btn:nth-child(3n+2) .episodes-mod').css({ 'left': '-212px' });
$('#vedio-resources li.play-btn:nth-child(3n+3) .episodes-mod').css({ 'left': '-424px' });
$('#vedio-resources li.play-btn:nth-child(3n+1) .episodes-mod .icon-arrow').css({ 'left': '87px' });
$('#vedio-resources li.play-btn:nth-child(3n+2) .episodes-mod .icon-arrow').css({ 'left': '299px' });
$('#vedio-resources li.play-btn:nth-child(3n+3) .episodes-mod .icon-arrow').css({ 'left': '499px' });

$('#vedio-resources .episodes-mod .close a').click(function (){
    $(this).parents('.episodes-mod').hide();
});

$('.episodes-hd a').click(function(){
    $(this).addClass('active').siblings().removeClass();
    $(".episodes-bd").eq($('.episodes-hd a').index(this)).show().siblings('.episodes-bd').hide();
});

// tv-listing
$('#tv-listing .timeline li').click(function(){
    $(this).addClass('active').siblings().removeClass();
    $("#tv-listing .epg-list").eq($('#tv-listing .timeline li').index(this)).show().siblings('.epg-list').hide();
});
$('#tv-listing .remind a').click(function (event){
    $(this).toggleClass('active');
});

function loadDrama(offset) {
    $.ajax({
        url: '<?php echo url_for('wiki/drama')?>',
        type: 'get',
        dataType: 'html',
        data: {'id': '<?php echo $wiki->getId()?>','offset': offset },
        success: function(html){
            if (html == 0) {
                alert('出错啦...');
            } else {
                $('#dramas').html(html);
            }
        }
    });
}
</script>