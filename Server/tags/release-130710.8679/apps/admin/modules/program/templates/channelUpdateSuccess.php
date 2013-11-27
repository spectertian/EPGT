<?php use_helper('Date');?>
<div id="content">
        <div class="content_inner">
            <header class="toolbar">
              <h2 class="content">tvsou更新</h2>
              <nav class="utility">
            	<li class="recommended"><a href="<?php echo url_for('program/SetChannelUpdate')?>">设置监控</a></li>
              </nav>
            </header>
            <?php include_partial('global/flashes') ?>
            <form action="#" id="adminForm" name="adminForm" method="post" >
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" style="width: 50%;">频道名称</th>
                  <th scope="col" style="width: 15%;">更新时间</th>
                  <th scope="col" style="width: 15%;">重新抓取</th>
                  <th scope="col" style="width: 20%;text-align: center;">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col">频道名称</th>
                  <th scope="col">更新时间</th>
                  <th scope="col">重新抓取</th>
                  <th scope="col" style="text-align: center;">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php $i = 0;?>
                <?php foreach ($channel_list as $channel):?>
                <tr>
                  <td><?php echo $channel['name']?></td>
                  <td><?php echo $channel['tvsouupdate']?></td>
                  <td><?php echo $channel['tvsouget']?'是':'否'?></td>
                  <td style="text-align: center;">
                  	<a href="javascript:void(0);" onclick="toAction(<?php echo $i.",'show','".$channel['code']."'";?>)" class="recommend" target="_self">点击查看更新</a> | 
                  	<!--  Modify by tianzhongsheng-ex@huan.tv Time 2013-06-06 10:15 tvshou重新抓取的方式改变
                  	<a href="javascript:void(0);" onclick="toAction(<?php echo $i.",'get','".$channel['code']."'";?>)" class="recommend" target="_self">重新抓取</a>
                  	-->
                  	 <a href="<?php echo url_for('program/tvsouGet?channel_code='.$channel['code']) ?>">重新抓取</a> 
                  	 |
                  	<a href="<?php echo url_for('program/tvsouOk?channel_code='.$channel['code']) ?>">确认完毕</a> |
                  	<a href="<?php echo url_for('program/index?channel_id='.$channel['id'].'&date='.date('Y-m-d')) ?>" class="recommend">返回节目表</a></td>
                </tr>
                <?php $i++; endforeach;?>
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
       location.href='<?php echo url_for("program/channelUpdate") ?>';
       //window.location.reload();
    }
	var i = 0;
    function toAction(num,action,channelCode){
        
		if(action == 'show'){
			window.open('/program/tvsou/action?channel_code='+channelCode);
			return false;
		}

//--START-- Modify by tianzhongsheng-ex@huan.tv Time 2013-06-06 10:15 tvshou重新重新抓取的方式改变	
//		if(action == 'get'){
//			if(i > 0) num = num - i;
//			$("tbody tr:eq("+num+")").remove();
//	        i++;
//			$.ajax({
//				type:"post",
//				url: "/program/tvsouGet",
//				data: {channel_code:channelCode},
//				success: function(msg){
//					
//							history.go(0) ;
//							
//						},
//				});
//		}
//--END-- Modify by tianzhongsheng-ex@huan.tv Time 2013-06-06 10:15 tvshou重新重新抓取的方式改变		
    }
</script>      