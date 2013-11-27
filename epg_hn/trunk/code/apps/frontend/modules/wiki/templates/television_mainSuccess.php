<?php slot('v_Movie')?>
<style type='text/css'>
.movie-id {background:#b91b12 url('public/topic_show.jpg') no-repeat center 48px;}
.topic .container { padding:200px 0 25px;}
</style>
<body  xmlns:v="http://rdf.data-vocabulary.org/#" typeof="v:Movie">
<?php end_slot();?>
<div class="container movie">
  <div class="container-inner">
     <?php include_partial('nav_tool', array('wiki' => $wiki, 'related_programs' => $related_programs))?>
        <div class="overview drama clearfix">
          <div class="poster"><img width="172" height="255" src="<?php echo thumb_url($wiki->getCover(), 172, 255)?>" alt="<?php echo $wiki->getTitle() ?> 海报" itemprop="photo"></div>
          <div class="info">
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
            <?php if ($wiki->getChannel()) :?>
            <div class="text-block"><span class="label">播出频道：</span><span class="param"><?php echo $wiki->getChannel()?></span></div>
            <?php endif?>
            <?php if ($wiki->getPlayTime()) :?>
            <div class="text-block"><span class="label">播出时间：</span><span class="param"><?php echo $wiki->getPlayTime()?></span></div>
            <?php endif?>
            <?php if ($wiki->getRuntime()) :?>
            <div class="text-block"><span class="label">播出时长：</span><span class="param"><?php echo $wiki->getRuntime()?></span></div>
            <?php endif?>
            <?php if($wiki->getCountry()): ?>
            <div class="text-block"><span class="label">国家/地区：</span><span class="param"><?php echo $wiki->getCountry()?></span></div>
            <?php endif;?>
            <?php if($wiki->getLanguage()): ?>
            <div class="text-block"><span class="label">语言：</span><span class="param"><?php echo $wiki->getLanguage()?></span></div>
            <?php endif;?>
            <div class="text-block summary">
              <p><span class="label">栏目介绍：</span><span class="param" property="v:summary"><?php echo $wiki->getHtmlCache(150, ESC_RAW)?>... <a href="#detail">详细&raquo;</a></span></p>
            </div>
          </div>
        </div>
        <?php include_partial('program_guide', array('programs' => $related_programs))?>
	<div class="mod" id="detail">
          <div class="hd">
            <h3>栏目介绍</h3>
          </div>
          <div class="bd">
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
        </div>
      </aside>
    </div>
  </div>
</div>