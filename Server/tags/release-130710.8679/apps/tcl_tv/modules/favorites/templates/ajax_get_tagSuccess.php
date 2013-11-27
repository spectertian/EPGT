<ul>
<?php if (count($tags)): ?>
    <?php foreach($tags as $tag): ?>
    <li class="action"><span><?php echo $tag ?></span></li>
    <?php endforeach; ?>
<?php endif; ?>
</ul>