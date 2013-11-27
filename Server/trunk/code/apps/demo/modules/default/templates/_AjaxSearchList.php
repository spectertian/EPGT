<?php foreach ($searchlist as $programs):?>

<?php  $img= sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s',  216, 320, $programs['cover']);?>


<li><a href="<?php   echo url_for('/default/show').'?wiki_id='.$programs['wiki_id'];?>"><img src="<?php echo $img;?>" alt="" class="tp" title="<?php echo "频道名称：".$programs['channel_name']."<br/>播放出时间：".$programs['start_time']; ?>" /><?php echo $programs['name'];?></a></li>



<?php endforeach;?>