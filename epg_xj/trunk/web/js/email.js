var curr_email_num = 0;

function showEmail(){
	var emailList = CA.getEmails();
	if (emailList.length > 0) {
		curr_email_num = 0;
		var curr_email = emailList[curr_email_num];

		if (curr_email.content != "undefined") {
			var email_title = iPanel.misc.getUserCharsetStr(curr_email.title,"gb2312") + curr_email.createDate;
			$("email_title").innerText = E.getStrChineseLen(email_title,45);
			var curr_content = iPanel.misc.getUserCharsetStr(curr_email.content,"gb2312");
			email_isShowMsg = 1;
			$("email_content").innerHTML = "<marquee loop=2 onfinish = hideEmail()>" + curr_content + "</marquee>"
			$("email_tips").style.visibility = "visible";
			var ioctlWrite_content = "0x11;"+E.get_ca_id()+";"+E.stb_id+";0;"+curr_email.id+";;0";
			iPanel.debug("play.html -> show_email() -> ioctlWrite = " + ioctlWrite_content);//上传阅读邮件服务
			iPanel.TR069.ioctlWrite("udc",ioctlWrite_content);
		}
	} else {
		iPanel.debug("play_show_email_emailList.length = 0");
	}
}

function hideEmail(){
	email_isShowMsg = 0;
	$("email_tips").style.visibility = "hidden";
}

function changeEmail(__num){//切换邮件
	var emailList = CA.getEmails();
	if (emailList.length > 0){
		curr_email_num += __num;
		if (curr_email_num < 0)curr_email_num = emailList.length - 1;
		else if (curr_email_num > emailList.length - 1)curr_email_num = 0;
		var curr_email = emailList[curr_email_num];

		if (curr_email.content != "undefined") {
			var email_title = iPanel.misc.getUserCharsetStr(curr_email.title,"gb2312") + curr_email.createDate;

			$("email_title").innerText = E.getStrChineseLen(email_title,45);//135 45
			$("email_content").innerHTML = "<marquee loop=2 onfinish = hideEmail()>" + iPanel.misc.getUserCharsetStr(curr_email.content,"gb2312") + "</marquee>";
		} else {
			changeEmail(__num);
		}
	} else {
		iPanel.debug("play_changeEmail_emailList.length = 0");
		email_isShowMsg = 0;
		$("email_tips").style.visibility = "hidden";
	}
}