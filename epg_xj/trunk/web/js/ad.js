//初始化
function initAD(){
	E.play_ad_0.init($(E.play_ad_0.name),adReady,refreshAD);
	E.play_ad_1.init($(E.play_ad_1.name),adReady,refreshAD);
	E.play_ad_2.init($(E.play_ad_2.name),adReady,refreshAD);
	E.play_ad_3.init($(E.play_ad_3.name),adReady,refreshAD);
	E.play_ad_4.init($(E.play_ad_4.name),adReady,refreshAD);
	E.play_ad_5.init($(E.play_ad_5.name),adReady,refreshAD);
	E.play_ad_6.init($(E.play_ad_6.name),adReady,refreshAD);
	E.play_ad_7.init($(E.play_ad_7.name),adReady,refreshAD);
	E.play_ad_8.init($(E.play_ad_8.name),adReady,refreshAD);
}
//刷新
function refreshed(){
	E.play_ad_0.refreshAD(currChannel.getService());
	E.play_ad_1.refreshAD(currChannel.getService());
}
//打开付费频道的时候需要调用这个方法。
function refresdedFF(){
    //视频广告
	E.play_ad_3.refreshAD(currChannel.getService());
    //下边文字广告
	E.play_ad_4.refreshAD(currChannel.getService());
    //右边图片广告
	E.play_ad_5.refreshAD(currChannel.getService());
    //收视率上传
	ioctlWriteFF();
}
//授权频道的时候需要调用这些广告的刷新
function refresdedAuth(){
    //视频广告
	E.play_ad_3.refreshAD(currChannel.getService());
    //右边图片广告
	E.play_ad_5.refreshAD(currChannel.getService());
    //下边图片广告
	E.play_ad_7.refreshAD(currChannel.getService());
    //收视率上传
	ioctlWriteAuth();
}

var play_ad_name = ["play_ad_0","play_ad_1","play_ad_2","play_ad_3",["play_ad_4_0","play_ad_4_1"],"play_ad_5","play_ad_6","play_ad_7","play_ad_8"];
var play_ad_timer = new Array(8);
var play_ad_pos = [0,0,0,0,0,0,0,0,0];

//这个是pf条的左边的文字广告的记录
//用于记录左边的文字广告是否有显示前端播发的内容，
//同时显示的是对应的那个channel
var ad_pf_flag = false;
var ad_pf_userChannel = -1;

//这个是声道的文字广告的记录，
//用于记录这个广告位是否显示前端播发内容，
var ad_sm_flag = false;

function adReady(id,W){
	var url = W.location.href;
	var data = W.contentWindow.jsonData;
	var type = W.contentWindow.type;
	var pos;
	switch(id) {
		case E.play_ad_0.id:
			pos = 0;
			break;
		case E.play_ad_1.id:
			pos = 1;
			break;
		case E.play_ad_2.id:
			pos = 2;
			break;
		case E.play_ad_3.id:
			pos = 3;
			break;
		case E.play_ad_4.id:
			pos = 4;
			break;
		case E.play_ad_5.id:
			pos = 5;
			break;
		case E.play_ad_6.id:
			pos = 6;
			break;
		case E.play_ad_7.id:
			pos = 7;
			break;
		case E.play_ad_8.id:
			pos = 8;
			break;
	}
	if(typeof pos == "undefined") return;
	url = url.substr(0,url.lastIndexOf("\/")+1);
	iPanel.debug("play type = " + type + "__" + pos);
	if(type == "Fly")  doFly(data,pos);
	else if(type == "Image")  doImage(data,pos,url);
	else if(type == "Video") doVideo(data);

	var tmpService = currChannel.getService();;
	var tmpService_name = iPanel.misc.getUserCharsetStr(tmpService.name,"gb2312");

	if (url.indexOf("ui://") < 0){
		if (pos == 0 || pos == 1 || pos == 6){//pf条的上传广告位
			var ioctlWrite_content = "0x17;"+E.get_ca_id()+";"+E.stb_id +";" + id + ";" + tmpService.serviceId + ";" + tmpService.TSId + ";"  +tmpService.frequency + ";" + tmpService_name + ";;1";
			iPanel.debug("play -> adReady -> ioctlWrite =" + ioctlWrite_content);
			iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
		}
		if (pos == 1){
			ad_pf_flag = true;
			ad_pf_userChannel = currChannel.userChannel;
		}else if (pos == 2){
			ad_sm_flag = true;
		}
	}else{
		var curr_pf_flag = 0;
		for (var i=0; i<special_channel.length; i++){
			if (tmpService_name == special_channel[i].name){curr_pf_flag = 1;break;}
		}
        //pf条的上传无广告位
		if (pos == 0 || pos == 1 || curr_pf_flag == 1){
			var ioctlWrite_content = "0x17;"+E.get_ca_id()+";"+E.stb_id +";" + id + ";" + tmpService.serviceId + ";" + tmpService.TSId + ";"  +tmpService.frequency + ";" + tmpService_name + ";;0";
			iPanel.debug("play -> adReady_no -> ioctlWrite =" + ioctlWrite_content);
			iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
		}
		if (pos == 1){
			ad_pf_flag = false;
			ad_pf_userChannel = -1;
		} else if (pos == 2){
			ad_sm_flag = false;
		}
	}
}

//文字广告
function doFly(data,pos){
	var len = data.flyads.flyEntrys.length;
	var obj = data.flyads;
	if(len > 0){
		if (pos == 4){
			var content = (iPanel.misc.getUserCharsetStr(obj.flyEntrys[0].content,"gb2312")).split('&');
			$(play_ad_name[pos][0]).innerHTML = "<font style='color:"+obj.fontColor+";font-size:"+obj.fontSize+"px;'>" + content[0] + "</font>";
			$(play_ad_name[pos][1]).innerHTML = "<font style='color:"+obj.fontColor+";font-size:"+obj.fontSize+"px;'>" + content[1] + "</font>";;//默认仅读取第一条数据。
		}else{
			var str = "<font style='color:"+obj.fontColor+";font-size:"+obj.fontSize+"px;'>" + iPanel.misc.getUserCharsetStr(obj.flyEntrys[0].content,"gb2312") + "</font>";
			$(play_ad_name[pos]).innerHTML = str;
		}
	}
}

//图片广告
function doImage(data,pos,url){
	var len = data.imgAds.imgEntrys.length;
	if(len > 0){
		//特殊处理pos == 6的情况
		if (pos == 6 && url.indexOf("ui:") > -1){//使用UI默认的图片
			var curr_name = iPanel.misc.getUserCharsetStr(currChannel.name,"gb2312");
			for (var i=0; i<special_channel.length; i++){
				if (curr_name == special_channel[i].name)
				{
					iPanel.debug("play_doImage_special_channel_" + special_channel[i].pic);
					$(play_ad_name[pos]).src = special_channel[i].pic;
					return;
				}
			}
		}
		iPanel.debug("play doImage_ " + pos + "__" + url + data.imgAds.imgEntrys[play_ad_pos[pos]].src);
		$(play_ad_name[pos]).src = url + data.imgAds.imgEntrys[play_ad_pos[pos]].src;
		if(len > 1){
			play_ad_pos[pos] = (play_ad_pos[pos] + 1) % len;
			clearTimeout(play_ad_timer[pos]);
			play_ad_timer[pos] = setTimeout(function(){
				doImage(data,pos,url);
			},data.imgAds.imgEntrys[play_ad_pos[pos]].pauseTime * 1000);
		}
	}
}

//视频广告
function doVideo(data){
	media.video.setPosition(0,0,947,536);
	iPanel.debug("frequency = " + data.frequency + " , serviceId = " + data.serviceId);
	DVB.playAV(data.frequency,data.serviceId);
}

//付费频道的时候上传
function ioctlWriteFF(){
	var tmpService = currChannel.getService();
	var tmpService_name = iPanel.misc.getUserCharsetStr(tmpService.name,"gb2312");
	
	iPanel.debug("play_ioctlWriteFF_txt = " + $("play_ad_4_0").innerHTML);

	if ($("play_ad_4_0").innerHTML != " " || $("play_ad_4_1").innerHTML != " "){
		var ioctlWrite_content = "0x17;"+E.get_ca_id()+";"+E.stb_id +";55;" + tmpService.serviceId + ";" + tmpService.TSId + ";"  +tmpService.frequency + ";" + tmpService_name + ";;1";

		iPanel.debug("play -> ioctlWriteFF -> play_ad_4_ioctlWrite =" + ioctlWrite_content);
		iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
	}
	else{
		var ioctlWrite_content = "0x17;"+E.get_ca_id()+";"+E.stb_id +";55;" + tmpService.serviceId + ";" + tmpService.TSId + ";"  +tmpService.frequency + ";" + tmpService_name + ";;0";

		iPanel.debug("play -> ioctlWriteFF -> play_ad_4_ioctlWrite =" + ioctlWrite_content);
		iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
	}

	var pic_src = $("play_ad_5").src;
	iPanel.debug("play_ioctlWriteFF_play_ad_5_src = " + pic_src);
	if (pic_src.indexOf("ui:") < 0){
		var ioctlWrite_content = "0x17;"+E.get_ca_id()+";"+E.stb_id +";56;" + tmpService.serviceId + ";" + tmpService.TSId + ";"  +tmpService.frequency + ";" + tmpService_name + ";;1";

		iPanel.debug("play -> ioctlWriteFF -> play_ad_5_ioctlWrite =" + ioctlWrite_content);
		iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
	}
	else{
		var ioctlWrite_content = "0x17;"+E.get_ca_id()+";"+E.stb_id +";56;" + tmpService.serviceId + ";" + tmpService.TSId + ";"  +tmpService.frequency + ";" + tmpService_name + ";;0";

		iPanel.debug("play -> ioctlWriteFF_no -> play_ad_5_ioctlWrite =" + ioctlWrite_content);
		iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
	}
}

//付费频道的时候上传
function ioctlWriteAuth(){
	var tmpService = currChannel.getService();
	var tmpService_name = iPanel.misc.getUserCharsetStr(tmpService.name,"gb2312");

	var pic_src = $("play_ad_5").src;
	iPanel.debug("play_ioctlWriteFF_play_ad_5_src = " + pic_src);
	if (pic_src.indexOf("ui:") < 0){
		var ioctlWrite_content = "0x17;"+E.get_ca_id()+";"+E.stb_id +";56;" + tmpService.serviceId + ";" + tmpService.TSId + ";"  +tmpService.frequency + ";" + tmpService_name + ";;1";

		iPanel.debug("play -> ioctlWriteAuth -> play_ad_5_ioctlWrite =" + ioctlWrite_content);
		iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
	}else{
		var ioctlWrite_content = "0x17;"+E.get_ca_id()+";"+E.stb_id +";56;" + tmpService.serviceId + ";" + tmpService.TSId + ";"  +tmpService.frequency + ";" + tmpService_name + ";;0";

		iPanel.debug("play -> ioctlWriteAuth_no -> play_ad_5_ioctlWrite =" + ioctlWrite_content);
		iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
	}

	var pic_src = $("play_ad_7").src;
	iPanel.debug("play_ioctlWriteFF_play_ad_7_src = " + pic_src);
	if (pic_src.indexOf("ui:") < 0){
		var ioctlWrite_content = "0x17;"+E.get_ca_id()+";"+E.stb_id +";58;" + tmpService.serviceId + ";" + tmpService.TSId + ";"  +tmpService.frequency + ";" + tmpService_name + ";;1";

		iPanel.debug("play -> ioctlWriteAuth -> play_ad_7_ioctlWrite =" + ioctlWrite_content);
		iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
	}else{
		var ioctlWrite_content = "0x17;"+E.get_ca_id()+";"+E.stb_id +";58;" + tmpService.serviceId + ";" + tmpService.TSId + ";"  +tmpService.frequency + ";" + tmpService_name + ";;0";

		iPanel.debug("play -> ioctlWriteAuth_no -> play_ad_7_ioctlWrite =" + ioctlWrite_content);
		iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
	}
}

// 更新广告
function refreshAD(arr){
	var len = arr.length;
	iPanel.debug("play.htm  refreshAD() arr.length = " + len);
	for(var i=0; i<len; i++){
		var tmp_id = arr[i];
		iPanel.debug("play.htm  refreshAD() tmp_id = " + tmp_id);
		switch(tmp_id){
			case E.play_ad_0.id: //刷新广告位1
				E.play_ad_0.refreshed = false;
				E.play_ad_0.refreshAD(currChannel.getService());
				break;
			case E.play_ad_1.id: //刷新广告位2
				E.play_ad_1.refreshed = false;
				E.play_ad_1.refreshAD(currChannel.getService());
				break;
			case E.play_ad_2.id: //刷新广告位2
				E.play_ad_2.refreshed = false;
				E.play_ad_2.refreshAD(currChannel.getService());
				break;
			case E.play_ad_3.id: //刷新广告位1
				E.play_ad_3.refreshed = false;
				E.play_ad_3.refreshAD(currChannel.getService());
				break;
			case E.play_ad_4.id: //刷新广告位2
				E.play_ad_4.refreshed = false;
				E.play_ad_4.refreshAD(currChannel.getService());
				break;
			case E.play_ad_5.id: //刷新广告位2
				E.play_ad_5.refreshed = false;
				E.play_ad_5.refreshAD(currChannel.getService());
				break;
			case E.play_ad_6.id: //刷新广告位2
				E.play_ad_6.refreshed = false;
				E.play_ad_6.refreshAD(currChannel.getService());
				break;
			case E.play_ad_7.id: //刷新广告位2
				E.play_ad_7.refreshed = false;
				E.play_ad_7.refreshAD(currChannel.getService());
				break;
			case E.play_ad_8.id: //刷新广告位2
				E.play_ad_8.refreshed = false;
				E.play_ad_8.refreshAD();
				break;
		}
	}	
}

//上报广告收视
function ioctlWriteUdc() {
    var tmpService = currChannel.getService();;
    var tmpService_name = iPanel.misc.getUserCharsetStr(tmpService.name,"gb2312");		
    if (ad_sm_flag){
        var ioctlWrite_content = "0x17;"+E.get_ca_id()+";"+E.stb_id +";53;" + tmpService.serviceId + ";" + tmpService.TSId + ";"  +tmpService.frequency + ";" + tmpService_name + ";;1";

        iPanel.debug("music -> changeMode -> play_ad_2_ioctlWrite =" + ioctlWrite_content);
        iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
    }
    else{
        var ioctlWrite_content = "0x17;"+E.get_ca_id()+";"+E.stb_id +";53;" + tmpService.serviceId + ";" + tmpService.TSId + ";"  +tmpService.frequency + ";" + tmpService_name + ";;0";

        iPanel.debug("music -> changeMode_no ->  play_ad_2_ioctlWrite =" + ioctlWrite_content);
        iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
    }
}