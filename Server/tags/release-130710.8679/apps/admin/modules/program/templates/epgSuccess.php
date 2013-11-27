<script type="text/javascript">
$(document).ready(function(){
	var del    = $(".sf_admin_list_td_del");
	var name    = $(".sf_admin_list_td_name");
	var time    = $(".sf_admin_list_td_time");
	del.live('click', function() {
			if(confirm('确定删除吗?'))
			{
			    var td = $(this).parent().parent();
	            var id  = $.trim($(this).parent().parent().find('.sf_admin_list_td_id').text());
				$.ajax({
			        url:            "/program/ajax_del?id=" + id ,
			        dataType:       "json",
			        success:function(data)
			        {
			            if(data.code == 1)
			            {
			            	td.remove();
			            	$("#point").text(data.msg);
			            }else{
			                alert(data.msg);
			            }
			            noticeShow(data.msg);
			        },error:function()
			        {
			        }
				});
			}
			return false;
    });	
	name.live('click', function() {
        if($("#name").html() == null )
        {
            var id  = $.trim($(this).parent().find('.sf_admin_list_td_id').text());
            $(this).attr('id','name');
            $("#name").html('<input id="postValue" value="'+ $.trim($("#name").text())+'" onblur="ajax_update(\''+id+'\',\'name\');">');
            $("#postValue").focus();
        }
    });

	time.live('click', function() {
        if($("#time").html() == null )
        {
            var id  = $.trim($(this).parent().find('.sf_admin_list_td_id').text());
            $(this).attr('id','time');
            $("#time").html('<input id="postValue" value="'+ $.trim($("#time").text())+'" onblur="ajax_update(\''+id+'\',\'time\');">');
            $("#postValue").focus();
        }
    });	
	
});

function ajax_update(id ,key)
{
    var value   = $("#postValue").val();

    $.ajax({
        url:            "/program/ajax_update?id=" + id +"&key="+key+"&value="+value,
        dataType:       "json",
        success:function(data)
        {
            if(data.code == 1)
            {
            	$("#point").text(data.msg);
                $("#postValue").parent().html(value);
            }
            else
            {
                alert(data.msg);
            }
            $("#"+key).attr('id', '');
            noticeShow(data.msg);
        },error:function()
        {
        }
    });
}
</script>
      <div id="content">
        <div class="content_inner">
            <?php //include_partial("toobal") ?>
            <?php //include_partial('global/flashes') ?>
            <?php //include_partial('weeks'); ?>
            <div class="table_nav">
            <?php //include_partial('search',array( 'channel'=>$channel,'start_time'=>$start_time,'end_time'=>$end_time));?>
            <?php //include_partial("list",array("pager"=>$pager,'channel'=>$channel,'start_time'=>$start_time,'end_time'=>$end_time)); ?>
            
            
            
            <div style="float:left;width:50%" >
            <h3>本地:(<?php echo date('Y-m-d')?>)<span style="color:green" id="point"></span></h3>
            <table cellspacing="0" class="adminlist" id="admin_list">
              <thead>
                <tr>
                  <th  scope="col"  name="name" style="width:55%">名称</th>
                  <th scope="col"  name="channel_id" style="width:20%">频道</th>
                  <th scope="col"  name="time"  style="width:15%">播放时间</th>
                  <th scope="col"  name="time"  style="width:10%">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col">名称</th>
                  <th scope="col">频道</th>
                  <th scope="col">播放时间</th>
                  <th scope="col">操作</th>
                </tr>
              </tfoot>
              <tbody id="cctv">
              <?php foreach($programs as $program):?>
                <tr>
                  <td style="display:none" class="sf_admin_list_td_id"><?php echo $program->getId()?></td>
                  <td style="cursor:pointer;height:50px;" class="sf_admin_list_td_name" scope="col"><?php echo $program->getName() ?></th>
                  <td scope="col"><?php echo $channel->getName() ?></th>
                  <td style="cursor:pointer;" class="sf_admin_list_td_time" scope="col"><?php echo $program->getTime() ?></th>
                  <td><a href="javascrip://" style="color:red" class="sf_admin_list_td_del">删除</a></td>
                </tr>
              <?php endforeach;?>  
              </tbody>
            </table>           
            </div>
            
            
            
            
            <div style="float:right;width:50%" >
            <h3>Epg:(<?php echo date('Y-m-d')?>)</h3>
            <table cellspacing="0" class="adminlist" id="admin_list">
              <thead>
                <tr>
                  <th scope="col"  name="name" style="width:55%">名称</th>
                  <th scope="col"  name="channel_id" style="width:20%">频道</th>
                  <th scope="col"  name="time"  style="width:25%">播放时间</th>
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
              <?php foreach($programTemps as $program):?>
                <tr>
                  <td scope="col" style="height: 50px; text-align:left"><?php echo $program->getName() ?></th>
                  <td scope="col"><?php echo $channel->getName() ?></th>
                  <td scope="col"><?php echo $program->getTime() ?></th>
                </tr>
              <?php endforeach;?> 
              </tbody>
            </table>            
            </div>
        </div>
      
      