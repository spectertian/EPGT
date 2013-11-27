  <div id="content">
    <div class="content_inner">
        <?php include_partial('toolbarList',array("pageTitle"=>$pageTitle))?>
        <?php include_partial('global/flashes') ?>
        <div class="table_nav">
          <div class="clear"></div>
        </div>
        <table cellspacing="0">
          <thead>
            <tr>
              <th scope="col" class="list_id" style="width: 40%;">标题</th>
              <th scope="col" class="list_id" style="width: 10%;">总集数</th>
              <th scope="col" class="list_id" style="width: 10%;">最大集数</th>
              <th scope="col" class="list_id" style="width: 10%;">高清视频数</th>
              <th scope="col" class="list_id" style="width: 10%;">标清视频数</th>
              <th scope="col" class="list_created_at" style="width: 20%;">详细</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th scope="col" class="list_id">标题</th>
              <th scope="col" class="list_id">总集数</th>
              <th scope="col" class="list_id">最大集数</th>
              <th scope="col" class="list_id">高清视频数</th>
              <th scope="col" class="list_id">标清视频数</th>
              <th scope="col" class="list_created_at">详细</th>
            </tr>
          </tfoot>
          <tbody>
          <?php if(count($datas)>0):?>
            <?php foreach ($datas as $rs):?>
            <tr>
              <td><?php echo $rs['title'];?></td>
              <td><?php echo $rs['marks'];?></td>
              <td><?php echo $rs['maxnum'];?></td>
              <td><?php echo $rs['videoHdYs'];?></td>
              <td><?php echo $rs['videoHdNs'];?></td>
              <td><a href="<?php echo url_for('video/show?id='.$rs['id']);?>" target="_blank">详细</a></td>
            </tr>
            <?php endforeach;?>
          <?php else:?>
            <tr><td colspan="6" style="text-align: center;">无错误</td></tr>  
          <?php endif;?>  
          </tbody>
        </table>
        <div class="clear"></div>
    </div>
  </div>
  