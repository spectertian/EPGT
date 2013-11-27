<ul>
    <?php if(!$ads):?>
    <li rel="0">暂无匹配数据</li>
    <?php else: ?>
        <?php foreach($ads as $ad): ?>
            <li rel = '{"id":"<?php echo $ad->getId() ?>","title":"<?php echo $ad->getName() ?>","img":"<?php echo $ad->getImage( ); ?>","imgurl":"<?php echo file_url($ad->getImage( )); ?>","url":"<?php echo $ad->getUrl(); ?>"}' ><?php echo $ad->getName() ?></li>
        <?php endforeach; ?>
    <?php endif ?>
</ul>
