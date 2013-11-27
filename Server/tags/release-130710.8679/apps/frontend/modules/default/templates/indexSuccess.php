<?php use_javascript('grid.js')?>
<?php use_javascript('slideframe.js')?>
<div class="container index">
  <div class="container-inner">
    <div class="main-hd">
      <div class="slideframe">
        <div id="big_frame" class="frame clearfix">
          <ul id="big_list">
            <?php foreach($recommends as $recommend) :?>  
            <li>
              <div class="slide-photo"><a href="<?php echo $recommend->getUrl();?>"><img src="<?php echo thumb_url($recommend->getPic(), 980, 300)?>" alt=""></a></div>
              <?php if($recommend->getIsdescDisplay()==true):?>
              <div class="overlay"></div>
              <div class="editors-picks">
                <h3><?php echo $recommend->getTitle(); ?></h3>
                <div class="text-block"><span class="label">编辑推荐：</span><span class="param"><?php echo $recommend->getDesc(200);?></span></div>
                <div class="more-info"><a href="<?php echo $recommend->getUrl();?>">查看详情</a></div>
              </div>
              <?php endif;?>
            </li>
            <?php endforeach?>
          </ul>
        </div>
        <div class="prev"><a id="back" class="slide_nav" href="javascript:void(0)">prev</a></div>
        <div id="small_frame" class="l_frame">
          <ul id="small_list">
            <?php foreach($recommends as $recommend) :?>
            <li><img src="<?php echo thumb_url($recommend->getSmallPic(), 144, 81)?>" alt="<?php echo $recommend->getTitle();?>"></li>
            <?php endforeach?>
          </ul>
        </div>
        <div class="next"><a id="forward" class="slide_nav" href="javascript:void(0)">next</a></div>
      </div>
    </div>
    <div class="main-bd">
      <h2>大家喜欢的节目 ... <span class="date"><?php echo date('Y年m月d日', time())?></span></h2>
      <!--
      <div class="rec-filter clearfix">
        <ul>
          <li><a href="#">全部节目</a></li>
          <li><a href="#" class="active">电视节目</a></li>
        </ul>
      </div>
      -->
      <div class="rec-list">
        <?php if ($wikiplays) :?>
        <ul>
          <?php foreach($wikiplays as $wikiplay) :?>
          	<?php $wiki = $wikiplay->getWiki()?>
	          <?php if ($wiki->getScreenshotsCount() > 1):?>
	          <li class="eachrec">
	            <div class="program">
	               <div class="stills">
	                <a href="<?php echo url_for('@wiki_show?slug='. $wiki->getSlug())?>" slug="<?php echo $wiki->getSlug()?>">
	                   <?php if ($screenshot = $wiki->getScreenshots()) :?>
	                    <?php if ($wiki->getScreenshotsCount() > 1) :?>
	                       <img src="<?php echo thumb_url($screenshot[rand(0, $wiki->getScreenshotsCount()-1)], 205, 300)?>"/>
	                    <?php else :?>
	                     <img src="<?php echo thumb_url($screenshot[0], 205, 300)?>" />
	                    <?php endif;?>
	                   <?php else:?>
	                   <img src="<?php echo thumb_url('1313572849892.png', 205, 300)?>" />
	                   <?php endif;?>
	                </a>
	              </div>
	              <h4><a href="<?php echo url_for('@wiki_show?slug='.$wiki->getSlug()) ?>"><?php echo $wiki->getTitle();?></a></h4>
	          </li>
	          <?php endif;?>
          <?php endforeach;?>
        </ul>
        <?php endif;?>
      </div>
      <!-- epg end -->
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
    toolTiper('.stills a', 20, 100, 474);
});
</script>