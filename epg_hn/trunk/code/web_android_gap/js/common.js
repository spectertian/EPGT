//连接设置
function setNetAndStart(jsondata) {
	var data = new Array("192.168.1.100","9999");
  	PhoneGap.exec(GapsuccessCallback, GapfailureCallback, 'NetSettingPlugin', '', data);
}
//频道切换
//{"channelcode":"cctv1"}
function changeChannelToTV(jsondata) {
	alert("loading...");
	setNetAndStart();
	var data = new Array(jsondata.channelcode);
    PhoneGap.exec(GapsuccessCallback, GapfailureCallback, 'TvChangePlugin', '', data);
}
//电视播放视频
//{"type" : "http", "format": "avi", "url": url}
//{"type" : "pptv", "format": "avi", "url": "1d7c1WCcaMih2c7j4a5aepuYsJmUqGVaeJiYnaGUp2dae6WjnI6xqFd2fYmlpJfc1WY%3d"}
function playMedia(jsondata) {
	alert("loading...");
	alert(jsondata.url);
	var data = new Array(jsondata.type, jsondata.format, jsondata.url);
  	PhoneGap.exec(GapsuccessCallback, GapfailureCallback, 'PlayVideoPlugin', '', data);
}
//本地播放视频
//{"type" : "http", "format": "avi", "url": url}
//{"type" : "pptv", "format": "avi", "url": "1d7c1WCcaMih2c7j4a5aepuYsJmUqGVaeJiYnaGUp2dae6WjnI6xqFd2fYmlpJfc1WY%3d"}
function playMediaToTV(jsondata) {
	alert("loading...");
	setNetAndStart();
	var data = new Array(jsondata.type, jsondata.format, jsondata.url);
  	PhoneGap.exec(GapsuccessCallback, GapfailureCallback, 'PlayTvPlugin', '', data);
}
//默认的回调事件
function GapsuccessCallback(data) {
	//alert("data:"+data);
}
//默认的回调事件           
function GapfailureCallback(data) {
	alert("error:"+data);
}

$(function(){
	//setNetAndStart();
});
