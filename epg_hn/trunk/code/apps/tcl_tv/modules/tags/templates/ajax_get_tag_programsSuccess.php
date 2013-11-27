<ul>
    <?php if(isset($programs) && $programs->count()): ?>
    <?php foreach ($programs as $program): ?>
        <li class="action
        <?php if (strtotime($program->getDate() . ' ' . $program->getTime()) <= strtotime(date('Y-m-d H:i:s'))): ?>played<?php endif; ?>"
            rel='{"program_id": <?php echo $program->getId(); ?>, "wiki_id": <?php echo $program->getWikiId() + 0; ?>}'>
            <div>
                <span class="timeline"><?php echo substr($program->getTime(), 0, 5); ?></span>
                <span class="title"><?php echo htmlspecialchars_decode(mb_substr($program->getName(), 0, 20, 'utf-8')); ?></span>
                <span class="cat"><?php echo $program->getChannelName() ?></span>
            </div>    
        </li>
    <?php endforeach; ?>
    <?php else: ?>
        <li>节目未完成录入</li>
    <?php endif; ?>
</ul>