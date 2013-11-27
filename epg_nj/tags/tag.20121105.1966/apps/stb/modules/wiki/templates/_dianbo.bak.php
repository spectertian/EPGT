<?php if($model=='teleplay') : //电视剧?>
    <?php if ($PlayList = $wiki->getPlayList()) :?>
        <?php foreach($PlayList as $playlist) :?>
         <?php if ($playlist->referer == 'qiyi'): ?>  
                <?php $videos = $playlist->getVideos()?>
                <?php $j = 0 ?>
                <?php foreach($videos as $video): $j++ ?>
          	<li><a onclick="tvList(<?php echo $j ?>)" id="pid<?php echo $j ?>" href="#" pid="<?php echo $video->getUrl()?>"  title="<?php echo $video->getTitle()?>">第<?php echo $video->getMark()?>集</a></li>
                <?php endforeach; break;?>
         <?php elseif ($playlist->referer == 'sohu'): ?> 
                <?php $videos = $playlist->getVideos()?>
                <?php $j = 0 ?>
                <?php foreach($videos as $video) : $j++ ?>
             	<li><a onclick="tvList(<?php echo $j ?>)" id="pid<?php echo $j ?>" href="#" pid="<?php echo $video->getUrl()?>"  title="<?php echo $video->getTitle()?>">第<?php echo $video->getMark()?>集</a></li>
                <?php endforeach;  break;?>   
         <?php else: ?>
                <?php $videos = $playlist->getVideos()?>
                <?php $j = 0 ?>
                <?php foreach($videos as $video) : $j++ ?>
            	<li><a onclick="tvList(<?php echo $j ?>)" id="pid<?php echo $j ?>" href="#" pid="<?php echo $video->getUrl()?>"  title="<?php echo $video->getTitle()?>">第<?php echo $video->getMark()?>集</a></li>
                <?php endforeach;  break;?>  
         <?php endif;?>
        <?php endforeach;?>
    <?php else: ?>
        <li>暂无片源！</li>
    <?php endif;?>  
<?php else:  //电影，综艺节目?>
    <?php if ($videos = $wiki->getVideos()) :?>
        <?php foreach($videos as $video) :?>
        <li>
            <a onclick="tvList(1)" id="pid1" href="#" pid="<?php echo $video->getUrl()?>"  title="播放<?php echo $video->getRefererZhcn()?>片源"><?php echo $video->getRefererZhcn() ?></a>
        </li>
        <?php endforeach;?>
    <?php else: ?>
        <li>暂无片源！</li>
    <?php endif;?>
<?php endif;?>
