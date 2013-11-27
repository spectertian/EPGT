<script type="text/javascript">
$(document).ready(function(){
	var name    = $(".sf_admin_list_td_name");
	var time    = $(".sf_admin_list_td_time");
	
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
    <?php $cctvcodes = unserialize($_COOKIE[$adminid."_cctvcodes"])?>
    <?php $tvcodes = unserialize($_COOKIE[$adminid."_tvcodes"])?> 
    <?php $channel_codes = array_merge($cctvcodes,$tvcodes)?>   
    <?php if(!empty($channel_codes) ):?>
	ajax_now();
	ajax_next();
	setInterval("ajax_now()",1000*60);
	setInterval("ajax_next()",1000*60);
    <?php else:?>
	ajax_cctv();
	ajax_tv();
	setInterval("ajax_cctv()",1000*60);
	setInterval("ajax_tv()",1000*60);
    <?php endif;?>
	
});
function ajax_now()
{
	$('#load_cctv').html("<img src='/images/throbber.gif' />");
    $.ajax({
        url: "program_live/now",
        success:function(data)
        {
        	$("#left").text("正在播放：")
            $('#cctv').html(data);
            $('#load_cctv').text("等待下一次执行");
        },error:function()
        {
        }
    });
}
function ajax_next()
{
	$('#load_tv').html("<img src='/images/throbber.gif' />");
    $.ajax({
        url: "program_live/next",
        success:function(data)
        {
    		$("#right").text("即将播放：");
            $('#tv').html(data);
            $('#load_tv').text("等待下一次执行");
        },error:function()
        {
        }
    });
}

function ajax_cctv()
{
	$('#load_cctv').html("<img src='/images/throbber.gif' />");
    $.ajax({
        url: "program_live/CCTV",
        success:function(data)
        {
    		$("#left").text("cctv：");
            $('#cctv').html(data);
            $('#load_cctv').text("等待下一次执行");
        },error:function()
        {
        }
    });
}
function ajax_tv()
{
	$('#load_tv').html("<img src='/images/throbber.gif' />");
    $.ajax({
        url: "program_live/TV",
        success:function(data)
        {
    		$("#right").text("tv：");
            $('#tv').html(data);
            $('#load_tv').text("等待下一次执行");
        },error:function()
        {
        }
    });
}

function ajax_update(id ,key)
{
    var value   = $("#postValue").val();

    $.ajax({
        url:            "program_live/ajax_update?id=" + id +"&key="+key+"&value="+value,
        dataType:       "json",
        success:function(data)
        {
            if(data.code == 1)
            {
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
            <?php include_partial("toobal") ?>
            <?php //include_partial('global/flashes') ?>
            <?php //include_partial('weeks'); ?>
            <div class="table_nav">
            <?php //include_partial('search',array( 'channel'=>$channel,'start_time'=>$start_time,'end_time'=>$end_time));?>
            <?php //include_partial("list",array("pager"=>$pager,'channel'=>$channel,'start_time'=>$start_time,'end_time'=>$end_time)); ?>
            
            
            
            <div style="float:left;width:48%" >
            <h3 id="left">cctv:</h3><span id="load_cctv"></span>
            <table cellspacing="0" class="adminlist" id="admin_list">
              <thead>
                <tr>
                  <th scope="col"  name="name" style="width: 40%">频道</th>
                  <th scope="col"  name="channel_id" style="width: 40%">名称</th>
                  <th scope="col"  name="time" style="width: 20%">播放时间</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col">频道</th>
                  <th scope="col">名称</th>
                  <th scope="col">播放时间</th>
                </tr>
              </tfoot>
              <tbody id="cctv">

              </tbody>
            </table>           
            </div>
            
            
            
            
            <div style="float:right;width:48%" >
            <h3 id="right">tv:</h3><span id="load_tv"></span>
            <table cellspacing="0" class="adminlist" id="admin_list">
              <thead>
                <tr>
                  <th scope="col"  name="name"  style="width: 40%">频道</th>
                  <th scope="col"  name="channel_id" style="width: 40%">名称</th>
                  <th scope="col"  name="time"  style="width: 20%">播放时间</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col">频道</th>
                  <th scope="col">名称</th>
                  <th scope="col">播放时间</th>
                </tr>
              </tfoot>
              <tbody id="tv">

              </tbody>
            </table>            
            </div>
        </div>
      
      