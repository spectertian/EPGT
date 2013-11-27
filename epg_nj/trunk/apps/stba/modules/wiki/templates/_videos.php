<?php 
$totalVideos = count($videos);
$j = 1;
foreach($videos as $video):
    $asset_id=$video->getPageId();
    if($model=='teleplay'):
        $k=intval($j/30);
        if($j%30==0&&$totalVideos>$k*30):
?>
        <li class="videohs <?php echo ceil($j/10); ?>" id="<?php echo $j; ?>" value="<?php echo ceil($j/30); ?>"><a href="javascript:void(0);"  onclick="more(<?php echo $k*30;?>,<?php echo $k;?>);return false;">更多</a></li>
        <?php $j++;?>   
        <li class="videohs <?php echo ceil($j/10); ?>" id="<?php echo $j; ?>" value="<?php echo ceil($j/30); ?>"><a href="javascript:void(0);"  onclick="playWikiVideo('<?php echo $asset_id;?>','<?php echo $video->getReferer()?>');return false;"><?php echo $video->getMark()?$video->getMark():$j-1;?></a></li>   
        <?php else:?>
        <li class="videohs <?php echo ceil($j/10); ?>" id="<?php echo $j; ?>" value="<?php echo ceil($j/30); ?>"><a href="javascript:void(0);"  onclick="playWikiVideo('<?php echo $asset_id;?>','<?php echo $video->getReferer()?>');"><?php echo $video->getMark()?$video->getMark():$j;?></a></li>
        <?php endif;?>
<?php 
    else:
        $title=$video->getTitle();
?>
	<li class="videohs <?php echo ceil($j/2); ?>" id="<?php echo $j; ?>" value="<?php echo ceil($j/6); ?>"><a href="#"  onclick="playWikiVideo('<?php echo $asset_id;?>','<?php echo $video->getReferer()?>');"><span><big><?php echo $title;?></big></span></a></li>
<?php 
    endif;
    $j++;
endforeach;
?>