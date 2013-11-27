      <div id="content">
        <div class="content_inner">
            <?php //include_partial("toobal") ?>
            <?php //include_partial('global/flashes') ?>
            <?php //include_partial('weeks'); ?>
            <div class="table_nav">
            <?php //include_partial('search',array( 'channel'=>$channel,'start_time'=>$start_time,'end_time'=>$end_time));?>
            <?php //include_partial("list",array("pager"=>$pager,'channel'=>$channel,'start_time'=>$start_time,'end_time'=>$end_time)); ?>
            
            
            
            <div style="float:left;width:50%" >
            <h3>本地:</h3>
            <table cellspacing="0" class="adminlist" id="admin_list">
              <thead>
                <tr>
                  <th scope="col"  name="name" style="width: 30em">名称</th>
                  <th scope="col"  name="channel_id">频道</th>
                  <th scope="col"  name="time" >播放时间</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col">名称</th>
                  <th scope="col">频道</th>
                  <th scope="col">播放时间</th>
                </tr>
              </tfoot>
              <tbody id="cctv">
              <?php foreach($programs as $program):?>
                <tr>
                  <td scope="col"><?php echo $program->getName() ?></th>
                  <td scope="col"><?php echo $channel->getName() ?></th>
                  <td scope="col"><?php echo $program->getTime() ?></th>
                </tr>
              <?php endforeach;?>  
              </tbody>
            </table>           
            </div>
            
            
            
            
            <div style="float:right;width:50%" >
            <h3>tvsou:</h3>
            <table cellspacing="0" class="adminlist" id="admin_list">
              <thead>
                <tr>
                  <th scope="col"  name="name" style="width: 30em">名称</th>
                  <th scope="col"  name="channel_id">频道</th>
                  <th scope="col"  name="time" >播放时间</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col">名称</th>
                  <th scope="col">频道</th>
                  <th scope="col">播放时间</th>
                </tr>
              </tfoot>
              <tbody id="tv">
              <?php foreach($xml->C as $program):?>
                <tr>
                  <td scope="col"><?php echo $program->pn; ?></th>
                  <td scope="col"><?php echo $channel->getName() ?></th>
                  <td scope="col"><?php echo date('H:i',strtotime($program->pt)); ?></th>
                </tr>
              <?php endforeach;?> 
              </tbody>
            </table>            
            </div>
        </div>
      
      