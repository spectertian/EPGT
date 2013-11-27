<?php if($programs) : ?>
    <?php foreach($programs as $program): ?>
    <li><a href="#"><?php echo mb_substr($program->getName(), 0, 11, 'utf-8') ?></a><span><?php echo $program->getStartTime()->format('H:i'); ?></span><a href="#"><?php echo mb_substr($program->getChannelName(), 0, 6, 'utf-8') ?></a></li>
    <?php endforeach; ?>
<?php else: ?>
    <li><p align="center">该节目暂无直播信息！</p></li> 
<?php endif;?>