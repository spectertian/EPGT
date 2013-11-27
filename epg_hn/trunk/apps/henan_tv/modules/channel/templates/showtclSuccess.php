<?php if(count($programs)): ?>
<script type="text/javascript">
$(document).ready(function() {
     $('#tv-listings ul').list({
        direction: 'V',
        viewRows: 10,
        enabledScroll: true,
        scrollIndexs: [1, 8],
        enter: function(event, item){
            
        },
        over: function(event, pos) {
            if (pos == 'start') {
                $('#dates').data('ui').focus();
            }
        },
        menu: function(event, ui){
             $('#dates').data('ui').focus();
        }
      });
});
</script>
<?php endif; ?>
<ul>
<?php foreach ($programs as $k => $program): ?>
    <li wikiid="<?php echo $program->getWikiId(); ?>" class="action
    <?php if ($current_program && $current_program->getId() == $program->getId()) ' actived'; ?>
    <?php echo ($playing_time > strtotime($program->getFulltime())) ? " played" : ""; ?>
    <?php if ($current_program && $current_program->getId() == $program->getId()){ echo ' on-air'; }?>">
        <span class="time"><?php echo substr($program->getTime(), 0, 5); ?></span>
        <span class="program-title"><?php echo $program->getName(); ?><?php if ($current_program && $current_program->getId() == $program->getId()){ echo '(正在播放)'; }?></span>
        <span class="column">
            <?php
                if (is_object($tags['key' . $program->getId()])) {
                    echo $tags['key' . $program->getId()]->getName();
                } else {
                    echo '';
                }
                ?>
        </span>
    </li>
<?php endforeach; ?>
</ul>