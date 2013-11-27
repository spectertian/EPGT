<div id="toptab">
<a href="<?php echo url_for('channel/index') ?>" class="btn-l">返回</a>
<div class="tit"><?php echo $province; ?></div>
<div class="clear"></div>
</div>

<div id="wrapper">
    <div class="row-container epg01" id="scroller">
        <?php foreach($channels as $channel): ?>
        <div class="row">
            <a href="<?php echo url_for('channel/show?code=' . $channel->getCode()); ?>">
                <div class="fl">
                    <img src="<?php echo thumb_url($channel->getLogo(),100,75)?>" width="100" height="75" />
                </div>
                <?php
                $now_program = $channel->getNowPrograms(3);
                ?>
                <div class="fr">
                    <p class="txt1"><?php echo $channel->getName(); ?></p>
                    <p class="txt2">
                        <?php if($now_program[0]): ?>
                        正在播放：<?php echo $now_program[0]->getName(); ?>
                        <?php endif; ?>
                    </p>
                    <p class="txt2">
                        <?php if($now_program[1] || $now_program[2]): ?>
                        即将播出：<?php echo $now_program[1]->getName(); ?><?php if($now_program[2]): ?>，<?php echo $now_program[2]->getName(); ?><?php endif; ?>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="clear"></div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>