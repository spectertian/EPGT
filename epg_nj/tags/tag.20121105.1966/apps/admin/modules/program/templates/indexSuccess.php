      <div id="content">
        <div class="content_inner">
            <?php include_partial("toobal") ?>
            <?php include_partial('global/flashes') ?>
            <?php include_partial('weeks'); ?>
            <div class="table_nav">
            <?php include_partial('search',array( 'topTvStations'=>$parentTvStations,'channels'=>$channels,'channel_code'=>$channel_code,'update'=>$update ,'updatetime'=>$updatetime));?>
            <?php include_partial("list",array("programs"=>$programs,'channel'=>$channel)); ?>
        </div>
      </div>
      