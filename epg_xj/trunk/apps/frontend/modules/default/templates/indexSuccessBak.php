<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>新疆广电 智能电视节目导航</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="page-view-size" content="1280*720" />
<link type="text/css" rel="stylesheet" href="/css/css.css"/>
<link type="text/css" rel="stylesheet" href="/css/add.css"/>
<script type="text/javascript" src="/js/ad.js"></script>
<script type="text/javascript" src="/js/ca.js"></script>
<script type="text/javascript" src="/js/channellist.js"></script>
<script type="text/javascript" src="/js/email.js"></script>
<script type="text/javascript" src="/js/lock.js"></script>
<script type="text/javascript" src="/js/osd.js"></script>
<script type="text/javascript" src="/js/pf.js"></script>
<script type="text/javascript" src="/js/language.js"></script>
<script type="text/javascript" src="/js/public.js"></script>
<script type="text/javascript">
//href
var href = window.location.href ;
//弹出提示信息Timer
var showMsgTimer = -1; 
//是否弹出信息状态
var isShowMsg = false;	        
//当前频道
var currChannel = null;
//PF进度条时间
var pfBarTime = 5000;
//PF进度条定时对象		 
var pfBarTimeout = null;
//PF是否处于显示状态 
var pfShow = false;	
//PF进度条的最大长度。		 
var maxProgress = 420;		 
//是否处于锁定状态。
var isLock = false;
//用户输入的解锁密码	 
var lock_password = "";	
//锁定Timer 
var lockTimer = null;
//notFoundTimer
var notFoundTimer = null; 
//当前的频道列表
var curr_channelList = [];   
//当前的记录
var currRecord = -1;
//osd的信息的承载
var labels;   
//邮件内容是否显示中               
var email_isShowMsg = 0; 
//init标识
var init_flag = 1;
//当前频道类型 视频
var currType = 1;	
//频道列表,主要用于弹出的channelList的列表
var channelList = [];
//频道列表Timer
var channelListTimer = null;
//频道列表显示标识
var channelListShow = false;
var channelListObj = null;
//频道列表滚动Timer
var chanListMarqueeTimer = -1;
//指纹Timer
var fingerTimer = null;
//反L广告的状态，
//如果反L广告显示，则F3可以显示卡号，并且提示在左上角
var L_ad_status = false;
//OSD标识
var osd_flag = 0;
//OSD内容
var osd_content = "";
//当前时间
var data_timeout = null;
//CA显示标识
var cardIdShow = false;
//PF显示标识,
//""为默认的，"spec"为特殊的pf条
var curr_pf = "";
//声道的Timer
var soundModeTimer = -1;
//声道图标
var audioMode = [
	{type:"stereo",pic:"midd_sd.png"},{type:"left",pic:"left_sd.png"},{type:"right",pic:"right_sd.png"}
];
//频道图标
var channel_num = [
    's_num0.png',	
    's_num1.png',	
    's_num2.png',
    's_num3.png',	
    's_num4.png',	
    's_num5.png',	
    's_num6.png',	
    's_num7.png',	
    's_num8.png',	
    's_num9.png'	
];


//切台过程中需要保留最后一帧
DVB.keepAVLastFrame = 1;
iPanel.eventFrame.initPage(window);
//频道列表Widget
var channelWidget = iPanel.pageWidgets.getByName("channelWidget");
//创建play对象
var play = new E.play();
//延迟多少S打开视频	    
play.openChannelTimer = 600;	

//页面初始化
function init(){    
    //初始化频道列表
	play.initChannelList(1);			
	curr_channelList = user.channels.getVideoList();
	//获取当前频道
	if (href.split("?").length == 2){
        var tmp_num = parseInt(href.split("?")[1]);
		currChannel = user.channels.getChannelByNum(tmp_num,1);
	}else{
		currChannel = play.getOffChannel(1);
	}
	if(typeof currChannel == "undefined"){
		iPanel.debug("play_currChannel = undefined");
		if(curr_channelList.length == 0){ 
            return;
		}else{	
			currChannel = curr_channelList[0];
		}
	}
    //初始化频道下标
	play.initCurrChannelPos(currChannel);	
    
	for(var i = 0; i < 3; i++) {
		$("lock_info_" + i).innerText = titleTxt[lang][i];
	}

	//智能卡插拔的判断,智能卡插上则播放，否则不播放
	var smartcard_in = iPanel.getGlobalVar("smartcard_in");
    
    //判断是否是本卡
	var isCurrCACard = CA.card.isCurrCACard; 
    //智能卡插上,并是本盒子卡,才进去播放
	if (smartcard_in != "0" && isCurrCACard == 1){
		if(!(user.passwordEnable && currChannel.lock)){
			var currService = currChannel.getService();
			DVB.playAV(currService.frequency, currService.serviceId);
		}else{
			DVB.stopAV(0);
		}
	}else{
		DVB.stopAV(0);
	}

	//延迟发channel_open消息
	setTimeout("iPanel.sendSimulateEvent(256, 8300, 0)",800);

	iPanel.enterMode("watchTV", 0x01);
	media.video.fullScreen();

	check_osd();

	//按key_menu进入
	if (E.form_key_menu){
		E.form_key_menu = false;
		//需要显示L型菜单
		if (channelWidget){
			channelWidget.initPage();

			if(pfShow) hidePfBar();
			var volumeWidget = iPanel.pageWidgets.getByName("volumeWidget");
			if(media.sound.isMute == 0) volumeWidget.minimize("delete-surface");
		}
	}

	check_message();
	showDate();
	iPanel.debug("play_init");
	initAD();
}

//按键事件
function eventHandler(eventObj, type){ 
	switch(eventObj.code){
		case "KEY_VOLUME_UP":
			keyVolumeUpEvent();
			break;
		case "KEY_VOLUME_DOWN":			
            keyVolumeDownEvent();
			break;
		case "KEY_LEFT":
			keyLeftEvent();
			break;
		case "KEY_RIGHT":
			keyRightEvent();
			break;
		case "KEY_UP":
            keyUpEvent();
			break;
		case "KEY_CHANNEL_UP":
			keyUpEvent();
			break;
		case "KEY_DOWN":
            keyDownEvent();
			break;
		case "KEY_CHANNEL_DOWN":		
            keyDownEvent();
			break;		
		case "KEY_PAGE_UP":			
            keyPageUpEvent();
            break;
		case "KEY_PAGE_DOWN":		
            keyPageDownEvent();
            break;
		case "KEY_SELECT":
            keySelectEvent();
			break;
		case "KEY_BACK":			
            keyBackEvent();
			break;
		case "KEY_AUDIO_MODE":			
            keyAudioEvent();
			break;		
		case "KEY_EXIT":
			keyExitEvent();
            break;
		case "KEY_EPG":			
            keyEpgEvent();
            break;
		case "KEY_MENU":
		case "KEY_RED":			
            keyMenuEvent();
            break;
		case "KEY_HOMEPAGE":
			keyHomepageEvent();
			break;
		case "KEY_NUMERIC":
			keyNumericEvent(eventObj, type);
			break;
		case "KEY_MAIL":
			showEmail();
			break;
		case "KEY_F2":
			playTimeshit();
            break;
		case "KEY_F3":
            showAdStatus();			
            break;	
		case "KEY_F4":
            keyFavoriteEvent();			
            break;
		case "KEY_FAVORITE":
            keyFavoriteEvent();
			break;
		case "DVB_CHANNEL_OPEN":		
            keyDvbChannelOpen();			
			break;
		case "DVB_CHANNEL_CLOSE":
			keyDvbChannelClose();
			break;
		case "DVB_LOCKED_CHNANEL":
			return 0;
			break;
		case "DVB_EIT_PF_READY": 
		case "DVB_EIT_TIMEOUT":	
            keyPfReadyEvent();			
			break;
		case "CHANNEL_LIST_REFRESH":
			return 0;
			break;
		case "CHANNEL_NOT_FOUND":
			channelNoFound();
			break;
		case "DVB_CABLE_CONNECT_FAILED":
			cableConnect(0);
			break;
		case "DVB_CABLE_CONNECT_SUCCESS":
			cableConnect(1);
			break;
		case "CA_MESSAGE_OPEN":
			caMessageOpen();
			break;
		case "CA_MESSAGE_CLOSE":
			caMessageClose();
			break;
		case "CA_FINGERPRINT_OPEN":
            caFingerprintOpen();			
			break;
		case "CA_FINGERPRINT_CLOSE":
			caFingerprintClose();
			break;
		case "CA_OSD_UP_OPEN":
            caOsdOpen();
			break;
		case "CA_OSD_UP_CLOSE":
			break;
		case "CA_OSD_BOTTOM_OPEN":
			caOsdOpen();			
			break;
		case "CA_OSD_BOTTOM_CLOSE":			
            break;
		case "CA_MAIL_NEW_OPEN":
			showEmail();
			return 0;
			break;
		case "CA_MAIL_FULL_OPEN":
			return 0;
			break;
		case "CA_MAIL_CLOSE":
			return 0;
			break;
		case "CA_INSERT_SMARTCARD":
			insertCaSmartcard();
			break;
		case "CA_EVULSION_SMARTCARD":
			removeCaSmartcard();
			break;
		case "EIS_DVB_PUSH_MAIL_NOTIFY":
			check_message();
			break;
		case "DVB_TUNE_FAILED":
			ioctlWirte(0);
			break;        
		case "KEY_MUTE":
			keyMuteEvent();
            break;  
	}
	return eventObj.args.type;
}

//离开页面
function exitPage(){
    /*
	if(play.channelNumShowFlag) play.hideChannelNubmer();
	clearTimeout(pfBarTimeout);
	clearTimeout(showMsgTimer);

	iPanel.debug("play_exitPage_channelWidget.isShowMsg" + channelWidget.isShowMsg);
	if (channelWidget.isShowMsg)channelWidget.exitPage();

	if(media.sound.isMute == 0){
		var volumeWidget = iPanel.pageWidgets.getByName("volumeWidget");
		volumeWidget.minimize("delete-surface");
	}
    
    //离开时停止视频播放
	DVB.keepAVLastFrame = 0;
	DVB.stopAV(0);
    //清除最后一帧
	//DVB.clearVideoLevel(1);
    
	var service = currChannel.getService();
	var program_name = "undefined";
	if(typeof service.presentProgram != "undefined"){
		program_name = iPanel.misc.getUserCharsetStr(service.presentProgram.name,"gb2312");
	}
	var currChannel_name = iPanel.misc.getUserCharsetStr(currChannel.name,"gb2312");
	var ioctlWrite_content = "0x15;"+E.get_ca_id()+";"+E.stb_id+";"+currChannel.getService().serviceId+";"+currChannel.getService().TSId + ";"+currChannel.getService().frequency+";"+currChannel_name+";"+program_name+";"+currChannel.getService().isCA+";"+DVB.SI.currentDelivery.signalStrength+";"+DVB.SI.currentDelivery.signalQuality+";0";
	iPanel.debug("play.html -> exitPage() -> ioctlWrite = " + ioctlWrite_content);
	iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
    */
}

//音量加
function keyVolumeUpEvent() {
    if (channelWidget.isShow)channelWidget.exitPage();
    if(pfShow) hidePfBar();
    E.changeVolume(1);
    return 0;
}

//音量减
function keyVolumeDownEvent() {
    if (channelWidget.isShow)channelWidget.exitPage();
	if(pfShow) hidePfBar();
	E.changeVolume(-1);
    return 0;
}

//向左
function keyLeftEvent() {
    if (channelListShow){
		change_list(-1);
	}else{
        if(pfShow) hidePfBar();
        E.changeVolume(-1);
	}
	return 0;
}

//向右
function keyRightEvent() {
    if (channelListShow){
				change_list(1);
			}
			else{
				if(pfShow) hidePfBar();
				E.changeVolume(1);
			}
			return 0;
}

//向上
function keyUpEvent() {
    if(channelListShow) {
        clearTimeout(channelListTimer);
        channelListTimer = setTimeout("hideChannelList()",5000);
    } else {
        clearTimeout(lockTimer);
        changeChannel(1);
        return 0;
    }
}

//向下
function keyDownEvent() {  
    if(channelListShow) {
        clearTimeout(channelListTimer);
        channelListTimer = setTimeout("hideChannelList()",5000);
    }else {
        clearTimeout(lockTimer);
        changeChannel(-1);
        return 0;
    }
}

//上翻页
function keyPageUpEvent() {
    if (channelListShow && channelList.length > 0){
        clearTimeout(channelListTimer);
        channelListObj.changePage(-1);
        channelListTimer = setTimeout("hideChannelList()",5000);
    }
}

//下翻页
function keyPageDownEvent() {
    if (channelListShow  && channelList.length > 0){
        clearTimeout(channelListTimer);
        channelListObj.changePage(1);
        channelListTimer = setTimeout("hideChannelList()",5000);
    }
}

//返回键
function keyBackEvent() {
    //if(isLock) delLockNum();
	//else play.playLastChannel();
	//openChannel(55,1);
    if(channelListShow) { 
        hideChannelList();
    } else {
        iPanel.mainFrame.location.href = "http://xjepg.test.cedock.net";
    }
}

//退出键
function keyExitEvent() {
    if (osd_flag == 1){
        //按退出后，再次进入则不再弹出OSD
        E.downosd_flag = -1;
        E.uposd_flag = -1;
        osd_flag = 0;
        osd_content = "";
        $("tips_exit").style.visibility = "hidden";
        check_notes();
    } else if (email_isShow){
        email_isShow = 0;
        $("email_content").innerHTML = " ";
        $("email_tips").style.visibility = "hidden";
    } else{
        iPanel.eventFrame.ip_load_index();
    }
}

//菜单键
function keyMenuEvent() {
    if (channelWidget) {
        channelWidget.initPage();
        if(pfShow) hidePfBar();
        var volumeWidget = iPanel.pageWidgets.getByName("volumeWidget");
        if(media.sound.isMute == 0) volumeWidget.minimize("delete-surface");
    }	
    return 0;
}

//确定键
function keySelectEvent() {
    if(true) {
        var AjaxObj = new E.AJAX_OBJ('http://192.168.10.70/default/select', 
            function(xmlHttp) {
                $("channel_list").innerHTML = E.trim(xmlHttp.responseText);
                $("channel_list").style.left= "885px";
                $("channel_list").style.opacity = 1;
                channelListShow = true;
                clearTimeout(channelListTimer);
                channelListTimer = setTimeout("hideChannelList()",3000);
            },
            function(xmlHttp){
                $("channel_list").innerHTML = "加载失败";
            }
		); 
        AjaxObj.requestData();	
    }
} 

//数字键
function keyNumericEvent(eventObj, type) {
    if (push_mail_visible){
        if (eventObj.args.value == 1)iPanel.mainFrame.location.href = "ui://emailList.htm?1";
    }
    else if(isLock){
        addLockNum(eventObj.args.value);
    }
    else{
        play.getInputChannelNumber(eventObj.args.value,1);
    }
}

//收藏
function keyFavEvent() {
    curr_pf = "";
    for (var i=0; i<special_channel.length; i++){
        if (curr_name == special_channel[i].name){curr_pf = "_spec";break;}
    }

    if (currChannel.favorite == 1){
        currChannel.favorite = 0;
        $("love_icon"+curr_pf).src = " ";
    }
    else if (currChannel.favorite == 0){
        currChannel.favorite = 1;
        $("love_icon"+curr_pf).src = "system/v_ico1.png";
    }
    user.channels.save();
    var ioctlWrite_content = "0x0f;"+E.get_ca_id()+";"+E.stb_id+";F4";
    iPanel.debug("play.html -> KEY_F4 -> ioctlWrite = " + ioctlWrite_content);
    iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
}

//DvbChannelOpen
function keyDvbChannelOpen() {
    clearTimeout(fingerTimer);
    $("cardId").style.visibility = "hidden";
    if (play.currChannel){
        currChannel = play.currChannel;
    }
    clearPF();
    //初始化频道号
    play.initChannelNum(currChannel.userChannel);
    //显示频道号
    play.showChannelNumber();	
    play.initCurrChannelPos(currChannel);
    iPanel.debug("play_channel_open_ enable = " + user.passwordEnable + ",  lock = " + currChannel.lock + " currRecord=" + currRecord);
    preparePFPrograms();    
    var ca_id = iPanel.getGlobalVar("ca_id");
    if (init_flag && typeof(ca_id) != "undefined" && ca_id != "" && ca_id != -1 && E.CA_OBJ.getCaMessageTypeById(ca_id) == 201){
        //这里需要显示付费广告
        //$("ca_video_ad").style.visibility = "visible";
        L_ad_status = true;
        $("pay_txt_ad").style.visibility = "visible";
        $("ca_pic_ad").style.visibility = "visible";
        refresdedFF();
    } else if(init_flag && typeof(ca_id) != "undefined" && ca_id != "" && ca_id != -1 && E.CA_OBJ.getCaMessageTypeById(ca_id) == 110){
        //这里需要显示未授权广告
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
        else {
            $("authoriza_ad").style.visibility = "visible";
            $("ca_pic_ad").style.visibility = "visible";
            refresdedAuth();
        }
    } else if (init_flag && typeof(ca_id) != "undefined" && ca_id != "" && ca_id != -1) {
        if(CA.getCAType == "IRDETO_V3"){//爱迪德CA
            var str = E.CA_OBJ.getCAMessageTextById(ca_id);
            if(str.substr(0,5) == "E16-4"){//未授权广告
                L_ad_status = true;
                //付费频道
                if (checkFF()){
                    $("pay_txt_ad").style.visibility = "visible";
                    $("ca_pic_ad").style.visibility = "visible";
                    refresdedFF();	
                } else {
                    $("authoriza_ad").style.visibility = "visible";
                    $("ca_pic_ad").style.visibility = "visible";
                    refresdedAuth();
                }
            }
        }
    }
    init_flag = 0;    
    var curr_name = iPanel.misc.getUserCharsetStr(currChannel.name,"gb2312");
    iPanel.debug("play_channel_open_curr_name = " + curr_name);
    var i = 0;
    for (i=0; i<special_channel.length; i++){
        if (special_channel[i].name == curr_name){
            iPanel.debug("play_channel_open_refreshAD");
            //先换成默认的图片，否则会出现空白的情况呢....
            $("play_ad_6").src = special_channel[i].pic;
            E.play_ad_6.refreshAD(currChannel.getService());//特殊频道广告刷新
            break;
        }
    }
    if(i == special_channel.length){
        refreshed();//普通广告刷新
    }
    showPfBar();
    check_notes();
    var channelNum = (""+currChannel.userChannel).add(3);
    hardware.STB.frontPanel(2,channelNum,0,1,100);
    return 0;
}

//DvbChannelClose
function keyDvbChannelClose() {
    ad_pf_flag = false;
    ad_pf_userChannel = -1;

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
    check_notes();
    
    var service = currChannel.getService();
    var program_name = "undefined";
    if(typeof service.presentProgram != "undefined"){
        program_name = iPanel.misc.getUserCharsetStr(service.presentProgram.name,"gb2312");
    }
    var currChannel_name = iPanel.misc.getUserCharsetStr(currChannel.name,"gb2312");
    var ioctlWrite_content = "0x15;"+E.get_ca_id()+";"+E.stb_id+";"+currChannel.getService().serviceId+";"+currChannel.getService().TSId + ";"+currChannel.getService().frequency+";"+currChannel_name+";"+program_name+";"+currChannel.getService().isCA+";"+DVB.SI.currentDelivery.signalStrength+";"+DVB.SI.currentDelivery.signalQuality+";0";
    iPanel.debug("play.html -> DVB_CHANNEL_CLOSE -> ioctlWrite = " + ioctlWrite_content);
    iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
    return 0;
}

//PF_READY
function keyPfReadyEvent() {
    getPFPrograms();
    var service = currChannel.getService();
    var program_name = "undefined";
    if(typeof service.presentProgram != "undefined"){
        program_name = iPanel.misc.getUserCharsetStr(service.presentProgram.name,"gb2312");
    }
    var currChannel_name = iPanel.misc.getUserCharsetStr(currChannel.name,"gb2312");
    var ioctlWrite_content = "0x05;"+E.get_ca_id()+";"+E.stb_id+";"+currChannel.getService().serviceId+";"+currChannel.getService().TSId + ";"+currChannel.getService().frequency+";"+currChannel_name+";"+program_name+";"+currChannel.getService().isCA+";"+DVB.SI.currentDelivery.signalStrength+";"+DVB.SI.currentDelivery.signalQuality+";0";
    iPanel.debug("play.html -> DVB_EIT_PF_READY -> ioctlWrite_content = " + ioctlWrite_content);
    iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
    return 0;
}
</script>
</head>

<body bgcolor="transparent" onLoad="init()" onUnload="exitPage()">
	<div class="wapper" id="wapper">
    	<div id="menu" class="menu hide"></div>        
        <div class="channel_ml hide"></div>        
        <div class="channelbg hide"></div>       
        <div class="look today look3 hide"></div>
        <div id="tips" class="tips hide">
        	<dl>
            	<dt id="tips_title"></dt>
                <dd id="tips_content"></dd>
            </dl>
        </div>
    </div>    
    <div id="channel_list" class="userctrlbg hide"></div>
    
    <!--pf 条-->
    <div style="position:absolute; left:40px; top:722px; width:1200px; height:155px; background:url(ui://system/pf_bar.png) no-repeat;-webkit-transition-duration:300ms;opacity:0;" id='navigator'>
	<div style="position:absolute; left:213px; top:13px; width:140px; height:64px;" id="pf_channel_num"><img src="ui://system/s_num0.png" width="46" height="64"/><img src="ui://system/s_num1.png" width="46" height="64"/><img src="ui://system/s_num2.png" width="46" height="64"/></div>
	<div style="position:absolute; left:364px; top:38px; width: 64px;">
		<table><tr><td><img src="" id='love_icon' width="28" height="24"/><img src="" id='lock_icon' width="23" height="28"/></td></tr></table>
    </div>  
	<div style="position:absolute; left:215px; top:82px; width:222px; height:50px; line-height:50px; font-size:32px; color:#ffffff;" id='channel_name'></div>
	<div style="position:absolute; left:440px; top:0px; width:452px; height:65px; font-size:28px; color:#ffffff;">
		<table width="453" cellpadding="0px" cellspacing="0px" style="font-size:26px; color:#ffffff;" height="100%">
		<tr>
		  	<td width="132">正在播放：</td>
			<td id='curr_programe' width="268"></td>
		</tr>
	  </table>
    </div>	
	<div style="position:absolute; left:454px; top:69px; width:419px; height:7px; background:url(ui://system/bar2.png) no-repeat" id='curr_progress'></div>
	<img style="position:absolute; left:380px; top:9px; visibility:hidden" id='isTstv' width='28px' height='24px' src='ui://system/tstv.png'></img>
    <div style="position:absolute; left:440px; top:88px; width:452px; height:46px; line-height:46px; font-size:26px; color:#ffffff;" id='f_program'></div>
	<div style="position:absolute; left:25px; top:5px; width:152px; height:142px;">
        <div id='play_ad_1' style="position:absolute; left:2px; top:-2px; font-size:30px; color:#ffffff; white-space:nowrap; text-overflow:ellipsis; overflow:hidden; line-height:50px; width:144px; height:50px" align="center">天山云</div>
		<div style="position:absolute; left:7px;top:54px;font-size:26px; color:#ffffff;" align="center">提醒您时间</div>
		<div style="position:absolute; left:12px; top:98px; font-size:30px; color:#ffffff; width: 122px;" id='curr_time' align="center">08:10:10</div>
	</div>
	<div style="position:absolute; left:907px; top:4px; width:287px; height:142px;"><img src="" width="287" height="142" id='play_ad_0'/></div>
    
    <!-- 锁定提示 -->
    <div style="position:absolute;background:url(ui://system/lock_bg.png) center no-repeat; width:588px;height:298px;top:195px;left:311px;-webkit-transition-duration:300ms;opacity:0;" id="lock">
        <table height="87%" border="0" cellpadding="0" cellspacing="0" style="width:100%;height:100%;font-size:26px;">
            <tr>
                <td height="76" colspan="2" align="center" style="font-size:30px;color:white;" id="lock_info_0">请输入密码解锁频道</td>
            </tr>
            <tr>
                <td width="40%" align="right" height="97" id="lock_info_1">请输入密码：</td>
                <td id="lock_password" style="background:url(ui://system/lock_input.png) left no-repeat;padding-left:10px;">&nbsp;</td>
            </tr>
            <tr>
                <td height="43" colspan="2" align="center" id="lock_tips">&nbsp;</td>
            </tr>
            <tr>
                <td height="70px" colspan="2" style="font-size:20px;text-align:center;" id="lock_info_2">按 [确认]键解锁，[0~9]键输入，[返回]键删除</td>
            </tr>
        </table>
    </div>
    
    <!--声道-->
    <div style="position:absolute;left:746px;top:3px;width:214px;height:87px; background:url(ui://system/sd_bg.png) no-repeat;-webkit-transition-duration:300ms;
    opacity:0;" id='sound_mode'>

    <!--文字广告啊-->
    <div style="position:absolute; left:6px; top:9px; width:112px; height:30px; line-height:30px; font-size:20px; color:#ffffff; overflow:hidden; word-break:break-all;" id="play_ad_2" align="center">天山云</div>
    <div style="position:absolute; left:10px; top:48px; width:109px; height:30px; line-height:30px; font-size:20px; color:#ffffff; overflow:hidden; word-break:break-all;">提醒您声道</div>
    <div style="position:absolute; left:128px; top:0px;"><img src="ui://system/midd_sd.png" width="86" height="86" id='mode_img'/></div></div>

    <!-- 频道号 -->
    <div id="channelNumber" style="position:absolute; left:321px; top:205px; width:569px; height:264px;-webkit-transition-duration:300ms;opacity:0;z-index:9;">
        <table style="position:absolute; left:4px; top:2px; width:563px; height:264px;"><tr><td id="input" width="100%" height="100%" align="center" valign="middle"></td></tr></table>
    </div>
    
    <!--网络角标提示-->
    <div id="net_tips" style="position:absolute; left:835px; top:87px; width:211px; height:75px; z-index:3; background:url(ui://system/vod_tip_2.png) no-repeat; color:#ffffff; visibility: hidden;font-size:24px;line-height:75px" align="center">
        <table width="100%" height="100%" border="0" cellspacing="0">
            <tr>
                <td style='font-size:24px; color:#FFFFFF;' align="center" id="net_text">请插上网线</td>
            </tr>
        </table>
    </div>
    
    <!--指纹-->
    <div id="cardId" style="position:absolute;top:30px;left:0px;width:100px;height:40px;background-color:#FFFFFF;color:#000000;line-height:40px;text-align:center;visibility:hidden;font-size:24px;z-index:99"></div>

    <!--智能卡-->
    <div id="cardId_tips" style="position:absolute;top:76px;left:0px;width:212px;height:71px;background-image:url(ui://system/vod_tip_2.png);text-align:center;visibility:hidden">
        <table width="90%" height="100%" style="color:#FFFFFF;font-size:20px">
            <tr>
                <td width="28%">智能<br>卡号</td>
                <td width="72%" id="ca_cardId" style="font-size:24px"> </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>