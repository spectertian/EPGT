  <div class="path-navi">
      <a href="<?php echo url_for('/') ?>">首页</a> &gt;
      <a href="#">赛事</a> &gt;
      <a href="#">球队</a> &gt;
      <?php echo $wiki->getTitle() ?>
  </div>
<div id="wrapper" class="tv-drama">

<div class="primary">
  <div class="module summary">
    <div class="poster" style="width:200px;">
        <img src="<?php echo thumb_url($wiki->getCover(), 150, 220) ?>" alt="" style="width:220px; height:150px; overflow:hidden;"><br>
    </div>
    <div class="data" style="width:470px;">
      <h1 class="title"><?php echo $wiki->getTitle() ?></h1>
      <div class="data-field">部区：<?php echo $wiki->getConference() ?></div>
      <div class="data-field">分区：<?php echo $wiki->getDivision() ?></div>
      <div class="data-field">主教练：<?php echo $wiki->getCoach() ?></div>

      <div class="data-field">主场：<?php echo $wiki->getArena() ?></div>
      <div class="data-field">城市：<?php echo $wiki->getCity() ?></div>
      <div class="data-field">经理：<?php echo $wiki->getManager() ?></div>
      <div class="data-field">英文名：<?php echo $wiki->getEnglishName() ?></div>
      <div class="data-field">建队时间：<?php echo $wiki->getFounded() ?></div>
    </div>

    <div class="clear"></div>
  </div>
  <div class="module bio">
    <h3>简介</h3>
    <?php echo $wiki->getHtmlCache(ESC_RAW); ?>
  </div>
</div>
<div class="clear"></div>
</div>
