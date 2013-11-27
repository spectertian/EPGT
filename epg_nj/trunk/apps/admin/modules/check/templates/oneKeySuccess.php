<div id="content">
    <div class="content_inner">
    	<header>
    		<h2 class="content">值班监测</h2>
    	</header>
        <div style="float:left; width:100%">
          <div class="widget">
            <h3>提示：2小时监测一次,以下是监测结果,请核实(返回结果有10分钟的缓存)</h3>
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
	width: 33%;
}
</style>
<script type="text/javascript">
var timer;
$(document).ready(function(){
    onekey();
    timer=1000*60*120;
    timer=setInterval("onekey()",timer); //2小时检查一次
});

//监测
function onekey() {
 	$.ajax({
        url: '<?php echo url_for('check/oneKey');?>',
        type: 'post',
        dataType: 'json',
        success: function(data)
        {
            var statu = '';
            //data=eval("("+data+")");  
            var content = '<table><tr><td><strong>监测项目</strong></td><td><strong>监测结果</strong></td><td><strong>状态</strong></td></tr>';
            if(data.wikiNum.statu=='异常'){
                content+='<tr><td>最近两天维基更新数量</td><td><a href="/wiki">'+data.wikiNum.num+'</a></td><td style="color:#ff0000">'+data.wikiNum.statu+'</td></tr>';
            }else{
                content+='<tr><td>最近两天维基更新数量</td><td><a href="/wiki">'+data.wikiNum.num+'</a></td><td style="color:#00aa00">'+data.wikiNum.statu+'</td></tr>';
            }
            if(data.crontabNum.statu=='异常'){
                content+='<tr><td>最近两天计划任务错误数量</td><td><a href="/crontabLog">'+data.crontabNum.num+'</a></td><td style="color:#ff0000">'+data.crontabNum.statu+'</td></tr>';
            }else{
                content+='<tr><td>最近两天计划任务错误数量</td><td><a href="/crontabLog">'+data.crontabNum.num+'</a></td><td style="color:#00aa00">'+data.crontabNum.statu+'</td></tr>';
            }
            if(data.videoError.statu=='异常'){
                content+='<tr><td>上线电视剧错误数量</td><td><a href="/count/videoCount">'+data.videoError.num+'</a></td><td style="color:#ff0000">'+data.videoError.statu+'</td></tr>';
            }else{
                content+='<tr><td>上线电视剧错误数量</td><td><a href="/count/videoCount">'+data.videoError.num+'</a></td><td style="color:#00aa00">'+data.videoError.statu+'</td></tr>';
            }
            if(data.videoPlayError.statu=='异常'){
                content+='<tr><td>无法播放视频统计</td><td><a href="/count/videoPlayCount">'+data.videoPlayError.num+'</a></td><td style="color:#ff0000">'+data.videoPlayError.statu+'</td></tr>';
            }else{
                content+='<tr><td>无法播放视频统计</td><td><a href="/count/videoPlayCount">'+data.videoPlayError.num+'</a></td><td style="color:#00aa00">'+data.videoPlayError.statu+'</td></tr>';
            }
            //深度节目单缺失情况
            content+='<tr><td>深度节目单缺失情况</td><td>';
            statu = '';
            for (i=0; i<data.epg.length; i++){
                content+=data.epg[i].date+'------<br/>';
                content+=data.epg[i].content;
                statu+=data.epg[i].content;
            }
            content += '</td>';
            if(statu!=''){
                content += '<td style="color:#ff0000">异常</td>';
            }else{
                content += '<td style="color:#00aa00">正常</td>';
            }
            content += '</tr>';
            
            //一周节目单缺失情况
            content+='<tr><td>一周节目单缺失情况</td><td>';
            statu = '';
            for (i=0; i<data.programWeek.length; i++){
                content+=data.programWeek[i].date+'------<br/>';
                content+=data.programWeek[i].content;
                statu+=data.programWeek[i].content;
            }
            content += '</td>';
            if(statu!=''){
                content += '<td style="color:#ff0000">异常</td>';
            }else{
                content += '<td style="color:#00aa00">正常</td>';
            }
            content += '</tr>';
            
            //回看节目单缺失情况
            content+='<tr><td>回看节目单缺失情况</td><td>';
            statu = '';
            for (i=0; i<data.cpg.length; i++){
                content+=data.cpg[i].date+'------<br/>';
                content+=data.cpg[i].content;
                statu+=data.cpg[i].content;
            }
            content += '</td>';
            if(statu!=''){
                content += '<td style="color:#ff0000">异常</td>';
            }else{
                content += '<td style="color:#00aa00">正常</td>';
            }
            content += '</tr>';
            
            //以播出为准节目单
            content+='<tr><td>以播出为准节目单</td><td>';
            statu = '';
            for (i=0; i<data.epgbochu.length; i++){
                content+=data.epgbochu[i].date+'------<br/>';
                content+=data.epgbochu[i].content;
                statu+=data.epgbochu[i].content;
            }
            content += '</td>';
            if(statu!=''){
                content += '<td style="color:#ff0000">异常</td>';
            }else{
                content += '<td style="color:#00aa00">正常</td>';
            }
            content += '</tr>';
            
            //深度节目单更新情况
            content+='<tr><td>深度节目单更新情况</td><td>';
            statu = '';
            for (i=0; i<data.epgNoUpdate.length; i++){
                content+='<a target="_blank" href="/program?channel_code='+data.epgNoUpdate[i].code+'&type='+data.epgNoUpdate[i].type+'&date='+data.epgNoUpdate[i].date+'">'+data.epgNoUpdate[i].name+'</a><br/>';
                statu+=data.epgNoUpdate[i].code;
            }
            content += '</td>';
            if(statu!=''){
                content += '<td style="color:#ff0000">异常</td>';
            }else{
                content += '<td style="color:#00aa00">正常</td>';
            }
            content += '</tr>';
            content += '</table>';
            $('#recommend').html(content);
        }
    });
}
</script>
