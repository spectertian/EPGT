<?php if($model=='teleplay') : //电视剧?>
    <?php if ($PlayList = $wiki->getPlayList()) :?>
        <?php foreach($PlayList as $playlist) :?>
    	<li class="t">
            <ol>
                <?php $videos = $playlist->getVideos()?>
                <?php $j = 0 ?>
                <?php foreach($videos as $video) : $j++ ?>
            	<li><a href="<?php echo $video->getUrl()?>" target="_blank"  title="<?php echo $video->getTitle()?>">第<?php echo $video->getMark()?>集</a></li>
                <?php endforeach;?>
            </ol>
        </li>
        <?php endforeach;?>
    <?php else: ?>
    <li class="t"><p align="center">很抱歉，该节目还没有片源！</p></li>
    <?php endif;?>  
<?php else:  //电影，综艺节目?>
    <?php if ($videos = $wiki->getVideos()) :?>
        <?php foreach($videos as $video) :?>
        <li class="t">
            <a href="<?php echo $video->getUrl()?>" title="播放<?php echo $video->getRefererZhcn()?>片源" target="_blank"><?php echo $video->getRefererZhcn()?>视频</a>
        </li>
        <?php endforeach;?>
    <?php else: ?>
        <li class="t"><p align="center">很抱歉，该节目还没有片源！</p></li>
    <?php endif;?>
<?php endif;?>
