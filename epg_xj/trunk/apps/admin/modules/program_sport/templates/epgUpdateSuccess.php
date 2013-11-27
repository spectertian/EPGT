<?php use_helper('Date');?>
<div id="content">
        <div class="content_inner">
            <header class="toolbar">
              <h2 class="content">Epg更新</h2>
            </header>
            <?php include_partial('global/flashes') ?>
            <form action="#" id="adminForm" name="adminForm" method="post" >
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" style="width: 20%;">频道名称</th>
                  <th scope="col" style="width: 15%;">更新时间</th>
                  <th scope="col" style="width: 15%;">编辑确认时间</th>
                  <th scope="col" style="width: 10%;">重新抓取</th>
                  <th scope="col" style="width: 40%;text-align: center;">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col">频道名称</th>
                  <th scope="col">更新时间</th>
                  <th scope="col">编辑确认时间</th>
                  <th scope="col">重新抓取</th>
                  <th scope="col" style="text-align: center;">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php foreach ($channel_list as $channel):?>
                <tr>
                  <td><?php echo $channel['name']?></td>
                  <td><?php echo $channel['epg_update']?></td>
                  <td><?php echo $channel['editor_update']?></td>
                  <td><?php echo $channel['epg_get']?'是':'否'?></td>
                  <td style="text-align: center;">
                  <a href="<?php echo url_for('program_sport/epg?channel_code='.$channel['code']) ?>" class="recommend" target="_blank">查看更新</a> | 
                  <a href="<?php echo url_for('program_sport/epgOk?channel_code='.$channel['code']) ?>" class="recommend">编辑确认</a> | 
                  <a href="<?php echo url_for('program_sport/epgGet?channel_code='.$channel['code']) ?>" class="recommend">重新抓取</a> | 
                  <a href="<?php echo url_for('program_sport/index?channel_id='.$channel['id'].'&channel_code='.$channel['code'].'&date='.date('Y-m-d')) ?>" class="recommend">返回节目表</a>
                  </td>
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
       location.href='<?php echo url_for("program_sport/epgUpdate") ?>';
       //window.location.reload();
    }
</script>      