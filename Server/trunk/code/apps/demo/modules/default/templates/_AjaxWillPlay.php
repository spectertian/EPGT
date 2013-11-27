<?php foreach ($program_now as $program):?>

<?php
$img= sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s',  216, 320, $program['cover']); 
$id=$program['wikiid'];
?>

<li  ><a href="<?php   echo url_for('/default/show').'?wiki_id='.$id;?>"><img src="<?php  echo $img;?>"   class="tip" title="<?php echo "节目名称：".$program['name']."<br/>频道名称：".$program['channel']."<br/>播放时间：开始".$program['start_time']." <----> 结束".$program['end_time']; ?>"   /><?php echo  $program['name'];?></a></li>
<?php endforeach;?>