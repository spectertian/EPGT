<?php slot("HeaderScript"); ?>
<script type="text/javascript">
// 下拉列表跳转
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
</script>
<?php end_slot(); ?>
<div class="toptab1" id="toptab">
<ul>
<?php if($current_navi == 'favorites'): ?>
<li <?php if($current_navi == 'favorites'): ?>class="act"<?php endif; ?>>收藏</li>
<?php else:?>
<li><a href="#" onclick="javascript:jsi.loadFavoritePage('<?php echo url_for('channel/index?code=favorites', true)?>'); return false;" >收藏</a></li>
<?php endif?>

<?php if($current_navi == 'customize'): ?>
<li class="act"><?php echo $customize_province; ?></li>
<?php else: ?>
<li><a href="<?php echo url_for('channel/index?code=' . md5($customize_province)); ?>"><?php echo $customize_province; ?></a></li>
<?php endif; ?>

<?php if($current_navi == 'cctv'): ?>
<li class="act">央视</li>
<?php else: ?>
<li><a href="<?php echo url_for('channel/index?code=cctv'); ?>">央视</a></li>
<?php endif; ?>

<?php if($current_navi == 'tv'): ?>
<li class="act">卫视</li>
<?php else: ?>
<li><a href="<?php echo url_for('channel/index?code=tv'); ?>">卫视</a></li>
<?php endif; ?>
</ul>
<a class="btn-r" href="#" onclick="javascript:window.epg.test();">其他地区</a>
<div class="qtdq">
  <form name="form" id="form">
    <select name="jumpMenu" id="jumpMenu" onChange="MM_jumpMenu('parent',this,0)" style="">
    <?php foreach($all_province as $pinyin => $name): ?>
      <option value="<?php echo url_for('channel/other?province=' . $pinyin) ?>" ><?php echo $name; ?></option>
    <?php endforeach;  ?>
    </select>
  </form>
</div>
<div class="clear"></div>
</div>
<div id="wrapper">
    <div class="row-container epg01" id="scroller">
    <?php if (!is_null($channels)) :?>
    <?php foreach($channels as $channel): ?>
    <div class="row">
        <a href="<?php echo url_for('channel/show?code=' . $channel->getCode()); ?>">
            <div class="fl"><img src="<?php echo thumb_url($channel->getLogo(), 100, 75)?>" width="100" height="75"/></div>
            <?php $now_program = $channel->getNowPrograms(3);?>
            <div class="fr">
                <p class="txt1"><?php echo $channel->getName(); ?></p>
                <p class="txt2">
                    <?php if($now_program[0]): ?>
                    正在播放：<?php echo $now_program[0]->getName(); ?>
                    <?php endif; ?>
                </p>
                <p class="txt2">
                    <?php if($now_program[1] || $now_program[2]): ?>
                    即将播出：<?php echo $now_program[1]->getName(); ?><?php if($now_program[2]): ?>，<?php echo $now_program[2]->getName(); ?><?php endif; ?>
                    <?php endif; ?>
                </p>
            </div>
            <div class="clear"></div>
        </a>
    </div>
    <?php endforeach; ?>
    <?php endif;?>
    </div>
</div>