function show_ca_cardId(){
	cardIdShow = true;
	$("cardId_tips").style.visibility = "visible";
	var cardId = iPanel.misc.getUserCharsetStr(CA.card.cardId,"gb2312");
	var cardId_number = cardId.length;
	var show_cardId = "";
	while (cardId_number > 0){
		var id = cardId.substring(cardId_number-1,cardId_number);
		if (!isNaN(parseInt(id))){
			show_cardId = id + show_cardId;
		}
		if (show_cardId.length == 11)break;
		cardId_number--;
	}
	$("ca_cardId").innerText = show_cardId;
}

function hide_ca_cardId(){
	cardIdShow = false;
	$("cardId_tips").style.visibility = "hidden";
}

/*针对艾迪德的CA,授权消息发出来后需要判别是否是付费的频道,如果是则显示付费的广告*/
var ff_channelList = user.channels.bouquetSortByRandom(E.fufei_bouquetId);
function checkFF(){
	for (var i=0; i<ff_channelList.length; i++){
		if (ff_channelList[i].userChannel == currChannel.userChannel && ff_channelList[i].type == currChannel.type){
			iPanel.debug('play_checkFF_1');
			return 1;
		}
	}
	iPanel.debug('play_checkFF_0');
	return 0;
}