      <div id="content">
        <div class="content_inner">
            <?php include_partial("toobal") ?>
            <?php include_partial('global/flashes') ?>
            <?php include_partial('weeks',array('type'=>$type)); ?>
            <div class="table_nav">
            <?php include_partial('searchnew',array( 'channel_code'=>$channel_code,'type'=>$type));?>
            <?php include_partial("list",array("programs"=>$programs,'channelname'=>$channelname)); ?>
        </div>
      </div>
      