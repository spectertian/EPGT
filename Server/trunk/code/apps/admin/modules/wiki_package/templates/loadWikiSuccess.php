<ul>
    <?php if(!$wikis):?>
    <li rel="0">暂无匹配数据</li>
    <?php else: ?>
        <?php foreach($wikis as $wiki): ?>
            <li rel = '{"id":"<?php echo $wiki->getId() ?>","title":"<?php echo $wiki->getTitle() ?>","tags":"<?php echo $wiki->getTags(","); ?>"}' ><?php echo $wiki->getTitle()."|".$wiki->getDisplayName() ?></li>
        <?php endforeach; ?>
    <?php endif ?>
</ul>
