<?php use_helper('GetFileUrl')?>
<div class="toptab2" id="toptab">
    <ul>
        <?php if (('today' == $date )) :?>
        <li class="act">今天<span><?php echo date('n月d日', time())?></span></li>
        <?php else :?>
        <li><a href="<?php echo url_for('channel/tag/?date=today')?>">今天<span><?php echo date('n月d日', time())?></span></a></li>
        <?php endif;?>

        <?php if (('tomorrow' == $date )) :?>
        <li class="act">明天<span><?php echo date('n月d日', time()+86400)?></span></li>
        <?php else :?>
        <li><a href="<?php echo url_for('channel/tag/?date=tomorrow')?>">明天<span><?php echo date('n月d日', time()+86400)?></span></a></li>
        <?php endif;?>
        
        <?php if (('day-after-tomorrow' == $date )) :?>
        <li class="act">后天<span><?php echo date('n月d日', time()+172800)?></span></li>
        <?php else :?>
        <li><a href="<?php echo url_for('channel/tag/?date=day-after-tomorrow')?>">后天<span><?php echo date('n月d日', time()+172800)?></span></a></li>
        <?php endif;?>
    </ul>
    <div class="clear"></div>
    <div class="tabcats">
        <a href="<?php echo url_for('channel/tag/?date='.$date.'&tag='.urlencode('电视剧'))?>" <?php echo ('电视剧' == $tag ) ? 'class="act"' : ''?>>电视剧</a>
        <a href="<?php echo url_for('channel/tag/?date='.$date.'&tag='.urlencode('电影'))?>" <?php echo ('电影' == $tag ) ? 'class="act"' : ''?>>电影</a>
        <a href="<?php echo url_for('channel/tag/?date='.$date.'&tag='.urlencode('体育'))?>" <?php echo ('体育' == $tag ) ? 'class="act"' : ''?>>体育</a>
        <a href="<?php echo url_for('channel/tag/?date='.$date.'&tag='.urlencode('娱乐'))?>" <?php echo ('娱乐' == $tag ) ? 'class="act"' : ''?>>娱乐</a>
        <a href="<?php echo url_for('channel/tag/?date='.$date.'&tag='.urlencode('少儿'))?>" <?php echo ('少儿' == $tag ) ? 'class="act"' : ''?>>少儿</a>
        <a href="<?php echo url_for('channel/tag/?date='.$date.'&tag='.urlencode('科教'))?>" <?php echo ('科教' == $tag ) ? 'class="act"' : ''?>>科教</a>
        <a href="<?php echo url_for('channel/tag/?date='.$date.'&tag='.urlencode('财经'))?>" <?php echo ('财经' == $tag ) ? 'class="act"' : ''?>>财经</a>
        <a href="<?php echo url_for('channel/tag/?date='.$date.'&tag='.urlencode('综合'))?>" <?php echo ('综合' == $tag ) ? 'class="act"' : ''?>>综合</a>
    </div>
</div>

<div id="wrapper">
    <div class="row-container epg05" id="scroller">
        <?php if(!is_null($wikiPlays)) :?>
        <?php foreach($wikiPlays as $play) :?>
        <?php
            $wiki = $play->getWiki();
            if(is_null($wiki))  continue;
         ?>
        <div class="row">
            <a href="<?php echo url_for('wiki/show?id='.$play->getWikiId())?>">
            <?php if($wiki->getCover()) {?>
            <div class="fl"><img src="<?php echo thumb_url($wiki->getCover(), 68, 100)?>" width="68" height="100"/></div>
            <?php }?>
            <div class="fr">
                <p class="txt1"><?php echo $wiki->getTitle()?></p>
                <p class="txt2"><?php echo $wiki->getHtmlCache(80, ESC_RAW); ?></p>
            </div>
            <div class="clear"></div>
        </a>
        </div>
       <?php endforeach;?>
       <?php endif;?>
    </div>
</div>