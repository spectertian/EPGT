  <div class="path-navi">
    <a href="<?php echo url_for('/') ?>">首页</a> &gt;
    <a href="#">人物</a> &gt;
    <a href="#">足球球员</a> &gt;
    <?php echo $wiki->getTitle() ?>
  </div>

  <div id="wrapper" class="tv-drama">
    <div class="primary">
      <div class="module summary">
        <div class="poster">
          <img src="<?php echo thumb_url($wiki->getCover(), 150, 220) ?>" alt="<?php echo $wiki->getTitle() ?>"><br>
          <a href="#">更新描述或照片</a>
        </div>
        <div class="data">

          <h1 class="title"><?php echo $wiki->getTitle() ?><?php if($wiki->getEnglishName()): ?>（<?php echo $wiki->getEnglishName() ?>）<?php endif; ?></h1>
          <?php if($wiki->getTeam()): ?>
          <div class="data-field">球队：<?php echo $wiki->getTeam() ?></div>
          <?php endif; ?>
          <?php if($wiki->getPosition()): ?>
          <div class="data-field">位置：<?php echo $wiki->getPosition() ?></div>
          <?php endif; ?>
          <?php if($wiki->getNumber()): ?>
          <div class="data-field">号码：<?php echo $wiki->getNumber() ?></div>
          <?php endif; ?>
          <?php if($wiki->getNickname()): ?>
          <div class="data-field">昵称：<?php echo $wiki->getNickname() ?></div>
          <?php endif; ?>
          <?php if($wiki->getSex()): ?>

		  <div class="data-field">性别：<?php echo $wiki->getSex() ?></div>
          <?php endif; ?>
          <?php if($wiki->getBirthday()): ?>
		  <div class="data-field">生日：<?php echo $wiki->getBirthday() ?></div>
          <?php endif; ?>
          <?php if($wiki->getNationality()): ?>
		  <div class="data-field">国籍：<?php echo $wiki->getNationality() ?></div>
          <?php endif; ?>
          <?php if($wiki->getZodiac()): ?>
		  <div class="data-field">星座：<?php echo $wiki->getZodiac() ?></div>
          <?php endif; ?>

          <div class="clear"></div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="module bio">
        <h3>简介 <small class="help"><a href="#">编辑&raquo;</a></small></h3>
        <?php echo $wiki->getHtmlCache(ESC_RAW); ?>
      </div>
    </div>
    <div class="secondary">

    </div>
    <div class="clear"></div>
  </div>
