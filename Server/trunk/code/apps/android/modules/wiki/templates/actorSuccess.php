<?php use_helper('GetFileUrl')?>
<div id="toptab">
<a class="btn-l" href="<?php echo ($sf_user->getAttribute('wikiback')) ? $sf_user->getAttribute('wikiback') : url_for('channel/index')?>">返回</a>
<div class="tit"><?php echo $wiki->getTitle()?></div>
<div class="clear"></div>
</div>
<div id="wrapper" class="bluebg">
<div class="row-container epg03" id="scroller">
<div class="tu2">
<?php if($wiki->getCover()): ?>
    <img src="<?php echo thumb_url($wiki->getCover(), 136, 199)?>"  alt="<?php echo $wiki->getTitle() ?>" width="136" height="199"/>
<?php endif; ?>
</div>
   
<div class="des">
  <p>
     <?php echo $wiki->getSex() ?>
     <?php echo ($wiki->getBirthday()) ? '| 生于 '.$wiki->getBirthday() : '' ;?>
     <?php echo ($wiki->getBirthplace()) ? '| '. $wiki->getBirthplace() : '' ;?>
  </p>
  <?php if($wiki->getOccupation()): ?>
  <p>职业: <?php echo $wiki->getOccupation()?></p>
  <?php endif;?>
  <?php if($wiki->getZodiac()): ?>
  <p>星座：<?php echo $wiki->getZodiac()?></p>
  <?php endif;?>
  <?php if($wiki->getBloodType()): ?>
  <p>血型：<?php echo $wiki->getBloodType()?> 型</p>
  <?php endif;?>
  <?php if($wiki->getHeight()): ?>
  <p>身高：<?php echo $wiki->getHeight()?> cm</p>
  <?php endif;?>
  <?php if($wiki->getWeight()): ?>
  <p>体重：<?php echo $wiki->getWeight()?> kg</p>
  <?php endif;?>
  <?php if($wiki->getDebut()): ?>
  <p>出道日期：<?php echo $wiki->getDebut()?></p>
  <?php endif;?>
</div>
</div>
</div>