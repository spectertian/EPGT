<ul>
    <?php if(!$wikis):?>
    <li rel="0">暂无匹配数据</li>
    <?php else: ?>
        <?php foreach($wikis as $wiki): ?>
            <li rel = '{"id":"<?php echo $wiki->getId() ?>","title":"<?php echo $wiki->getTitle() ?>","img":"<?php echo $wiki->getCover( ); ?>","imgurl":"<?php echo file_url($wiki->getCover()) ?>"}' ><?php echo $wiki->getTitle() ?></li>
        <?php endforeach; ?>
    <?php endif ?>
</ul>
