<ul rel='{"total":<?php echo $total; ?>,"page": <?php echo $page; ?>}'>
    <?php if (isset($programs) && $programs->count()): ?>
    <?php foreach ($programs as $k => $program): ?>
            <li class="action" rel='{"program_id": <?php echo $program->getId(); ?>, "wiki_id": <?php echo $program->getWikiId() + 0; ?>}'>
                <div>
                    <span class="timeline">
                <?php if ($program->getDate() == date('Y-m-d')): ?>
                <?php echo substr($program->getTime(), 0, 5); ?>
                <?php else: ?>
                <?php echo str_replace('-', '/', substr($program->getDate(), 5)); ?>
                <?php endif; ?>
                    </span>
                    <span class="title"><?php echo htmlspecialchars_decode(mb_substr($program->getName(), 0, 20, 'utf-8')); ?></span>
                    <span class="cat"><?php echo $program->getChannel()->getName(); ?></span>
                </div>
            </li>
    <?php endforeach; ?>
    <?php endif; ?>
</ul>