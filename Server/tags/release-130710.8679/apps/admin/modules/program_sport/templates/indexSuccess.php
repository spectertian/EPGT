      <div id="content">
        <div class="content_inner">
            <?php include_partial("toobal") ?>
            <?php include_partial('global/flashes') ?>
            <?php include_partial('weeks'); ?>
            <div class="table_nav">
            <?php include_partial("list",array("pager"=>$pager,'date'=>$date)); ?>
           
        </div>
        <div style='padding:0 0 35px 0'><?php include_partial("foottoobal") ?></div>
      </div>
       
      