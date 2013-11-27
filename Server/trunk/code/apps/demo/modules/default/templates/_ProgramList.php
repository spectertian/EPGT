<?php $time_now=strtotime(date("Y-m-d H:i:s",time()));?>
<?php foreach ($programLists as $program):?>
 <li class="mvcover">
  <a href='javascript:void(0)' id="program" >
  
        <span class="time"><?php echo $program->time;?></span>
        <?php  $wikiid= $program->getWikiId(); ?>
        <input type="hidden" name="" id="wikiid" value="<?php echo $wikiid;?>"> 
        <!-- <span class="name"><a href="<?php echo url_for('/default/show').'?wiki_id='.$wikiid ;?>" target="_blank"> <?php echo $program->name;?></a></span> -->
          <span class="name"><?php echo $program->name;?></span>
        <?php
         if($program->getEndTime()){
         $EndTime=strtotime(date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp()));
         $StartTime=strtotime(date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp()));
          }
           ?>
        <?php if($time_now>= $StartTime && $time_now < $EndTime){?>
             <span class="ctrl">正在播放中...</span>  
        <?php }else{?>
             <span class="ctrl">回看</span>
        <?php }?>
 </a>
</li>
<?php endforeach;?>