var total_pushmail_email = 0;
var push_mail_timeout = -1;
var push_mail_visible = false;
function check_message(){
	var pushMessages = user.getDisplayPushMessages();
	iPanel.debug("play_check_message_pushMessages.length = " + pushMessages.length);

	var pushMessage;
	for(var i=0;i<pushMessages.length;i++){
		pushMessage = pushMessages[i];

		iPanel.debug("play_check_message_pushMessages.type = " + pushMessage.type + "__pushMessage.readFlag = " + pushMessage.readFlag + "__" + pushMessage.id);

		if(pushMessage.type == 3 && pushMessage.readFlag == 0){//浮动，一般用于邮件提醒
			total_pushmail_email++;
		}
		else if (pushMessage.type == 2 && pushMessage.readFlag == 0){//冒泡
			iPanel.overlayFrame.location.href = "pushMessagePopup.htm?" + pushMessage.id;
		}
		else if (pushMessage.type == 20  && pushMessage.readFlag == 0){
			$("push_mail_osd").style.visibility = "visible";
			$("push_mail_osd").innerHTML = "<marquee loop=3 onfinish = hide_push_osd()>" + iPanel.misc.getUserCharsetStr(pushMessage.content,"gb2312") + "</marquee>";
			pushMessage.readFlag = 1;
		}	
	}

	if (total_pushmail_email > 0){
		push_mail_visible = true;
		$("push_mail_email").style.visibility = "visible";
		$("push_mail_num").innerText = total_pushmail_email;
		
		clearTimeout(push_mail_timeout);
		push_mail_timeout = setTimeout("hide_push_mail()",5000);
	}
}

function hide_push_osd(){
	$("push_mail_osd").style.visibility = "hidden";
}

function hide_push_mail(){
	push_mail_visible = false;
	$("push_mail_email").style.visibility = "hidden";
}