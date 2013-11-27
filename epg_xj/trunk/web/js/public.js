//静音
function keyMuteEvent() {
    if (media.sound.isMute==1){
		if (channelWidget.isShow)channelWidget.exitPage();
		if (pfShow) hidePfBar();
	}
	E.changeVolume(0);
    return 0;
}

//声道切换
function keyAudioEvent() {
    changeMode(1);
	return 0;
}

//EPG
function keyEpgEvent() {
    iPanel.mainFrame.location.href = "ui://system/epg.htm";
    return 0;
}

//HOMEPAGE
function keyHomepageEvent() {
    iPanel.eventFrame.ip_load_index();
	return 0;
}

//显示CA状态
function showAdStatus() {
    if (L_ad_status){
        if (cardIdShow){
            hide_ca_cardId();
        }
        else{
            var smartcard_in = iPanel.getGlobalVar("smartcard_in");
            var isCurrCACard = CA.card.isCurrCACard; //判断是否是本卡
            if (smartcard_in != "0" && isCurrCACard == 1){//智能卡插上,并是本盒子卡,显示卡号
                show_ca_cardId();
            }
        }
    }
}

//进入时移
function playTimeshit() {
    iPanel.debug("play__enter_timeShift");
    var ioctlWrite_content = "0x0f;"+E.get_ca_id()+";"+E.stb_id+";F2";
    iPanel.debug("play.html -> KEY_F2 -> ioctlWrite = " + ioctlWrite_content);
    iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);

    var flag = currChannel.hasOperation("TSTV");

    iPanel.debug("play__channel is timeShift == " + flag);
    //判断网络链接状态,如果网络不通，则发出来消息提醒一下，不进去时移
    //这里不管是DVB还是IP的方式的时移，均需要做提醒
    var ethernet_item = network.ethernets[0];
    if (ethernet_item.LANStatus==0 ||(ethernet_item.LANStatus!=3&&network.ethernets[0].DHCPEnable==1))
    {
        $("net_text").innerText = titleTxt[lang][10];
        $("net_tips").style.visibility = "visible";
        setTimeout(function(){
            $("net_tips").style.visibility = "hidden";
        },3000);
        return;
    }
    iPanel.debug("play__network is ok");
    if(flag == 1){
        var url = currChannel.entrance;

        var start_time = "";
        var d = new Date();
        var year = d.getYear();
        var month = d.getMonth() + 1;
        var date = d.getDate();
        var hour = d.getHours();
        var minute = d.getMinutes();
        var second = d.getSeconds();
        if(parseInt(month) < 10) month = "0" + month;
        if(parseInt(date) < 10) date = "0" + date;
        if(parseInt(hour) < 10) hour = "0" + hour;
        if(parseInt(minute) < 10) minute = "0" + minute;
        if(parseInt(second) < 10) second = "0" + second;
        start_time = ""+year+month+date+hour+minute+second;

        iPanel.setGlobalVar("back_url","play");

        iPanel.debug("play__timeshit url ==" + url);
        iPanel.mainFrame.location = url+"&start-time="+start_time;//进入时移不能在用location.href
    }
}

function channelNoFound() {
    show(titleTxt[lang][8]);
    clearTimeout(notFoundTimer);
    notFoundTimer = setTimeout(function(){
        checkNotes();
	},2000);
	return 0;
}

//DVB_CABLE_CONNECT
function cableConnect(status) {
    iPanel.setGlobalVar("cable_connect_success",status);
	checkNotes();
}

//CA_MESSAGE_OPEN
function caMessageOpen() {
    var return_type = E.CA_OBJ.checkCAType(eventObj.args.modifiers);
    iPanel.debug("play_CA_MESSAGE_OPEN_return_type="+return_type);
    if(return_type != -1){
        iPanel.setGlobalVar("ca_id",eventObj.args.modifiers);
        iPanel.debug("play_CA_MESSAGE_OPEN_message_type="+E.CA_OBJ.getCaMessageTypeById(eventObj.args.modifiers));
        if (E.CA_OBJ.getCaMessageTypeById(eventObj.args.modifiers) == 130)
        {//应急广播
            var ca_message = iPanel.misc.getMessageById(eventObj.args.modifiers);
            var ca_timeout = ca_message.timeout;
            $('CAUrgencyBroadcast').style.visibility = 'visible';
            setTimeout("$('CAUrgencyBroadcast').style.visibility = 'hidden'",ca_timeout);
        }
        else {
            //DVB.restartAV(0);						//兼容st平台只能用这种方式清帧的问题,重启解码器
            DVB.clearVideoLevel(1);
        }
        if (E.CA_OBJ.getCaMessageTypeById(eventObj.args.modifiers) == 201){//付费广告
            //显示付费广告
            //$("ca_video_ad").style.visibility = "visible";

            iPanel.debug("play_CA_MESSAGE_OPEN_201");
            L_ad_status = true;
            $("pay_txt_ad").style.visibility = "visible";
            $("ca_pic_ad").style.visibility = "visible";
            refresdedFF();					
        }
        else if (E.CA_OBJ.getCaMessageTypeById(eventObj.args.modifiers) == 110){//未授权广告
            L_ad_status = true;
            /*$("authoriza_ad").style.visibility = "visible";
            $("ca_pic_ad").style.visibility = "visible";
            refresdedAuth();*/
            
            /*2012-08-27 应现场需求，将数码的Ca发出来未授权的消息后，与前端bouquet下发的付费频道进行比较，如包含在付费频道中，则显示付费的广告*/
            if (checkFF()){//付费频道
                $("pay_txt_ad").style.visibility = "visible";
                $("ca_pic_ad").style.visibility = "visible";
                refresdedFF();	
            }
            else{
                $("authoriza_ad").style.visibility = "visible";
                $("ca_pic_ad").style.visibility = "visible";
                refresdedAuth();
            }
        }

        if(CA.getCAType == "IRDETO_V3"){//爱迪德CA
            var str = E.CA_OBJ.getCAMessageTextById(eventObj.args.modifiers);
            iPanel.debug("play_CA_MESSAGE_OPEN_str="+str);
            if(str.substr(0,5) == "E16-4"){//未授权广告
                L_ad_status = true;
                if (checkFF()){//付费频道
                    $("pay_txt_ad").style.visibility = "visible";
                    $("ca_pic_ad").style.visibility = "visible";
                    refresdedFF();	
                }
                else{
                    $("authoriza_ad").style.visibility = "visible";
                    $("ca_pic_ad").style.visibility = "visible";
                    refresdedAuth();
                }
            }
            /*else if(str.substr(0,5) == "---"){//付费广告 待定
                L_ad_status = true;
                $("pay_txt_ad").style.visibility = "visible";
                $("ca_pic_ad").style.visibility = "visible";
                refresdedFF();					
            }
            if(str.substr(0,5) == "---"){//应急广播 待定

            }
            else{
                DVB.clearVideoLevel(1);
            }*/
        }
        checkNotes();
    }
}

//CA_MESSAGE_CLOSE
function caMessageClose() {
    iPanel.setGlobalVar("ca_id",-1);
    L_ad_status = false;
    $("pay_txt_ad").style.visibility = "hidden";
    $("ca_pic_ad").style.visibility = "hidden";
    $("authoriza_ad").style.visibility = "hidden";
    if (cardIdShow){
        hide_ca_cardId();
    }
    $("L_tips").style.visibility = "hidden";
    $("L_tips").innerText = " ";
    checkNotes();
}

//显示指纹
function caFingerprintOpen() {
    var ca_message_id = eventObj.args.modifiers;
    var ca_message = iPanel.misc.getMessageById(parseInt(ca_message_id));
    var timer	= ca_message.timeout;
    var labelArr = ca_message.labels;
    var cardId = "";
    for(var i = 0; i < labelArr.length; i++) {
        cardId += labelArr[i].caption;
    }
    iPanel.debug("play.html cardId = " + cardId);
    //产生一个随机值,用来标示Finger的x 坐标
    var offset_x = Math.random();	
    offset_x = Math.round(offset_x * 1000);
    //产生一个随机值,用来标示Finger的y 坐标
    var offset_y = Math.random();	
    offset_y = Math.round(offset_y * 680);
    
    iPanel.debug("play.html x_offset = " + offset_x);
    iPanel.debug("play.html y_offset = " + offset_y);
    $("cardId").style.left = offset_x;
    $("cardId").style.top = offset_y;
    $("cardId").innerText = cardId;
    $("cardId").style.visibility = "visible";
    clearTimeout(fingerTimer);
    fingerTimer = setTimeout(function(){
        $("cardId").style.visibility = "hidden";
    },timer);
}

//关闭指纹
function caFingerprintClose() {
    clearTimeout(fingerTimer);
    $("cardId").style.visibility = "hidden";
}

//CA_OSD_OPEN
function caOsdOpen() {    
    var caMessage = iPanel.misc.getMessageById(parseInt(eventObj.args.modifiers));
    if (typeof(caMessage) != "undefined") {
        labels = caMessage.labels;
        show_osd();
    }
}

//插入智能卡
function insertCaSmartcard() {
    iPanel.setGlobalVar("smartcard_in","1");
    var isCurrCACard = CA.card.isCurrCACard; //判断是否是本卡
    iPanel.debug("play_eventHandler_isCurrCACard==" + isCurrCACard);

    if(typeof(currChannel)!="undefined" && 
    (!(user.passwordEnable && currChannel.lock) || (currRecord == currChannel.userChannel && user.passwordEnable && currChannel.lock)) && isCurrCACard == 1 ){
        var service = currChannel.getService();
        DVB.playAV(service.frequency, service.serviceId); 
    }
    checkNotes();
    return 0;
}

//拔出智能卡
function removeCaSmartcard() {
    iPanel.setGlobalVar("smartcard_in","0");
    DVB.stopAV(0);
    checkNotes();

    if (L_ad_status && cardIdShow){
        hide_ca_cardId();
    }
    return 0;
}

//打开频道
function openChannel(num,type){
	if(pfShow) hidePfBar();
	var tmp_channel = user.channels.getChannelByNum(num,type);
	
	if (typeof(tmp_channel) != "undefined" && currChannel.userChannel == tmp_channel.userChannel && currChannel.type == tmp_channel.type){
		//相同的频道，不播了
		iPanel.debug("play_openChannel_no");
		//模拟发channel_open消息
		setTimeout("iPanel.sendSimulateEvent(256, 8300,0)",500)
	}
	else{
		play.openChannelByNum(num,0,type);
	}
}

//频道+-
function changeChannel(num){
	if(pfShow) hidePfBar();
	if(num > 0) play.channelUp();
	else play.channelDown();
}

//DVB错误，收视率 
//0 无信号/锁屏失败 
//1 智能卡未插入 
//2 智能卡未匹配 
//3 智能卡读写错误。  
function ioctlWirte(type){
	iPanel.debug("play.html -> ioctlWirte() -> type =" + type);
	var errorCode = ["20001","20002","20003","20004"];
	var s = currChannel.getService();
	var tsid = s.TSId;
	var severid = s.serviceId;
	var freq = s.frequency;
	var ioctlWrite_content = "0x20;"+E.get_ca_id()+";"+E.stb_id+";" + errorCode[type] + ";" + tsid + ";" + severid + ";" + freq;
	iPanel.debug("play.html -> ioctlWirte() -> ioctlWrite_content = " + ioctlWrite_content);
	iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
}

//检测提示信息
//包括无节目，无信号，锁定，CA提示
function checkNotes(){
    return true;
    /*
	if(curr_channelList.length == 0) {//无节目
		if(isLock) hideLock();
		show(titleTxt[lang][6]);
		DVB.clearVideoLevel(1);
		//DVB.restartAV(0);//重启解码器
		iPanel.debug("play_checkNotes1");
	}else{
		var cable_connect_success = iPanel.getGlobalVar("cable_connect_success");
		if(typeof cable_connect_success == "number" && cable_connect_success == 0){//无信号
			if(isLock) hideLock();
			show(titleTxt[lang][7]);
			DVB.clearVideoLevel(1);
			//DVB.restartAV(0);//重启解码器
			iPanel.debug("play_checkNotes2");
			ioctlWirte(0);//收视率 ，无信号。
		}else{
			var smartcard_in = iPanel.getGlobalVar("smartcard_in");
			
			if (smartcard_in == "0"){//智能卡没插上,提示
				show(titleTxt[lang][9]);
				//DVB.clearVideoLevel(1);
				iPanel.debug("play_checkNotes3");
				
				L_ad_status = false;
				$("pay_txt_ad").style.visibility = "hidden";
				$("ca_pic_ad").style.visibility = "hidden";
				$("authoriza_ad").style.visibility = "hidden";

				if (cardIdShow){
					hide_ca_cardId();
				}
				$("L_tips").style.visibility = "hidden";
				$("L_tips").innerText = " ";
				ioctlWirte(1);//收视率，智能卡未插入。
			}
			else{
				var isCurrCACard = CA.card.isCurrCACard; //判断是否是本卡
				iPanel.debug("play_checkNotes_isCurrCACard==" + isCurrCACard);
				if (isCurrCACard != 1){
					show("无法识别卡,请重新插入或咨询客服96566");
				//	DVB.clearVideoLevel(1);
					iPanel.debug("play_checkNotes4");
					
					L_ad_status = false;
					$("pay_txt_ad").style.visibility = "hidden";
					$("ca_pic_ad").style.visibility = "hidden";
					$("authoriza_ad").style.visibility = "hidden";
					if (cardIdShow){
						hide_ca_cardId();
					}
					$("L_tips").style.visibility = "hidden";
					$("L_tips").innerText = " ";
					ioctlWirte(2);//收视率，智能卡未匹配。
				}else if(user.passwordEnable && currChannel.lock && currRecord != currChannel.userChannel){//频道被锁定了...
					currRecord = -1;
					if(isShowMsg) hide();
					showLock();
                    //DVB.clearVideoLevel(1);
					 iPanel.debug("play_checkNotes5");
					
					L_ad_status = false;
					$("pay_txt_ad").style.visibility = "hidden";
					$("ca_pic_ad").style.visibility = "hidden";
					$("authoriza_ad").style.visibility = "hidden";
					if (cardIdShow){
						hide_ca_cardId();
					}
					$("L_tips").style.visibility = "hidden";
					$("L_tips").innerText = " ";
				}else{	
					if(isLock) hideLock();
					if(currRecord != currChannel.userChannel) currRecord = -1;
                    if (osd_flag == 1) {
						show(osd_content);
						$("tips_exit").style.visibility = "visible";
					}else{
						 var ca_id = iPanel.getGlobalVar("ca_id");
						 if(typeof(ca_id) != "undefined" && ca_id != "" && ca_id != -1){

							if (L_ad_status){
								$("L_tips").style.visibility = "visible";

								if(CA.getCAType == "IRDETO_V3"){//爱迪德CA
									var str = E.CA_OBJ.getCAMessageTextById(ca_id);
									if(str.substr(0,5) == "E16-4"){//未授权
										$("L_tips").innerHTML = "E16-4 :本节目未订购或欠费停机!"
									}
									else{
										$("L_tips").innerHTML = E.CA_OBJ.getCAMessageTextById(ca_id);
									}
								}
								else $("L_tips").innerHTML = E.CA_OBJ.getCAMessageTextById(ca_id);

								if(isShowMsg) hide();
							}
							else {
								$("L_tips").style.visibility = "hidden";
								$("L_tips").innerText = " ";
								//show(E.CA_OBJ.getCAMessageTextById(ca_id));

								if(CA.getCAType == "IRDETO_V3"){//爱迪德CA
									var str = E.CA_OBJ.getCAMessageTextById(ca_id);
									if(str.substr(0,5) == "E16-4"){//未授权
										show("E16-4 :本节目未订购或欠费停机!")
									}
									else{
										show(E.CA_OBJ.getCAMessageTextById(ca_id));
									}
								}
								else show(E.CA_OBJ.getCAMessageTextById(ca_id));
							}
							//show(E.CA_OBJ.getCAMessageTextById(ca_id));

							var currChannel_name = iPanel.misc.getUserCharsetStr(currChannel.name,"gb2312");

							var ioctlWrite_content = "0x16;"+E.get_ca_id()+";"+E.stb_id+";"+currChannel.getService().serviceId+";"+currChannel.getService().TSId + ";"+currChannel.getService().frequency+";"+currChannel_name+";"+E.CA_OBJ.getCAMessageTextById(ca_id);

							iPanel.debug("play.html -> show_ca -> ioctlWrite = " + ioctlWrite_content);
							iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);

						 }else{
							if(isShowMsg) hide();
						 }
					 }
				}
			}
		}
	}
    */
}

//显示时间
function showDate(){
	if(data_timeout)clearTimeout(data_timeout);
	var curr_time = E.util.date.format(new Date(),"hh:mm:ss");
	$("curr_time").innerText = curr_time;
	$("curr_time_spec").innerText = curr_time;
	data_timeout = setTimeout("showDate()" , 1000);
}

//弹出信息提示
function showMsg(txt, autoHide){
	isShowMsg = true;
	$("tips").style.opacity = 1;
    $("tips_title").innerHTML = titleTxt[lang][3];
	$("tips_content").innerHTML = txt;
    autoHide = true;
	if(autoHide){
		clearTimeout(showMsgTimer);
		showMsgTimer = setTimeout("hideMsg();",3000);
	}
}

//隐藏信息提示
function hideMsg(){
	clearTimeout(showMsgTimer);
	$("tips").style.opacity = 0;
    $("tips_title").innerHTML = "";
	$("tips_content").innerText = "";
	isShowMsg = false;
}

//改变声道
function changeMode(num){
	var val = media.sound.mode;
	for(var i = 0; i < audioMode.length; i++){
		if(audioMode[i].type == val){
			var n = (i + num) % audioMode.length;
			media.sound.mode = audioMode[n].type;
			$("mode_img").src = "/system/"+audioMode[n].pic;
			break;
		}
	}
	$("sound_mode").style.opacity = 1;
	clearTimeout(soundModeTimer);
	soundModeTimer = setTimeout(function(){
		$('sound_mode').style.opacity = 0;
		//隐藏的时候上传广告收视率
		ioctlWriteUdc();
	},2000);
}

debugs = [];
iPanel.debug = function(msg) {
    if(debugs.length >= 3) {
        debugs.shift();
    }
    debugs.push(msg); 
    $("debugInfo").innerHTML = debugs.join("<br>");
}

String.prototype.Trim = function() {
	return this.replace(/^(\#|\*)*|(\#|\*)*$/g,"");
}

String.prototype.add = function(max){
	if(this.length >= max) return this;
	var str = "";
	for(var i = 0; i < max - this.length; i++){
		str += "0";
	}
	return str + this;
}