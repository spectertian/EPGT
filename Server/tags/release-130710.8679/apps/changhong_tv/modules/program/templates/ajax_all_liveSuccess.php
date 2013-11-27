<ul>
    <?php if (isset($programs) && count($programs)): ?>
    <?php foreach ($programs as $k => $program): ?>
    <li class="action playing" rel='{"program_id": <?php echo $program->getId(); ?>, "wiki_id": <?php echo $program->getWikiId() + 0; ?>}'>
                <div>
                    <span class="timeline"><?php echo substr($program->getTime(), 0, 5); ?></span>
                    <span class="title"><?php echo htmlspecialchars_decode(mb_substr($program->getName(), 0, 20, 'utf-8')); ?></span>
                    <span class="cat"><?php echo $program->getChannel()->getName(); ?></span>
                </div>

            </li>
    <?php endforeach; ?>
    <?php endif; ?>
</ul>