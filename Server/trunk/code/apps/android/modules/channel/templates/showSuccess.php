<?php use_helper('GetFileUrl')?>
<div id="toptab">
    <a class="btn-l" href="<?php echo ($sf_user->getAttribute('showback')) ? $sf_user->getAttribute('showback') : url_for('channel/index')?>">返回</a>
    <a class="btn-f" onclick="javascript:addFavorite('<?php echo $channel->getCode()?>'); return false;" id="add-favorite" style="display:none"><img src="<?php echo image_path('fav1_b.png')?>"/>收藏</a>
    <a class="btn-f" onclick="javascript:delFavorite('<?php echo $channel->getCode()?>'); return false;" id="del-favorite" style="display:none"><img src="<?php echo image_path('fav1_a.png')?>"/>收藏</a>
    <div class="ac">
        <?php if ($channel->getLogo()) :?>
        <img src="<?php echo thumb_url($channel->getLogo(),100,75)?>" width="100" height="75" alt="<?php echo $channel->getName()?>"/>
        <?php endif;?>
        <?php echo $channel->getName()?>
    </div>
   <div class="clear"></div>
   <div class="tabtime">
            <a href="<?php echo url_for('channel/show/?code='.$code.'&time='.($time-86400))?>" class="fl">&lt;前一天</a>
            <a href="<?php echo url_for('channel/show/?code='.$code.'&time='.($time+86400))?>" class="fr">后一天&gt;</a>
            <div class="ac"><?php echo date('Y年m月d日', $time)?></div>
            <div class="clear"></div>
    </div>
</div>
    
<div id="wrapper">
<div class="row-container epg02" id="scroller">
    <?php if (!is_null($programs)) :?>
    <?php foreach($programs as $program) :?>
    <?php $wiki = $program->getWiki()?>
    <div class="row <?php echo ('playing' ==$program->getPlayStatus()) ? 'onair': ''?>">
        <?php if (is_null($wiki)) :?>
    	<div class="fl"><p class="txt2"><?php echo $program->getTime()?></p></div>
        <div class="fr"><p class="txt1"><?php echo $program->getName()?></p></div>
        <div class="clear"></div>
        <?php else: ?>
        <a href="<?php echo url_for('wiki/show?id='. $wiki->getId())?>">
    	<div class="fl"><p class="txt2"><?php echo $program->getTime()?></p></div>
        <div class="fr"><p class="txt1"><?php echo $program->getName()?></p></div>
        <div class="clear"></div>
        </a>
        <?php endif;?>
    </div>
    <?php endforeach;?>
    <?php endif;?>
</div>
</div>
<script type="text/javascript">
if (jsi.isFavoriteExist("<?php echo $channel->getCode()?>")) {
    document.getElementById('del-favorite').style.display="block";
}else{
    document.getElementById('add-favorite').style.display="block";
}

function addFavorite(code){
    if (jsi.addFavorite(code)){
       document.getElementById('add-favorite').style.display="none";
       document.getElementById('del-favorite').style.display="block";
       return true;
    } else {
        return false;
    }
}

function delFavorite(code) {
    if (jsi.delFavorite(code)){
        document.getElementById('add-favorite').style.display="block";
        document.getElementById('del-favorite').style.display="none";
        return true;
    } else {
        return false;
    }
}
</script>