<div class="main">
	<ul class="tv_choice clr">
        <?php 
        foreach($channels as $channel):
        ?>
    	<li><a href="#"><img src="<?php echo thumb_url($channel->getLogo(),45,45)?>" alt=""/><?php echo $channel->getName()?></a></li>
        <?php 
        endforeach;
        ?>
    </ul>
</div>