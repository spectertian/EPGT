<?php if(count($programs)): ?>
<script type="text/javascript">
     $('#tv-listings ul').list({
        direction: 'V',
        viewRows: 10,
        enabledScroll: true,
        scrollIndexs: [1, 8],
        enter: function(event, item){

        },
        menu: function(event, ui){
             $('#channel-slider').data('ui').focus();
        }
      });
</script>
<?php endif; ?>
<ul>
<?php if($programs): ?>
    <?php foreach ($programs as $program): ?>
        <li class="action
        <?php
            if($program->getPlayStatus() == 'playing') {
                echo 'on-air';
            } elseif($program->getPlayStatus() == 'played') {
                echo 'played';
            }
        ?>
        ">
            <span class="time"><?php echo $program->getTime() ?></span>
            <span class="program-title">
                <?php echo $program->getName(); ?>
                <?php if($program->getPlayStatus() == 'playing') echo '(正在播放)'; ?>
            </span>
            <span class="column"><?php echo $program->getFirstTag() ?></span>
        </li>
    <?php endforeach; ?>
<?php else: ?>
    暂无节目
<?php endif; ?>

</ul>