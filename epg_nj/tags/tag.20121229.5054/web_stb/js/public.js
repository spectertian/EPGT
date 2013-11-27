var SerList = mp = StbNumber = SmartCardNumber = null;

function initPage() {	
	publicInit();	
    playVideo();
    $("#navul").animateNav({speed: 10, step: 20, width: 150});
}

function eventHandler(evt){
	var evtcode = evt.which ? evt.which : evt.code;
	switch (evtcode) {		
		case 112:   //"KEY_INFO"
        case 35:    //end键
			showHotLivePage();
			break;		
		case 36:    //"HOME键"
        case 3864:  //"KEY_LIANXIANG"
        case 0x31:  //1
			showIndexPage();
			break;
		case 113:    //"KEY_MENU"
			showTip("KEY_MENU");
			break;	
        case 33:     //"Pg Up键"
        case 0x78:   //上页
            showPagePrior();
            evt.preventDefault();
            break;
        case 34:    //"Pg Down键"
        case 0x79:  //下页
            showPageNext();
            evt.preventDefault();
            break;
        case 0x72:  //退出
            showPlayPage();
            evt.preventDefault();
            break;
        case 0x30:  //0
            //getChannelsAndPost();
            //evt.preventDefault();
            break;
	}	
}

function exitPage() {
}
   
function showPlayPage() {
    window.location.href = Utility.getEnv("LOCAL_ROOT_PATH") + "play/play.htm";
}
   
function showIndexPage() {
	location.href="/default/index";
}  

function showHotLivePage() {
	location.href="/list/index";
}       

function hiddenPage() {
    $("#wapper").style("visibility","hidden");
}
   
function publicInit() {
	try {
	    StbNumber=hardware.STB.serialNumber;              //机顶盒号
        SmartCardNumber=hardware.smartCard.serialNumber;  //智能卡号
		//SerList = ServiceDB.getServiceList(ServiceDB.LIST_TYPE_SERVICE,"TV");	
        SerList = ServiceDB.getServiceList().filterService(ServiceDB.LIST_TYPE_SERVICE, "TV");
		mp = new MediaPlayer();
	}catch(err) {
	}
}
//根据频道名称跳转到指定频道
function goChannelByName(name) {
    var jump=arguments[1]?arguments[1]:1;  //为1跳转到直播，否则仅播放
    var hidden=arguments[2]?arguments[2]:"hidden";   
	try {
	    jump_true=0;      
		for(var i = 0; i < SerList.length; i++) {
			var ser = SerList.getAt(i);
				if(ser.name == name) {
					$("#wapper").style("visibility",hidden);
					playVideoByLocation(ser.getLocation());
                    SerList.moveTo(i);
                    jump_true=1;
                    //onDisplayMessageEvent(DVB.getShowEvents()); //提示是否订购该频道
                    break;
				}
		}
        if(jump_true==0){
            showTip('不能跳转到该频道');
        } 	   
	    if(jump==1){
	       showPlayPage();
	    }
	}catch(err) {
		showTip("没有发现中间件！");
	}	
}
//获取当前正在播放的频道
function getCurrentService() {
    try {
        //var Location = mp.getServiceLocation(0);
        //return new Service(Location);
        return ServiceDB.getServiceList().filterService(0, "TV").currentService;
    }catch(err) {
		showTip("没有发现中间件！");
	}
	finally{		
	}
}
//播放指定的频道节目
function playVideoByLocation(location){
    try {
        var nativePlayerInstanceId = mp.getNativePlayerInstanceId ();
        mp.setVideoDisplayMode(1);
        mp.refreshVideoDisplay();
        mp.setSingleMedia(location); 
        mp.playFromStart();
    }catch(err) {
        showTip("没有发现中间件！");
    }
    finally{		
    }	
}
//播放之前正在播放的频道
function playVideo(){
    try {
        var nativePlayerInstanceId = mp.getNativePlayerInstanceId ();
        mp.bindNativePlayerInstance(nativePlayerInstanceId);  //绑定播放实例
        mp.setVideoDisplayMode(1); 
        mp.refreshVideoDisplay();
        mp.playFromStart();
    }catch(err) {
        showTip("没有发现中间件！");
    }
    finally{		
    }	
}

function printDebug(_str) {
	$("#debugDiv").html(_str + "<br>" + $("#debugDiv").html());
}

function showTip(_tip) {
    var mytimer;
    clearTimeout(mytimer);
    var hidetime=arguments[1]?arguments[1]:2000;
    $(".tipc").style("display","block");
    $("#tipInfo").html(_tip);
    if(arguments[2]=='big'){  //为了wiki详情的更多内容设置
        $(".tipc").addClass('tipbig');
        $(".tipc h2 span").html('详细信息');
        $(".tipc").find("a")[0].focus();
        //按任意键隐藏
        $(".tipc").find("a").eq(0).keydown(function(evt){
            hideTip();
            $("#showall")[0].focus();
            evt.preventDefault();
        });
    }else{
        $(".tipc").removeClass('tipbig');
        mytimer=setTimeout("hideTip()",hidetime);
    }
}

function hideTip() {
	$(".tipc").style("display","none");
	$("#tipInfo").html("");
}

//根据频道名称跳转到指定频道
function getChannelsAndPost() {
	try {
        var channels = [];
        SerList = ServiceDB.getServiceList(ServiceDB.LIST_TYPE_SERVICE,"TV");
	    for(var i = 0; i < SerList.length; i++) {
			var ser = SerList.getAt(i);
            if(ser.type == 1 || ser.type == 7) {
                //batType=ServiceDB.getBATInfos(ser);
                var channel = {"name" : ser.name,
                               "serviceId" : ser.serviceId,
                               "frequency" : ser.frequency,
                               "symbolRate" : ser.symbolRate,
                               "modulation" : ser.modulation,
                               "onId" : ser.onId,
                               "tsId" : ser.tsId,
                               "logicNumber" : ser.logicNumber,
                               "location" : ser.getLocation()}; 
                channels.push(channel);
            }
		}
        showTip("共收集"+channels.length+"个频道，正在上报中...");
        $.ajax({url: '/channel/InjectSpService',
                type: 'post',
                data: {json_str: jsonToString(channels)},
                success: function(data){
                    showTip(data);            
                }});
	}catch(err) {
		showTip("没有发现中间件！");
	}
	finally{		
	}	
}

//判断是否订购该频道
function onDisplayMessageEvent(events) {
	if (events.length > 0) {
		var maxId = 0;//events.length-1;
		switch (events[maxId].type) {
			case 40021:
				showTip("加锁节目");
				break;
			case 40023:
				if (SerList.length == 0) {
					info = $GL.NO_SERVICE;
				} else {
					info = "无信号";
				}
				showTip(info);
				break;
			case 40081:
				var tipsArr = ['',$GL.CA_1, $GL.CA_2, $GL.CA_3, $GL.CA_4, $GL.CA_5, $GL.CA_6, $GL.CA_7, $GL.CA_8, $GL.CA_23, $GL.CA_10, $GL.CA_11, $GL.CA_12, $GL.CA_13, $GL.CA_14, $GL.CA_15, $GL.CA_16, $GL.CA_17, $GL.CA_18, $GL.CA_19, $GL.CA_20, $GL.CA_21,$GL.CA_22];
				var type = Number(events[maxId].msgSubType);
				if(type == 1 || type == 2 || type == 10)
				{
					Utility.ioctlWrite("NM_Error","nAction:"+1+",code:"+40081+",subcode:"+type);
				}
				if (type < 23) {
					info = tipsArr[type];
					if(type == 9) {	
						Utility.setEnv("NM_CaStatus", "failed");
						showTip("您尚未订购该频道");
					}
				} else if (type == 23) {//在屏幕上消除alarm消息-23
					hiddenTips();
				}
				break;
			case 40083:
				var tipsArr = ['',$GL.CA_MSG_0,'','',$GL.CA_MSG_1];
				var type = Number(events[maxId].msgSubType);
				if(type == 1){
					showTipsType = 0;
					var info = $GL.NO_CARD;
					showTip(info);
				}else{
					showTipsType = 0;
					info = tipsArr[type];
					showTip(info);
				}
				break;
		}
	}
}
function hiddenTips(){
	Utility.setEnv("NM_CaStatus", "success");
	var events = DVB.getShowEvents();
	if (events.length > 0) {
		onDisplayMessageEvent(events);
	}
}