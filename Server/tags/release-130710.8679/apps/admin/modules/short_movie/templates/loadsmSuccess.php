<ul>
    <?php if(!$wikis):?>
    <li rel="0">暂无匹配数据</li>
    <?php else: ?>
        <?php foreach($wikis as $wiki): ?>
            <li rel = '{"id":"<?php echo $wiki->getId() ?>","name":"<?php echo $wiki->getName() ?>"}' ><?php echo $wiki->getName()?></li>
        <?php endforeach; ?>
    <?php endif ?>
</ul>
