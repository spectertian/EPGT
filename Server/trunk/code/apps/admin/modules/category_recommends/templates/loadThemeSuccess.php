<ul>
    <?php if(!$themes):?>
    <li rel="0">暂无匹配数据</li>
    <?php else: ?>
        <?php foreach($themes as $theme): ?>
            <li rel = '{"id":"<?php echo $theme->getId() ?>","title":"<?php echo $theme->getTitle() ?>","img":"<?php echo $theme->getImg( ); ?>","imgurl":"<?php echo file_url($theme->getImg( )); ?>"}' ><?php echo $theme->getTitle() ?></li>
        <?php endforeach; ?>
    <?php endif ?>
</ul>
