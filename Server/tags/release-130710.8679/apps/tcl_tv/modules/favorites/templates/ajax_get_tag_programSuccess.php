<ul>
    <?php if (isset($live_programs) && $live_programs && $live_programs->count() || isset($other_programs) && $other_programs && $other_programs->count()): ?>
        <?php if (isset($live_programs) && $live_programs && $live_programs->count()): ?>
        <?php foreach ($live_programs as $k => $live_program): ?>
        <li class="action playing" rel='{"program_id": <?php echo $live_program->getId(); ?>, "wiki_id": <?php echo $live_program->getWikiId() + 0; ?>}'>
                    <div>
                        <span class="timeline"><?php echo substr($live_program->getTime(), 0, 5); ?></span>
                        <span class="title"><?php echo mb_substr($live_program->getName(), 0, 20, 'utf-8'); ?></span>
                        <span class="cat"><?php echo $live_program->getChannel()->getName(); ?></span>
                    </div>

                </li>
        <?php endforeach; ?>
        <?php endif; ?>
        <?php if(isset($other_programs) && $other_programs && count($other_programs)): ?>
            <?php foreach ($other_programs as $k => $other_program): ?>
                <li class="action playing" rel='{"program_id": <?php echo $other_program->getId(); ?>, "wiki_id": <?php echo $other_program->getWikiId() + 0; ?>}'>
                    <div>
                        <span class="timeline"><?php echo substr($other_program->getTime(), 0, 5); ?></span>
                        <span class="title"><?php echo mb_substr($other_program->getName(), 0, 20, 'utf-8'); ?></span>
                        <span class="cat"><?php echo $other_program->getChannel()->getName(); ?></span>
                    </div>

                </li>
        <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</ul>