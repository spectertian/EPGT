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
      <img src="<?php echo thumb_url($wiki->getCover(), 150, 220) ?>" alt="<?php echo $wiki->getTitle() ?>"><br>
    </div>
    <div class="data" style="width:470px;">
      <h1 class="title"><?php echo $wiki->getTitle() ?></h1>
      <?php if($wiki->getCoach()): ?>
      <div class="data-field">主教练：<?php echo $wiki->getCoach(); ?></div>
      <?php endif; ?>
      <?php if($wiki->getArena()): ?>
      <div class="data-field">主场：<?php echo $wiki->getArena(); ?></div>
      <?php endif; ?>
      <?php if($wiki->getCity()): ?>
      <div class="data-field">城市：<?php echo $wiki->getCity(); ?></div>
      <?php endif; ?>
      <?php if($wiki->getManager()): ?>
      <div class="data-field">经理：<?php echo $wiki->getManager(); ?></div>
      <?php endif; ?>
      <?php if($wiki->getEnglishName()): ?>
      <div class="data-field">英文名：<?php echo $wiki->getEnglishName(); ?></div>
      <?php endif; ?>
      <?php if($wiki->getFounded()): ?>
      <div class="data-field">建队时间：<?php echo $wiki->getFounded(); ?></div>
      <?php endif; ?>
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
