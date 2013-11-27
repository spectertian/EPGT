<ul>
    <?php if(!$short_movie_packages):?>
    <li rel="0">暂无匹配数据</li>
    <?php else: ?>
        <?php foreach($short_movie_packages as $short_movie_package): ?>
            <li rel = '{"id":"<?php echo $short_movie_package->getId() ?>","title":"<?php echo $short_movie_package->getName() ?>","img":"<?php echo $short_movie_package->getCover( ); ?>","imgurl":"<?php echo file_url($short_movie_package->getCover( )); ?>"}' ><?php echo $short_movie_package->getName() ?></li>
        <?php endforeach; ?>
    <?php endif ?>
</ul>
