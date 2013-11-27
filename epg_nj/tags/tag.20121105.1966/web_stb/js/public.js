var SerList = mp = null;

function initPage() {	
	publicInit();	
    playVideo();
    $("#navul").splotNav();
}

function eventHandler(evt){
	var evtcode = evt.which ? evt.which : evt.code;
	switch (evtcode) {		
		case 112://"KEY_INFO"
        case 33://"Pg Up"
        //case 13://"KEY_ENTER"
			showHotLivePage();
			break;		
		case 36://"KEY_HOME"
        case 3864://"KEY_LIANXIANG"
        case 0x31://1键
			showIndexPage();
			break;
		case 113://"KEY_MENU"
			showTip("KEY_MENU");
			break;	
        case 0x31:
        case 0x78:
            showPagePrior();
            evt.preventDefault();
            break;
        case 0x32:
        case 0x79:
            showPageNext();
            evt.preventDefault();
            break;
        case 0x72:
            hiddenPage();
            evt.preventDefault();
            break;
	}	
}

function exitPage() {
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
		SerList = ServiceDB.getServiceList(ServiceDB.LIST_TYPE_SERVICE,"TV");	
		mp = new MediaPlayer();
	}catch(err) {
	}
}
//根据频道名称跳转到指定频道
function goChannelByName(name) {
	try {
		for(var i = 0; i < SerList.length; i++) {
			var ser = SerList.getAt(i);
				if(ser.name == name) {
					$("#wapper").style("visibility","hidden");
					playVideoByLocation(ser.getLocation());
					return false;
				}
		}
	}catch(err) {
		showTip("没有发现中间件！");
	}
	finally{		
	}	
}
//获取当前正在播放的频道
function getCurrentService() {
    try {
        var Location = mp.getServiceLocation(0);
        return new Service(Location);
    }catch(err) {
		showTip("没有发现中间件！");
	}
	finally{		
	}
}
//根据频道名称跳转到指定频道
function goChannelByNameThis(name) {
    try {
        for(var i = 0; i < SerList.length; i++) {
            var ser = SerList.getAt(i);
                if(ser.name == name) {
                    playVideoByLocation(ser.getLocation());
                }
        }
    }catch(err) {
        showTip("没有发现中间件1！");
    }
    finally{		
    }	
}
//播放指定的频道节目
function playVideoByLocation(location){
    try {
        var mp = new MediaPlayer();
        var nativePlayerInstanceId = mp.getNativePlayerInstanceId ();
        mp.setVideoDisplayMode(1);
        mp.setSingleMedia(location); 
        mp.playFromStart();
        mp.refreshVideoDisplay();
    }catch(err) {
        showTip("没有发现中间件1！");
    }
    finally{		
    }	
}
//播放之前正在播放的频道
function playVideo(){
    try {
        var mp = new MediaPlayer();
        var nativePlayerInstanceId = mp.getNativePlayerInstanceId ();
        mp.setVideoDisplayMode(1); 
        mp.playFromStart();
        mp.refreshVideoDisplay();
    }catch(err) {
        showTip("没有发现中间件1！");
    }
    finally{		
    }	
}

function printDebug(_str) {
	$("#debugDiv").html(_str + "<br>" + $("#debugDiv").html());
}

function showTip(_tip) {
	$("#tipDiv").style("visibility","visible");
	$("#tipInfo").html(_tip);
	setTimeout("hideTip()",2000);
}

function hideTip() {
	$("#tipDiv").style("visibility","hidden");
	$("#tipInfo").html("");
}