<?php foreach ($nowplaying as $program):?>

<?php
$img= sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s',  216, 320, $program['cover']); 
$id=$program['wikiid'];
$next_time=  (strtotime($program['end_time'])- strtotime(date('H:i:s',time())));
$next_time= date('i',$next_time)
?>

<li><a href="<?php   echo url_for('/default/show').'?wiki_id='.$id;?>"><img src="<?php  echo $img;?>"  class="tip" title="<?php echo "节目名称：".$program['name']."<br/>频道名称：".$program['channel']."<br/>播放时间：开始".$program['start_time']." <----> 结束".$program['end_time']."<br/>即将播出：".$program['next_name']." ------剩余时间".$next_time."分钟"; ?>" /><?php echo  $program['name'];?></a></li>
<?php endforeach;?>