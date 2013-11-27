<?php foreach ($program as $programs):?>
    <?php $channel_logo= $programs['channel_logo']; // thumb_url($cover, 216, 320);
    $img= sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s',  108, 160, $channel_logo);
    ?>
<li class="tip"  title="<?php echo "当前播放：".$programs['name']."<br/>即将播放：".$programs['next_name']; ?>"><a href="<?php  echo url_for('/default/ProgramList').'?channel_code='.$programs['channel_code'];?>"><img src="<?php echo  $img;?>" alt=""/><?php echo $programs['channelname'];?></a></li>

<?php endforeach;?>