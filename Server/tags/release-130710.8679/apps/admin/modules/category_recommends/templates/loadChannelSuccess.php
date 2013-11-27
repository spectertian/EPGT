<ul>
    <?php if(!$channels):?>
    <li rel="0">暂无匹配数据</li>
    <?php else: ?>
        <?php foreach($channels as $channel): ?>
            <li rel = '{"id":"<?php echo $channel->getCode() ?>","title":"<?php echo $channel->getName() ?>","img":"<?php echo $channel->getLogo( ); ?>","img":"<?php echo file_url($channel->getLogo( )); ?>}' ><?php echo $channel->getName() ?></li>
        <?php endforeach; ?>
    <?php endif ?>
</ul>
