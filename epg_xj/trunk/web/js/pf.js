/*
 * PF条的操作
 */

//显示PF条
function showPfBar(){
	if (channelWidget && channelWidget.isShowMsg){
		return;
	}
	iPanel.debug("play_pfbar_show!!!",7);
	var volumeWidget = iPanel.pageWidgets.getByName("volumeWidget");
	if(media.sound.isMute == 0) volumeWidget.minimize("delete-surface");
	showChanInfo();	
	if (curr_pf == "_spec"){
		$("navigator_spec").style.top = "522px";
		$("navigator_spec").style.opacity = 1;
		$("navigator").style.top = "720px";
		$("navigator").style.opacity = 0;
	}else {
		$("navigator").style.top = "570px";
		$("navigator").style.opacity = 1;
		$("navigator_spec").style.top = "720px";
		$("navigator_spec").style.opacity = 0;
	}
	pfShow = true;
	clearTimeout(pfBarTimeout);
	pfBarTimeout = setTimeout("hidePfBar();",pfBarTime);
}

//隐藏PF条
function hidePfBar(){    
	iPanel.debug("play_pfbar_hide_start!!!",7);
	clearTimeout(pfBarTimeout);
	$("navigator").style.top = "720px";
	$("navigator").style.opacity = 0;
	$("navigator_spec").style.top = "720px";
	$("navigator_spec").style.opacity = 0;
	pfShow = false;
	iPanel.debug("play_pfbar_hide_end!!!",7);
}

//填写频道信息
function showChanInfo(){
	var channelNum = "";
	var curr_name = iPanel.misc.getUserCharsetStr(currChannel.name,"gb2312");
	curr_pf = "";
	for (var i=0; i<special_channel.length; i++){
		if (curr_name == special_channel[i].name){curr_pf = "_spec";break;}
	}
	var __num = (""+currChannel.userChannel).add(3);
	for (var i = 0; i < __num.length ; i++) {
		var number = __num.substr(i, 1);
		channelNum += '<img src="'+channel_num[parseInt(number)]+'">';
	}
	$("pf_channel_num"+curr_pf).innerHTML = channelNum;
		
	var tmpName = $("channel_name"+curr_pf).divideString(curr_name);
	iPanel.debug("play_showChanInfo_tmpName.length = " +tmpName.length);

    //现场要求只显示6位，多的不显示了，直接截取，不显示
	$("channel_name"+curr_pf).innerText = tmpName[0];
	
	if (currChannel.hasOperation("TSTV")){
		$("isTstv"+curr_pf).style.visibility = "visible";
	} else{
		$("isTstv"+curr_pf).style.visibility = "hidden";
	}
	if (currChannel.lock){
		$("lock_icon"+curr_pf).src = "system/v_ico2.png";
	} else {
		$("lock_icon"+curr_pf).src = " ";
	}
	if (currChannel.favorite){
		$("love_icon"+curr_pf).src = "system/v_ico1.png";
	} else{
		$("love_icon"+curr_pf).src = " ";
	}

	var val = media.sound.mode;
	for(var i = 0; i < audioMode.length; i++){
		if(audioMode[i].type == val){
			$("mode_img").src = audioMode[i].pic;
			break;
		}
	}
}

//准备PF信息
function preparePFPrograms(){
	var flag = play.prepareProgram(currChannel);
	if(flag == 1) getPFPrograms();
}

//清除PF信息
function clearPF(){
	$("curr_programe").innerText = " ";
	$("f_program").innerText = " ";
	$("curr_progress").width = "5px";
	$("curr_programe_spec").innerText = " ";
	$("f_program_spec").innerText = " ";
	$("curr_progress_spec").width = "5px";
}

//填写PF信息
function getPFPrograms(){
	clearPF();
	var curr_name = iPanel.misc.getUserCharsetStr(currChannel.name,"gb2312");
	curr_pf = "";
	for (var i=0; i<special_channel.length; i++){
		if (curr_name == special_channel[i].name){curr_pf = "_spec";break;}
	}

	var service = currChannel.getService();
	var p = service.presentProgram;
	var f = service.followingProgram;
	var time = "";
	if(typeof p != "undefined"){
		$("curr_programe"+curr_pf).innerText = E.getStrChineseLen(iPanel.misc.getUserCharsetStr(p.name,"gb2312"),16) || "";
		showProgress(p);
	}
	time = "";
	if(typeof f != "undefined"){
		if(f.startTime)time += f.startTime.substring(0,5) || "";
		if(f.endTime) time += "-" + f.endTime.substring(0,5) || "";

		var content = time +" "+ iPanel.misc.getUserCharsetStr(f.name,"gb2312");

		$("f_program"+curr_pf).innerHTML = E.getStrChineseLen(content,13) || "";
	}
}

//计算 pf进度条
function showProgress(curr_prog){
	var n = play.getCurrProgramProgress(maxProgress,curr_prog);
	$("curr_progress"+curr_pf).style.width = n + "px";
}