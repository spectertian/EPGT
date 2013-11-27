<ul>
    <?php if(!$channels):?>
    <li rel="0">暂无匹配数据</li>
    <?php else: ?>
        <?php foreach($channels as $channel): ?>
            <li rel = '{"code":"<?php echo $channel->getCode() ?>","name":"<?php echo $channel->getName() ?>"}' ><?php echo $channel->getName()?></li>
        <?php endforeach; ?>
    <?php endif ?>
</ul>
