<div id="content">
    <div class="content_inner">
    	<header>
    		<h2 class="content">视频播放错误日志</h2>
    	</header>
        <div style="float:left; width:100%">
          <div class="widget">
            <h3>提示：以下是不能播放的视频,请核实</h3>
    		<div class="widget-body">
    		  <ul class="wiki-meta" id="recommend">
              加载时间较长，请耐心等待......
              </ul>
              
    		  <ul id="right">
              </ul>
    		</div>
          </div>
        </div> 
    </div>
</div>
<style type="text/css">
.check_ok{
    background-color: #00ff00;
}
.check_error{
    background-color: #ff0000;
}
#recommend td{
    text-align:left;
	padding-top: 10px;
	padding-left: 10px;
    vertical-align: top;
	width: 33%;
}
</style>
<script type="text/javascript">
var timer;
$(document).ready(function(){
    epg();
    timer=1000*60*120;
    timer=setInterval("epg()",timer); //2小时检查一次
});

//检查欢网节目单
function epg() {
 	$.ajax({
        url: '<?php echo url_for('count/videoPlayCount');?>',
        type: 'post',
        dataType: 'json',
        success: function(data)
        {
            if(data.length>0){
                var content='总共有'+data.length+'个视频不能播放<br/>' ;
                content += '<table style="width:100%"><tr><td style="width:20%">id</td><td style="width:40%">名称</td><td style="width:20%">asset_id</td><td style="width:20%">Page_id</td></tr>';
                for (i=0; i<data.length; i++){
                    content+='<tr>';
                    content+="<td>"+data[i].id+"</td>";
                    content+="<td>"+data[i].title+"</td>";
                    content+="<td>"+data[i].asset_id+"</td>";
                    content+="<td>"+data[i].page_id+"</td>";
                    content+='</tr>';
                }
                content += '</table>';
                $('#recommend').html(content);
            }else{
                $('#recommend').html('暂无不能播放的视频！');
            }
        }
    });
}
</script>