<?php 
      $playlist=$wiki->getPlayList($refer);
      if($playlist):
          $videos = $playlist[0]->getVideos();
    	  $totalVideos = count($videos);
    	  $j = 1;
    	  foreach($videos as $video):
              if($model=='teleplay'):
?>
		<li class="videohs <?php echo ceil($j/10); ?>" id="<?php echo $j; ?>" value="<?php echo ceil($j/30); ?>"><a href="#"  onclick="tclSave('<?php echo (string)$wiki->getId();?>','<?php echo $video->getUrl()."&backurl=http://http://172.31.139.17"?>');"><?php echo $video->getMark()?$video->getMark():$j;?></a></li>
<?php         else:
                  $title=$video->getTitle();
?>
		<li class="videohs <?php echo ceil($j/2); ?>" id="<?php echo $j; ?>" value="<?php echo ceil($j/6); ?>"><a href="#"  onclick="tclSave('<?php echo (string)$wiki->getId();?>','<?php echo $video->getUrl()."&backurl=http://http://172.31.139.17"?>');"><span><big><?php echo $title;?></big></span></a></li>
<?php         endif;?>
<?php
    	  $j++;
    	  endforeach;
      endif;
?>