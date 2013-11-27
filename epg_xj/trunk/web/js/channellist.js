/*确认事件处理*/
function doSelect(){
	if(isLock){
		doLock();//锁定处理
	}else if(play.inInputStatus){
		play.goChannelImmediately(1); //播放输入频道号对应的节目
	}
	else if (channelListShow){
		openChannel(channelList[channelListObj.listPos].userChannel,channelList[channelListObj.listPos].type);
		hideChannelList();
	}
	else{
		var AjaxObj = new E.AJAX_OBJ('/default/select', 
		function(xmlHttp) {
			$("wapper").innerHTML = E.trim(xmlHttp.responseText);
		},
		function(xmlHttp){
			$("wapper").innerHTML = "无天气情况";
		}
		); 
        AjaxObj.requestData();		
	}
}

/*频道列表的操作*/
var slipOpotion = {                           //设定滑动条件
	objName : "channelList_",                 //内容DIV的前缀名称
	direction : "top",
	listSize : 7,                             // 当前最大显示。（不是所有内容DIV的个数）
	rowHeight : 39,                           // 每个内容DIV的高度
	focusObj : "channelList_focus",           //焦点ID名称
	focusTop : 134,                            //焦点ID的高度
	dataLength : 0,							  //数据的长度，这里可留到new 一个对象的时候赋值。
	focusRange : [0,6],                       //控制焦点框的范围，注：不能超过listSize。如果listPos为0，那么focusRange[0] 自动设置为0
	show : showData,                          //显示数据方法，自带_row ,_pos 前面代表焦点下标，后面代表数据下标
	clear : clearData                         //清楚数据 自带_row 
};
var progress_top = 136;
var progress_bottom = 351;
//初始化频道列表
function initChannelList(){
	channelList = [];
	if (currType == 2)channelList = user.channels.getAudioList();
	else if (currType == 1){channelList = user.channels.getVideoList();}
	else if (currType == 3){channelList = user.channels.getFavoriteList(1);}
	
	var pos = 0;
	if (currType == 3)pos = 0;
	else {	
		pos = getChannelPos(currChannel.userChannel);
	}
	
	if (channelListObj != null){
		channelListObj.resetSlip();
	}
	
	clearChannelList();
	if (channelList.length == 0){
		$("channelList_focus").style.top = "134px";
		$('channelList_0').innerText = '无对应的频道!';
		return ;
	}

	slipOpotion.dataLength = channelList.length;
	setChanneListPos(pos);

	channelListObj = new E.listSlip_2D(slipOpotion);
	channelListObj.initData();
	setProgress();
}

function clearChannelList(){
	for (var i=0; i<7; i++){
		$('channelList_' + i).innerText = ' ';
	}
}
//设置滚动的焦点位置和数据位置
function setChanneListPos(pos){
	if(slipOpotion.dataLength > slipOpotion.listSize){
		slipOpotion.listPos = pos;
		if ((slipOpotion.dataLength - pos) >= slipOpotion.listSize){
			slipOpotion.focusPos = 0;
		}else{
			slipOpotion.focusPos = slipOpotion.listSize - (slipOpotion.dataLength - pos);
		}
	}else{
		slipOpotion.listPos = pos;
		slipOpotion.focusPos = pos;	
	}
}
//显示频道列表对应Pos数据
function showData(_focusPos,_dataPos){
	var name = channelList[_dataPos].name;
	name = iPanel.misc.getUserCharsetStr(name,"gb2312");
	if(E.getStrChineseLength(name) > 9){
		name = E.getStrChineseLen(name,9);
	}
	$("channelList_" + _focusPos).innerText = (""+channelList[_dataPos].userChannel).add(3) + " " + name;
}
//清除频道列表对应Pos数据
function clearData(_focusPos){
	$("channelList_" + _focusPos).innerText = " ";
}

//获取当前频道的下标位置,num为userChannel
function getChannelPos(num){
	for(var i = 0; i < channelList.length/2 + 1; i++){
		if(channelList[i].userChannel == num){
			return i;
		}else if(channelList[channelList.length - 1 - i].userChannel == num){
			return channelList.length - 1 - i;
		}
	}
}
//显示频道列表的滚动条
function setProgress(){
	if (channelListObj.listPos == channelListObj.dataLength-1)$('channelList_progress').style.top = progress_bottom;
	else if (channelListObj.listPos == 0)$('channelList_progress').style.top = progress_top;
	else{
		$('channelList_progress').style.top = progress_top + parseInt((channelListObj.listPos/(channelListObj.dataLength-1))*(progress_bottom - progress_top));
	}
}
//显示频道列表
function showChannelList(){
	channelListShow = true;
	$("channel_list").style.left = "985px";
	$("channel_list").style.opacity = 1;

	if(channelList.length > 0){
		var name = iPanel.misc.getUserCharsetStr(channelList[channelListObj.listPos].name,"gb2312");
		if(E.getStrChineseLength(name) > 9){
			var str = (""+channelList[channelListObj.listPos].userChannel).add(3) + " " + name;
			$("channelList_" + channelListObj.divs[channelListObj.divPos]).innerHTML = "<marquee>" + str + "</marquee>";
		}
	}
	clearTimeout(channelListTimer);
	channelListTimer = setTimeout("hideChannelList()",5000);
}

//隐藏频道列表
function hideChannelList(){
	channelListShow = false;
	clearTimeout(channelListTimer);
	$("channel_list").style.left = "1280px";
	$("channel_list").style.opacity = 0;
}

//频道列表上下键切换焦点
function channelList_change(__num){
	if (channelList.length == 0)return;
	var name = iPanel.misc.getUserCharsetStr(channelList[channelListObj.listPos].name,"gb2312");
	if(E.getStrChineseLength(name) > 9){
		name = E.getStrChineseLen(name,9);
		$("channelList_" + channelListObj.divs[channelListObj.divPos]).innerText = (""+channelList[channelListObj.listPos].userChannel).add(3) + " " + name;
	}
	if (channelListObj.listPos == channelListObj.dataLength - 1 && __num > 0){//循环滚动
		if (channelListObj != null){
			channelListObj.resetSlip();
		}
		setChanneListPos(0);
		channelListObj = new E.listSlip_2D(slipOpotion);
		channelListObj.initData();
		setProgress();
	}
	else if (channelListObj.listPos == 0 && __num < 0){
		if (channelListObj != null){
			channelListObj.resetSlip();
		}
		setChanneListPos(channelListObj.dataLength - 1);
		channelListObj = new E.listSlip_2D(slipOpotion);
		channelListObj.initData();
		setProgress();
	}
	else{
		channelListObj.changeFocus(__num);
		setProgress();
	}
	var name = iPanel.misc.getUserCharsetStr(channelList[channelListObj.listPos].name,"gb2312");
	if(E.getStrChineseLength(name) > 9){
		var str = (""+channelList[channelListObj.listPos].userChannel).add(3) + " " + name;
		clearTimeout(chanListMarqueeTimer);
		chanListMarqueeTimer = setTimeout(function(){
			$("channelList_" + channelListObj.divs[channelListObj.divPos]).innerHTML = "<marquee>" + str + "</marquee>";
		},300);
	}
}

//左右切换频道列表
function change_list(__num){
	if (currType + __num > 3)currType = 1;
	else if (currType + __num < 1)currType = 3;
	else currType += __num;

	if (currType == 2){
		$('currType').innerText = '广播';
	}
	else if (currType == 1){
		$('currType').innerText = '电视';
	}
	else if (currType == 3){
		$('currType').innerText = '喜爱';
	}
	initChannelList();
	showChannelList();
}