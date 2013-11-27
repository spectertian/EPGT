<ul>
<?php if (isset ($channels) && $channels->count()): ?>
<?php foreach ($channels as $k => $channel): ?>
    <li class="action" id="channel_id_<?php echo $channel->getId(); ?>">
        <span><?php echo $channel->getName(); ?></span>
    </li>
<?php endforeach; ?>
<?php endif; ?>
</ul>