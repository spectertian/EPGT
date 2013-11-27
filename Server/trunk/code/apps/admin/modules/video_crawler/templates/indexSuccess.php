      <div id="content">
        <div class="content_inner">
            <?php include_partial("toobal") ?>
            <?php include_partial('global/flashes') ?>
            <?php //include_partial('weeks'); ?>
            <div class="table_nav">
            <?php include_partial('search',array( 'wiki'=>$wiki,'field'=>$field,'text'=>$text,'model'=>$model,'state'=>$state));?>
            <?php include_partial("list",array("vcs"=>$vcs, 'wiki'=>$wiki,'field'=>$field,'text'=>$text,'model'=>$model,'state'=>$state)); ?>
           
        </div>
        <div style='padding:0 0 35px 0'><?php include_partial("foottoobal") ?></div>
      </div>
       
      