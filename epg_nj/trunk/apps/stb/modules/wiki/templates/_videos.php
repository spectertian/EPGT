<?php 
      $playlist=$wiki->getPlayList($refer);
      if($playlist):
          $videos = $playlist[0]->getVideos();
    	  $totalVideos = count($videos);
    	  $j = 1;
    	  foreach($videos as $video):
              $config=$video->getConfig();
              $asset_id=$config['asset_id'];
              if($model=='teleplay'):
?>
		<li class="videohs <?php echo ceil($j/10); ?>" id="<?php echo $j; ?>" value="<?php echo ceil($j/30); ?>"><a href="#"  onclick="playWikiVideo('<?php echo $asset_id;?>','<?php echo $video->getReferer()?>');"><?php echo $video->getMark()?$video->getMark():$j;?></a></li>
<?php         else:
                  $title=$video->getTitle();
?>
		<li class="videohs <?php echo ceil($j/2); ?>" id="<?php echo $j; ?>" value="<?php echo ceil($j/6); ?>"><a href="#"  onclick="playWikiVideo('<?php echo $asset_id;?>','<?php echo $video->getReferer()?>');"><span><big><?php echo $title;?></big></span></a></li>
<?php         endif;?>
<?php
    	  $j++;
    	  endforeach;
      endif;
?>