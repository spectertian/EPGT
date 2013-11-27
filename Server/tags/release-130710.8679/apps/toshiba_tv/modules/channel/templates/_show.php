<?php if ( $pager->haveToPaginate() ): ?>
<ul totalPages="<?php echo $pager->getLastPage(); ?>" date="<?php echo ($firstday = $pager->getObjectByCursor($onePrePage/*5*/)->getDate()); ?>" dayTitle="<?php echo date('m月d日', strtotime($firstday)); ?>">
<?php foreach ( $pager->getResults() as $k => $program ): ?>
    <?php if ($pager->getPage() == 1 && $current_program && $k == 0): ?>
    <li class="on-air"><span class="time"><?php echo substr($program->getTime(), 0, 5); ?></span> <?php echo htmlspecialchars_decode(mb_substr($program->getName(), 0, 13, 'utf-8')); ?></li>
    <?php else: ?>
    <li><span class="time"><?php echo substr($program->getTime(), 0, 5); ?></span> <?php echo htmlspecialchars_decode(mb_substr($program->getName(), 0, 13, 'utf-8')); ?></li>
    <?php endif; ?>
<?php endforeach; ?>
</ul>
<?php else: ?>
<ul totalPages="0"></ul>
<?php endif; ?>

