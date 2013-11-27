<div id="content">
    <div class="content_inner">
    	<header>
    		<h2 class="content">回看节目单监测</h2>
    	</header>
        <div style="float:left; width:100%">
          <div class="widget">
            <h3>提示：2小时监测一次,以下是昨天和前天没有回看节目单的频道,请核实</h3>
    		<div class="widget-body">
    		  <ul class="wiki-meta" id="recommend">
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
	width: 50%;
}
</style>
<script type="text/javascript">
var timer;
$(document).ready(function(){
    epg();
    timer=1000*60*120;
    timer=setInterval("epg()",timer); //2小时检查一次
});

//检查回看节目单
function epg() {
 	$.ajax({
        url: '<?php echo url_for('check/epgbak');?>',
        type: 'post',
        dataType: 'json',
        success: function(data)
        {
            if(data.length>0){
                var content = '<table><tr>';
                for (i=0; i<data.length; i++){
                    content+="<td>"+data[i].content+"</td>";
                }
                content += '</tr></table>';
                $('#recommend').html(content);
            }
        }
    });
}
</script>
