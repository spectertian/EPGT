<script type="text/javascript">
$(document).ready(function() {
// $('.tip').powerTip({ placement: 'nw-alt' });
  // $('.tips').powerTip({ placement: 'n' });
});
</script>
 <div class="play_now clr">

             <?php include_partial("mytv") ?>
            
            <ol>
 
 
          <?php foreach ($program_now as $program):?>

          <?php
          $img= sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s',  216, 320, $program['cover']); 
          $id=$program['wikiid'];
           ?>

            <li  ><a href="<?php   echo url_for('/default/show').'?wiki_id='.$id;?>"><img src="<?php  echo $img;?>"   class="tip"/><?php echo  $program['name'];?></a></li>


          <?php endforeach;?>
            </ol>
        </div>