//如果osd的第一个字是*那么在上面显示，
//如果是#在下面显示，其他是弹出框处理
function show_osd(){
	var content = "";
	for(var i=0; i<labels.length; i++){
		content += labels[i].caption;
	}

	content = iPanel.misc.getUserCharsetStr(content,"gb2312");
	iPanel.debug("play_show_osd_"+content);
	if(content.substring(0,1) == "#"){
		document.getElementById("up_osd_text").innerHTML = "<marquee loop=3 onfinish=hideosd()>"+content.Trim()+"</marquee>";
		document.getElementById("CAUpScrollInfo").style.visibility = "visible";
	}else if(content.substring(0,1) == "*"){
		document.getElementById("down_osd_text").innerHTML = "<marquee loop=3 onfinish=hideosd()>"+content.Trim()+"</marquee>";
		document.getElementById("CAdownScrollInfo").style.visibility = "visible";
	}else{
        //以弹出框的方式显示OSD的消息
		osd_flag = 1;
		osd_content = content;
		checkNotes();
	}

	//上传OSD信息
	var ioctlWrite_content = "0x11;"+E.get_ca_id()+";"+E.stb_id+";1;0;" + content + ";0";
	iPanel.debug("play.html -> show_osd() -> ioctlWrite = " + ioctlWrite_content);//上传阅读邮件服务
	iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
}
function hideosd(){
	E.downosd_flag = -1;
	E.uposd_flag = -1;
	$("CAUpScrollInfo").style.visibility = "hidden";
	$("CAdownScrollInfo").style.visibility = "hidden";
}

function check_osd(){
	if (E.downosd_flag != -1 || E.uposd_flag != -1)
	{	
		var caMessage;
		if(E.downosd_flag != -1)caMessage = iPanel.misc.getMessageById(E.downosd_flag);
		if(E.uposd_flag != -1)caMessage = iPanel.misc.getMessageById(E.uposd_flag);
		iPanel.debug("play__check_osd_" + typeof(caMessage)) ;
		if (typeof(caMessage) != "undefined") {
			labels = caMessage.labels;
			iPanel.debug("play__show_osd_" + labels.length) ;
			show_osd();
		}
	}
}