<div class="main">
	<div class="tvlists">   	
        <ul class="clr">
            <li>电视台</li>
            <li>正在播出</li>
            <li>即将播出</li>
        </ul>
        <div>
            <ol>
            <?php foreach ($channels as $channel): ?>
                <li><a href="<?php echo url_for('channel/index?channel_code='.$channel->getCode())?>"><span><?php echo $channel->getName() ?></span>
                <span>
                <?php $programs = $channel->getNowPrograms();?>
                <?php if (count($programs) >= 1) :?>
                    <?php echo $programs[0]->getName(ESC_RAW) ?>
                <?php endif;?>
                </span>
                
                <span>
                <?php if (count($programs) > 1) :?>
                    <?php echo date("H:i",strtotime($programs[1]->getTime())); ?> - <?php echo $programs[1]->getEndTime()->format("H:i"); ?>
                    <?php echo $programs[1]->getName(ESC_RAW)?>
                <?php endif;?>
                </span></a></li>
            <?php endforeach;?>
            </ol>
        </div>
	</div>
</div>