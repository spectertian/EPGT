<ul>
    <?php if (isset($programs) && $programs->count()): $current_status = true;
        $current_cursor = false; ?>
    <?php foreach ($programs as $k => $program): ?>
            <li class="action<?php
            if ($current_program) {
                if (strtotime($program->getDate()) < strtotime(date('Y-m-d')) ||
                        (strtotime($program->getDate() . ' ' . $program->getTime()) < strtotime($current_program->getDate() . ' ' . $current_program->getTime())) && $program->getDate() == date('Y-m-d')) {
                    echo ' played';
                } else if ($current_status && $current_program->getId() == $program->getId() && $program->getDate() == date('Y-m-d')) {
                    echo ' playing';
                    $current_status = false;
                    $current_cursor = true;
                }
            }
    ?>" rel='{"program_id": <?php echo $program->getId(); ?>, "wiki_id": <?php echo $program->getWikiId() + 0; ?>}'>
            <div>
                <span class="timeline"><?php echo substr($program->getTime(), 0, 5); ?></span>
                <span class="title"><?php echo htmlspecialchars_decode(mb_substr($program->getName(), 0, 20, 'utf-8')); ?></span>
            <?php if ($current_cursor): $current_cursor = false; ?>
                <span class="on-air">正在直播...</span>
            <?php endif; ?>
                <span class="cat">
                <?php
                if (is_object($tags['key' . $program->getId()])) {
                    echo $tags['key' . $program->getId()]->getName();
                } else {
                    echo '';
                }
                ?>
            </span>
        </div>
    </li>
    <?php endforeach; ?>
    <?php else: ?>
    <li>节目未完成录入</li>
    <?php endif; ?>
</ul>
