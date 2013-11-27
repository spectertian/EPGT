<?php use_helper('Date');?>
<div id="content">
        <div class="content_inner">
            <header class="toolbar">
              <h2 class="content">待重新抓取频道</h2>
            </header>
            <?php include_partial('global/flashes') ?>
            <form action="#" id="adminForm" name="adminForm" method="post" >
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" style="width: 50%;">频道名称</th>
                  <th scope="col" style="width: 30%;">更新时间</th>
                  <th scope="col" style="width: 20%;text-align: center;">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col">频道名称</th>
                  <th scope="col">更新时间</th>
                  <th scope="col" style="text-align: center;">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php foreach ($channel_list as $channel):?>
                <tr>
                  <td><?php echo $channel['name']?></td>
                  <td><?php echo $channel['tvsouupdate']?></td>
                  <td style="text-align: center;"><a href="<?php echo url_for('program/tvsou?channel_code='.$channel['code']) ?>" class="recommend" target="_blank">点击查看更新</a> | <a href="<?php echo url_for('program/tvsouGetNo?channel_code='.$channel['code']) ?>" class="recommend">取消抓取</a> | <a href="<?php echo url_for('program/index?channel_id='.$channel['id'].'&date='.date('Y-m-d')) ?>" class="recommend">返回节目表</a></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>    
            </form>         
            <div class="clear"></div>
          
        </div>
      </div>
<script type="text/javascript">
    $(document).ready(function () {
       setInterval("reload()",120000);
    });
    function reload()
    {
       location.href='<?php echo url_for("program/channelGet") ?>';
       //window.location.reload();
    }
</script>      