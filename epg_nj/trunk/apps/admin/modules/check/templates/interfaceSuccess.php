<style type="text/css">
table td{
    word-wrap:break-word;word-break:break-all;
    text-align:left;
}
</style>
<div id="content">
    <div class="content_inner">
    	<header>
    		<h2 class="content">各接口监测<input type="hidden" name="soundName" id="soundName" value=""/></h2>
    	</header>
        <div style="float:left; width:100%">
          <div class="widget">
            <h3>提示：5分钟监测一次</h3>
    		<div class="widget-body">
    		  <ul class="wiki-meta" id="recommend">
              </ul>
              
    		  <ul id="right">
              </ul>
              各接口地址如下：<br />
              <table width="100%" style="table-layout:fixed;word-wrap:break-word;word-break:break-all">
              <tr><td style="width: 20%;"><b>TCL直播推荐接口：</b></td><td style="width: 80%;"><?php echo sfConfig::get('app_recommend_tclUrl')?>?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=10&genre=Movie&uid=825010288629384</td></tr>
              <tr><td><b>TCL点播推荐接口：</b></td><td><?php echo sfConfig::get('app_recommend_tclUrl')?>?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=vod&count=10&uid=825010288629384&genre=Movie&backurl=</td></tr>
              <tr><td><b>运营中心直播推荐接口：</b></td><td><?php echo sfConfig::get('app_recommend_centerUrl')?>?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&rtype=recommend.livetv.v1&operation=GetRecommendList&ctype=vod&count=10&uid=99766609340071223_0&lang=zh&cid=10557718</td></tr>
              <tr><td><b>运营中心点播推荐接口：</b></td><td><?php echo sfConfig::get('app_recommend_centerUrl')?>?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&postertype=1&count=10&lang=zh&urltype=1&alg=CF&uid=99766609340071223_0&filter=Category6%3D%27%E6%96%B0%E9%97%BB%E6%97%B6%E7%A7%BB%27&backurl=</td></tr>
              <tr><td><b>技术部直播推荐接口：</b></td><td><?php echo sfConfig::get('app_recommend_tongzhouUrl')?>?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=epg&count=10&genre=Movie&uid=8250102113075968</td></tr>
              <tr><td><b>技术部点播推荐接口：</b></td><td><?php echo sfConfig::get('app_recommend_tongzhouUrl')?>?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=vod&count=10&genre=Movie&uid=8250102113075968</td></tr>
              <tr><td><b>终端网管节目收视率：</b></td><td><?php echo sfConfig::get("app_statsQuery_biz")?>?CMD=Channel</td></tr>
              <tr><td><b>用户行为数据：</b></td><td>ftp地址:<?php echo sfConfig::get("app_DataWarehouse_ip");?> 用户名:<?php echo sfConfig::get("app_DataWarehouse_username");?> 密码:<?php echo sfConfig::get("app_DataWarehouse_password");?></td></tr>
              <tr><td><b>CMS系统CDI接口：</b></td><td><?php echo sfConfig::get('app_cmsCenter_bkjsonVod');?>?action=adi1synccallback</td></tr>
              <tr><td><b>CMS系统Epg接收接口：</b></td><td><?php echo sfConfig::get('app_cmsCenter_bkjson');?>?action=adi1synccallback</td></tr>
              <tr><td><b>运营中心视频接口：</b></td><td><?php echo  sfConfig::get("app_cpgPortal_url");?>?action=adi1synccallback</td></tr>
              <tr><td><b>PPTV和1905视频接口：</b></td><td><?php echo sfConfig::get("app_linkQuery_center");?>?action=adi1synccallback</td></tr>
              </table>
    		</div>
          </div>
        </div> 
    </div>
</div>
<style type="text/css">
.check_ok{
    background-color: #00ff00;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #006600;
}
.check_error{
    background-color: #ff0000;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #006600;
}
.check_ok span{
    display:block;
    width: 150px;
    float: left;
}
.check_error span{
    display:block;
    width: 150px;
    float: left;
}
</style>
<script type="text/javascript">
var timer;
$(document).ready(function(){
    checkRecommend();
    timer=setInterval("checkRecommend()", 1000*300); //5分钟检查一次
});

//检查
function checkRecommend() {
 	$.ajax({
        url: '<?php echo url_for('check/interface');?>',
        type: 'post',
        dataType: 'text',
        success: function(data)
        {
            if(data.length>0){
                $('#recommend').html(data);
                if (data.indexOf('tclLiveError')>=0){
                    $('#soundName').attr('value','tclLiveError.wav');
                    soundAlert();
                }else if(data.indexOf('tclVodError')>=0){
                    $('#soundName').attr('value','tclVodError.wav');
                    soundAlert();
                }else if(data.indexOf('centerLiveError')>=0){
                    $('#soundName').attr('value','centerLiveError.wav');
                    soundAlert();
                }else if(data.indexOf('centerVodError')>=0){
                    $('#soundName').attr('value','centerVodError.wav');
                    soundAlert();
                }else if(data.indexOf('tongzhouLiveError')>=0){
                    $('#soundName').attr('value','tongzhouLiveError.wav');
                    soundAlert();
                }else if(data.indexOf('tongzhouVodError')>=0){
                    $('#soundName').attr('value','tongzhouVodError.wav');
                    soundAlert();
                }else if(data.indexOf('zhongduanError')>=0){
                    $('#soundName').attr('value','zhongduanError.wav');
                    soundAlert();
                }else if(data.indexOf('userError')>=0){
                    $('#soundName').attr('value','userError.wav');
                    soundAlert();
                }else if(data.indexOf('cmsCdiError')>=0){
                    $('#soundName').attr('value','cmsCdiError.wav');
                    soundAlert();
                }else if(data.indexOf('cmsEpgError')>=0){
                    $('#soundName').attr('value','cmsEpgError.wav');
                    soundAlert();
                }else if(data.indexOf('centerVideoError')>=0){
                    $('#soundName').attr('value','centerVideoError.wav');
                    soundAlert();
                }else if(data.indexOf('pptvError')>=0){
                    $('#soundName').attr('value','pptvError.wav');
                    soundAlert();
                }else if(data.indexOf('portal11Error')>=0){
                    $('#soundName').attr('value','11Error.wav');
                    soundAlert();
                }else if(data.indexOf('portal12Error')>=0){
                    $('#soundName').attr('value','12Error.wav');
                    soundAlert();
                }else if(data.indexOf('portal13Error')>=0){
                    $('#soundName').attr('value','13Error.wav');
                    soundAlert();
                }else if(data.indexOf('portal14Error')>=0){
                    $('#soundName').attr('value','14Error.wav');
                    soundAlert();
                }else if(data.indexOf('portal15Error')>=0){
                    $('#soundName').attr('value','15Error.wav');
                    soundAlert();
                }else if(data.indexOf('portal16Error')>=0){
                    $('#soundName').attr('value','16Error.wav');
                    soundAlert();
                }else if(data.indexOf('portal17Error')>=0){
                    $('#soundName').attr('value','17Error.wav');
                    soundAlert();
                }else if(data.indexOf('portal18Error')>=0){
                    $('#soundName').attr('value','18Error.wav');
                    soundAlert();
                }else if(data.indexOf('portal19Error')>=0){
                    $('#soundName').attr('value','19Error.wav');
                    soundAlert();
                }else if(data.indexOf('portal20Error')>=0){
                    $('#soundName').attr('value','20Error.wav');
                    soundAlert();
                }else if(data.indexOf('portal21Error')>=0){
                    $('#soundName').attr('value','21Error.wav');
                    soundAlert();
                }else if(data.indexOf('portal22Error')>=0){
                    $('#soundName').attr('value','22Error.wav');
                    soundAlert();
                }
            }
        }
    });
}
function soundAlert(){
    fileName=$('#soundName').attr('value');
    if(fileName!=''){
        host=window.location.host;
        if (document.all) {
            sounda=' <OBJECT id="Player" classid="clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6" width=0 height=0 > <param name="URL" value="/sound/'+fileName+'" /> <param name="AutoStart" value="true" /></OBJECT>';
        }else {
            sounda='<embed src="/sound/'+fileName+'" loop="0" autostart="true" hidden="true"></embed>';
        }
        $('#right').html(sounda);
        setTimeout(function(){$('#right').html('');},1000);
    }
}
function soundPlay(fileName){
    host=window.location.host;
    $('#right').html('');
    if (document.all) {
        sound=' <OBJECT id="Player" classid="clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6" width=0 height=0 > <param name="URL" value="http://'+host+'/sound/'+fileName+'" /> <param name="AutoStart" value="false" /> <param name="PlayCount" value="3"></OBJECT>';
    }else {
        sound=' <OBJECT id="Player" type="application/x-ms-wmp" src= "http://'+host+'/sound/'+fileName+'" width=0 height=0> </OBJECT>';
    }
    $('#right').html(sound);
    Player.controls.play();
}
</script>
