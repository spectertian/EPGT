<?php slot('v_Movie')?>
<style type='text/css'>
.movie-id {background:#b91b12 url('public/topic_show.jpg') no-repeat center 48px;}
.topic .container { padding:200px 0 25px;}
</style>
<body xmlns:v="http://rdf.data-vocabulary.org/#" typeof="v:Movie">
<?php end_slot();?>
<div class="container movie">
  <div class="container-inner">
     <?php include_partial('nav_tool', array('wiki' => $wiki, 'related_programs' => $related_programs))?>
        <div class="overview drama clearfix">
          <div class="poster"><img width="172" height="255" src="<?php echo thumb_url($wiki->getCover(), 172, 255)?>" alt="<?php echo $wiki->getTitle() ?> 海报" itemprop="photo"></div>
          <div class="info">
            <?php if($wiki->getReleased()): ?>
            <div class="text-block"><span class="label">上映时间：</span><span class="param" property="v:initialReleaseDate" content="<?php echo $wiki->getReleased()?>"><?php echo $wiki->getReleased()?></span>
            </div>
            <?php endif; ?>
            <?php if($wiki->getRuntime()): ?>
            <div class="text-block"><span class="label">片长：</span><span class="param" property="v:runtime" content="<?php echo $wiki->getRuntime()?>"><?php echo $wiki->getRuntime()?>分钟</span></div>
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
          <?php if ($videos = $wiki->getVideos()) :?>
          <div class="bd clearfix">
            <ul>
              <?php foreach($videos as $video) :?>
              <li class="play-btn">
                <div class="on-demand play-<?php echo $video->getReferer()?>">
                    <a href="<?php echo $video->getUrl()?>" class="popup-tip" title="播放<?php echo $video->getRefererZhcn()?>片源" target="_blank"><?php echo $video->getRefererZhcn()?>视频</a>
                </div>
              </li>
              <?php endforeach;?>
            </ul>
          </div>
          <?php else: ?>
          <div class="no-resource">很抱歉，该节目还没有片源！</div>
          <?php endif;?>
        </div>
        <?php include_partial('program_guide', array('programs' => $related_programs))?>
        <div class="tab-mod" id="detail">
          <div class="tab-hd">
            <ul>
              <li><a href="javascript:void(0)" class="active">剧情介绍</a></li>
            </ul>
          </div>
          <div class="tab-bd" style="display:block;">
            <div class="storyline"><?php echo $wiki->getHtmlCache(ESC_RAW); ?></div>
            <?php include_partial('screenshots', array('wiki' => $wiki))?>
          </div>
        </div>
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