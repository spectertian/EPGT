<?php use_helper('GetFileUrl')?>
<div id="toptab">
<a class="btn-l" href="<?php echo ($sf_user->getAttribute('wikiback')) ? $sf_user->getAttribute('wikiback') : url_for('channel/index')?>">返回</a>
<div class="tit"><?php echo $wiki->getTitle()?></div>
<div class="clear"></div>
</div>
<div id="wrapper" class="bluebg">
<div class="row-container epg03" id="scroller">

<?php if (!empty($channels)) :?>
<?php foreach($channels as $key => $channel) :?>
<div class="col">
    <div class="txt1 channel">
        <?php if ($channel->getLogo()) :?>
        <img src="<?php echo thumb_url($channel->getLogo(), 45, 45)?>" alt="<?php echo $channel->getName()?>"/>
        <?php endif;?>
        <?php echo $channel->getName()?>
    </div>
    <?php foreach($channel_programs[$key] as $program ) :?>
    <div class="row">
        <div class="txt2"><?php echo $program->getStartTime()->format('m月d日 ') . $program->getWeekChineseName('星期') . $program->getStartTime()->format(' H:i')?></div>
        <div class="txt3"><?php echo $program->getName()?></div>
    </div>
    <?php endforeach;?>
</div>
<?php endforeach;?>
<?php endif;?>

<?php if ($wiki->getScreenshots()): ?>
<div class="tu">
    <?php foreach($wiki->getScreenshots() as $screenshot) :?>
    <img src="<?php echo thumb_url($screenshot, 130, 98)?>" width="130" height="98" />
    <?php endforeach;?>
</div>
<?php endif;?>

<div class="des">
  <?php if ($wiki->getStarring()) {
    foreach ($wiki->getStarring() as $starr) {
        $starrs[] = $starr;
    }
    echo '<p>主演: '.implode(' / ', $starrs).'</p>';
  }?>
  <p><?php echo $wiki->getHtmlCache(300, ESC_RAW)?></p>
</div>

</div>
</div>