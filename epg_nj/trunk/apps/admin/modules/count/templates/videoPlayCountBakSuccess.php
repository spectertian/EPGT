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
              <th scope="col" class="list_id" style="width: 30%;">id</th>
              <th scope="col" class="list_id" style="width: 50%;">名称</th>
              <th scope="col" class="list_id" style="width: 20%;">page_id</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th scope="col" class="list_id">id</th>
              <th scope="col" class="list_id">名称</th>
              <th scope="col" class="list_id">page_id</th>
            </tr>
          </tfoot>
          <tbody>
          <?php if(count($datas)>0):?>
            <?php foreach ($datas as $rs):?>
            <tr>
              <td><?php echo $rs['id'];?></td>
              <td><?php echo $rs['title'];?></td>
              <td><?php echo $rs['page_id'];?></td>
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
  