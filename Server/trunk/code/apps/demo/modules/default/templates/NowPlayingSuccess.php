<script type="text/javascript">
$(document).ready(function() {
 $('.tip').powerTip({ placement: 'nw-alt' });
  // $('.tips').powerTip({ placement: 'n' });
});
</script>
  <div class="play_now clr">
        	<ul class="item_list">

            	<li <?php if($_GET['Channel_type'] == 'cctv'):?> class="this" <?php endif;?> ><a href="<?php echo url_for('/default/NowPlaying').'?Channel_type=cctv';?>">中央频道</a></li>
                <li <?php if($_GET['Channel_type'] == 'tv'):?> class="this" <?php endif;?> ><a href="<?php  echo url_for('/default/NowPlaying').'?Channel_type=tv';?>">卫视频道</a></li>
                <li <?php if($_GET['Channel_type'] == 'local'):?> class="this" <?php endif;?>><a href="<?php  echo url_for('/default/NowPlaying').'?Channel_type=local';?>">本地频道</a></li>
                <li <?php if($_GET['Channel_type'] == 'hd'):?> class="this" <?php endif;?>><a href="<?php  echo url_for('/default/NowPlaying').'?Channel_type=hd';?>">高清频道</a></li>
                <li <?php if($_GET['Channel_type'] == 'pay'):?> class="this" <?php endif;?> ><a href="<?php  echo url_for('/default/NowPlaying').'?Channel_type=pay';?>">付费频道</a></li>
                <li <?php if($_GET['Channel_type'] == ''):?> class="this" <?php endif;?> ><a href="<?php  echo url_for('/default/NowPlaying');?>">全部频道</a></li>
            </ul>
            
            <ol>
 


 
          <?php foreach ($nowplaying as $program):?>
 
           <?php
          $img= sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s',  216, 320, $program['cover']); 
          $id=$program['wikiid'];
           ?>

            <li><a href="<?php   echo url_for('/default/show').'?wiki_id='.$id;?>"><img src="<?php  echo $img;?>"  class="tip" title="<?php echo "节目名称：".$program['name']."<br/>频道名称：".$program['channel']."<br/>播放时间：开始".$program['start_time']." <----> 结束".$program['end_time']; ?>" style="width: 150px; height: 215px;"/><?php echo  $program['name'];?></a></li>
          <?php endforeach;?>


                



            </ol>
        </div>
        </div>