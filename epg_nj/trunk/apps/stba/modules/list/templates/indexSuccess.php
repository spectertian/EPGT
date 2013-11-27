<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<div class="wapper">
	<div class="smartnav8">
        <input type="hidden" name="type" id="f_type" value="<?php echo $type;?>" />
    	<div class="clr" id="tvplay">
            <!--
                <h2><b><img src="img/sty2/jsws.png" alt=""/><strong>江苏卫视</strong><span>《非诚勿扰》</span></b></h2>
                <div class="time">
                    <span>8:00</span>
                    <span class="timeline"><b><em></em></b></span>
                    <span>9:00</span>
                </div>
                <div class="tips">按<img src="img/sty2/qr.png" alt=""/>进入观看<img src="img/sty2/znmh.png" alt="" class="pic"/></div>
            -->
        </div>
        
        <div class="nav">
            <a class="up" onclick="showPagePrior();" id="up"></a> 
            <div class="hidelist">
                <ul>
                    <li></li>
                </ul>
            </div>
            <a class="down" onclick="showPageNext();" id="down"></a>
        </div>
        
        <ul class="list clr" id="showProgram">
            <!--
            <li class="no4">
               <a href="#">
               		<img src="/pic/4.jpg" alt=""/>
                    <span>
                    	<em>步步惊心</em>
                    </span>
               </a> 
            </li>
            -->
        </ul>
        <a class="l" id="left"></a><a class="r" id="right"></a>
    </div>
</div>

<script type="text/javascript">
    var curProgramName;
    var curService;
    var curServiceName;
    var curServicePic;
    var curServiceTag;
    var curProgramStartTime;
    var curProgramEndTime;
    
    var time_id;
    var types=new Array('Series','Movie','Sports','Entertainment','Cartoon','Culture','News','vod');
    var names=new Array('电视剧','电&nbsp;影','体&nbsp;育','综&nbsp;艺','动&nbsp;漫','文&nbsp;化','综&nbsp;合','热&nbsp;播');
    
    var pre_programs;  //存储ajax数据
	function inArray(arr, val){
		for(var i=0; i<arr.length; i++){
			if(arr[i] == val)
				return i;
		}
		return -1;
	}
    function initPage() { 
    	publicInit();
        currentProgram(1); //获取当前电视节目
        //showCurrentProgram();
        playVideoReturn();
        $("#down")[0].className='down1';
        $("#right").addClass('rh');
        //$("#right")[0].focus();
        Utility.ioctlWrite("JsAddKeyState","Y");  //重新定义默认键值
        //Utility.ioctlWrite("motoKey2Dvb", "");
        autohiddenPage();
        //setTimeout(PreGetProgram(),3000); //3秒后提前得到全部数据
        

    }
    function PreGetProgram() {
		for(var i=0; i<types.length; i++){
            $.ajax({
                url: '<?php echo url_for('list/showProgram')?>',
                type: 'post',
                dataType: 'html',
                data: {'type': types[i],'cardId': SmartCardNumber,'stbId': StbNumber},
                success: function(data){
                    pre_programs[types[i]]=data;
                }
            });
		}
    }
    function keyRefresh() {
        //alert('调用了');
        Utility.ioctlWrite("JsAddKeyState","Y");  
    } 
    /*
     * myfocus    控制是否设置焦点(为0设置焦点)  
     */
    function showProgram(type){  
        /*
        if(pre_programs[type]!=''){
                    $('#showProgram').html(pre_programs[type]);
                    $("#f_type").attr("value",type);
                    $("#showProgram").scroll("em",4); 
                    $(".hidelist ul li").eq(0).html(names[inArray(types, type)]);
        }else{ */
            $.ajax({
                url: '<?php echo url_for('list/showProgram')?>',
                type: 'post',
                dataType: 'html',
                data: {'type': type,'cardId': SmartCardNumber,'stbId': StbNumber},
                success: function(data){
                    $('#showProgram').html(data);
                    $("#f_type").attr("value",type);
                    $("#showProgram").scroll("em",4); 
                    $(".hidelist ul li").eq(0).html(names[inArray(types, type)]);
                    //Utility.ioctlWrite("JsAddKeyState","Y");  //重新定义默认键值
                }
            });   
        //}
    }     

    function hiddenPage() {
        hidPlay();
        $("#wapper").style("visibility","hidden");
    } 
    
    function autohiddenPage() {
        clearTimeout(time_id);
        //time_id=setTimeout(hiddenPage,15000);
        time_id=setTimeout(showPlayPage,<?php echo $autohidden;?>);
    }  
    
    function showHotLivePage() {
        if($("#wapper").style("visibility") == "hidden"){
            $("#wapper").style("visibility","visible");
            $("#tvplay").style("visibility","visible");
        }else{
            $("#wapper").style("visibility","hidden");
            $("#tvplay").style("visibility","hidden");
        }
    }        
    function showPlay(channel,name,start,end,pic){
        var mt = new Date();
        var mytime = mt.format("h:m");
        var newname = setStringCut(name,20,'...');
        var width = percentAge(start,end);
        var start1=new Date(parseInt(start)*1000);
        var end1=new Date(parseInt(end)*1000);
        var startTime=start1.format("h:m");
        var endTime=end1.format("h:m");
        //$("#tvplay").html('<h2><b><img src="'+pic+'" alt=""/><strong>'+channel+'&nbsp;&nbsp;</strong><span>'+newname+'</span></b></h2><div class="time"><span>'+startTime+'</span><span class="timeline"><b style="width:'+width.toString()+'%;"><em></em></b></span><span>'+endTime+'</span></div><div class="tips">按<img src="img/sty2/qr.png" alt=""/>进入观看<img src="img/sty2/znmh.png" alt="" class="pic"/></div>');
        $("#tvplay").html('<h2><b><strong>'+channel+'&nbsp;&nbsp;</strong><span>'+newname+'</span></b></h2><div class="time"><span>'+startTime+'</span><span class="timeline"><b style="width:'+width.toString()+'%;"><em></em></b></span><span>'+endTime+'</span></div><div class="tips">按<img src="/img/sty2/qr.png" alt=""/>进入观看<img src="/img/sty2/znmh.png" alt="" class="pic"/></div>');
    }
    
    function showWiki(name){
        $("#tvplay").html('<h2><b><span>点播：'+name+'</span></b></h2><div class="tips">按<img src="/img/sty2/qr.png" alt=""/>进入观看<img src="/img/sty2/znmh.png" alt="" class="pic"/></div>');
    }
    
    function showCurrentProgram() {
        //clearTimeout(time_id);
        showPlay(curServiceName,curProgramName,curProgramStartTime,curProgramEndTime,curServicePic);
    }
    
    //获取当前节目名称
    function currentProgram(){
        //var date1=new Date();   //开始时间
        var show=arguments[0]?arguments[0]:0;
        try{
            var currentSer = getCurrentService();
            var program = currentSer.presentProgram;
            curProgramName = program.name;
            curServiceName = currentSer.name;
            curProgramStartTime = program.startTime.getTime()/1000;
            curProgramEndTime = program.endTime.getTime()/1000;   
            //显示获取的节目信息                
            showPlay(currentSer.name,program.name,curProgramStartTime,curProgramEndTime);        
            var url = '<?php echo url_for('program/GetCurrentProgramByChannelname?channelname=')?>';
            $.ajax({
                url: url+currentSer.name,
                type: 'get',
                dataType: 'html',
                success: function(data){
                    if(data!='null'){
                        var program =  stringToJSON(data);
                        curProgramName = program.name;
                        curServiceName = currentSer.name;
                        curProgramStartTime = program.start_time;
                        curProgramEndTime = program.end_time;
                        curServicePic = program.channel_logo;
                        curServiceTag = program.tag;
                        showPlay(currentSer.name,program.name,program.start_time,program.end_time,curServicePic);
                        if(!curServiceTag||curServiceTag==''){
                            curServiceTag='Series';
                        }
                        if(show==1){
                            showProgram(curServiceTag); 
                        }
                    }else{
                        //showPlay(currentSer.name,program.name,program.start_time,program.end_time,curServicePic);
                        showProgram('Series'); 
                    }
                },
                error: function(){
                    //showPlay(currentSer.name,program.name,program.start_time,program.end_time,curServicePic);
                    showProgram('Series'); 
                }
            });
        }catch(error){
            
        }
        //var date2=new Date();    //结束时间
        //var date3=date2.getTime()-date1.getTime();  //时间差的毫秒数
        //alert(date3);
    }   
    //获取当前节目详情,按1键用
    function getProgram(){
        var currentSer = getCurrentService();  
        var url = '<?php echo url_for('program/GetCurrentProgramByChannelname?channelname=')?>';
        $.ajax({
            url: url+currentSer.name,
            type: 'get',
            dataType: 'html',
            success: function(data){
                if(data!='null'){
                    var program =  stringToJSON(data);
                    if(!program.wiki_slug){
                        showTip('未获取到节目详情');
                    }else{
                        //location.href='/wiki/show/slug/' + program.wiki_slug+'/refer/list';
                        location.href='/wiki/show/id/' + program.wiki_id+'/refer/list';
                    }
                }else{
                    showTip('未获取到节目详情');
                }
            }
        });
    }   
    //设置点击次数
    function setLiveHit(){ 
        var path = '<?php echo url_for('list/setLiveHit')?>';
        $.ajax({
            url: path,
            type: 'get',
            success: function(data){
                
            }
        });
    }     
    //获取当前频道一周节目信息,按2键用
    function getChannel(){
        var currentSer = getCurrentService();
        location.href='/channel/index/channel/' + currentSer.name;
    }     
    function hidPlay() {
        $("#tvplay").style("visibility","hidden");
    }

    function thefocus(){
    	$('.program_list_gb').find('a').focus();
    }
    var keynum=0;
    function eventHandler(evt){
    	//var evtcode = evt.which ? evt.which : evt.code;
        var evtcode = evt.keyCode;
        if(keynum==0){
            Utility.ioctlWrite("JsAddKeyState","Y");  
            //Utility.ioctlWrite("motoKey2Dvb", "");
        }
        //$("#up").html(evtcode);
        keynum++;
        autohiddenPage();
    	switch (evtcode) {		
    		case 112:   //"KEY_INFO"
            case 35:    //end键
    			//showHotLivePage();
                getProgram();
    			break;		
    		case 36:    //"HOME键"
            case 3864:  //"KEY_LIANXIANG"
            case 0x31:  //1,改写
                showIndexPage();
    			break;
            case 0x32:  //2,改写
                //getChannel();
    			break;
    		case 113:    //"KEY_MENU"
    			showTip("KEY_MENU");
    			break;	
            case 38:    //上键
            case 33:    //"Pg Up键"
            case 0x78:  //上页
            case 28:    //点播传过来的上键
                showPagePrior();
                evt.preventDefault();
                break;
            case 40:    //下键
            case 34:    //"Pg Down键"
            case 0x79:  //下页
            case 29:    //点播传过来的上键
                showPageNext();
                evt.preventDefault();
                break;
            case 37:    //左键
            case 30:    //点播传过来的左键
                showPageLeft();
                break;
            case 39:    //右键
            case 31:    //点播传过来的右键
                showPageRight();
                break;
            case 0x72:  //退出
            case 640:   //后退
                showPlayPage();
                evt.preventDefault();
                break;
            case 0x30:  //0
                //getChannelsAndPost();
                //evt.preventDefault();
                break;
			case 447: //音量加
				changeVolume(1);
				break;
			case 448: //音量减
				changeVolume(-1);
				break;
			case 449: //静音
				setMute();
				break;
    	}	
    }
    //音量
    var sysDa = new DataAccess("Systemsetting.properties");
    function changeVolume(_type) {
    	var volume = mp.getVolume();
    	volume += _type;
    	if (volume > 31) {
    		volume = 31;
    	} else if (volume < 0) {
    		volume = 0;
    	}
    	if (volumeMode == 1) {
    		sysDa.set("UniformVolume", volume);
    	}else {
    		var curSer = ServiceDB.getServiceList().filterService(0, "TV").currentService;
    		curSer.volume = volume;
    	}
    	mp.setVolume(volume);
    	if (mp.getMuteFlag() == 1) {
    		mp.setMuteFlag(0);
    	}
    }
    //静音
    function setMute() {
    	var muteStatus = mp.getMuteFlag();
    	muteStatus = muteStatus == 0 ? 1 : 0;
    	mp.setMuteFlag(muteStatus);
    }
    function showPagePrior() {
        var type = $("#f_type").attr("value");
    	var i = inArray(types, type)-1;
        if(i>=0){
            showProgram(types[i]);
            $("#f_type").attr("value",types[i]);
        }else{
            i=7;
            showProgram(types[i]);
            $("#f_type").attr("value",types[i]);
        }
        if(i==0){
            $("#down")[0].className='down1';
            $("#up")[0].className='up';
        }else if(i==7){
            $("#up")[0].className='up1';
            $("#down")[0].className='down';
        }else{
            $("#up")[0].className='up1';
            $("#down")[0].className='down1';
        }
        showCurrentProgram();
        //clearTimeout(time_id);
    }
    function showPageNext() {
        var type = $("#f_type").attr("value");
    	var i = inArray(types, type)+1;
        if(i<8){
            showProgram(types[i]);
            $("#f_type").attr("value",types[i]);
        }else{
            i=0;
            showProgram(types[i]);
            $("#f_type").attr("value",types[i]);

        }
        if(i==0){
            $("#down")[0].className='down1';
            $("#up")[0].className='up';
        }else if(i==7){
            $("#up")[0].className='up1';
            $("#down")[0].className='down';
        }else{
            $("#down")[0].className='down1';
            $("#up")[0].className='up1';
        }
        showCurrentProgram();
        //clearTimeout(time_id);
    }
    function showPageLeft() {
        $("#left").addClass('lh');
        $("#right").addClass('rh');
        //$("#right").removeClass('rh');
        $("#showProgram").find("a").eq(0).keydown(function(evt){
                var evtcode = evt.which ? evt.which : evt.code;
                switch (evtcode) {
                    case jLim.VK_LEFT: 
                      //$("#showProgram").find("a")[7].focus();
                      $("#dianboend")[0].focus();
                      return false;
                      break;
                } 
        });
        $("#showProgram").find("a").eq(0).mouseover(function(evt){
                $("#left").removeClass('lh');
                $("#right").addClass('rh');
        });
        $("#dianboend").mouseover(function(evt){
                $("#left").addClass('lh');
                $("#right").removeClass('rh');
        });
    }
    function showPageRight() {
        //$("#left").removeClass('lh');
        $("#left").addClass('lh');
        $("#right").addClass('rh');
        //$("#showProgram").find("a").eq(7).keydown(function(evt){
        $("#dianboend").keydown(function(evt){
                var evtcode = evt.which ? evt.which : evt.code;
                switch (evtcode) {
                    case jLim.VK_RIGHT: 
                      $("#showProgram").find("a")[0].focus();
                      return false;
                      break;
                } 
        });
        $("#showProgram").find("a").eq(0).mouseover(function(evt){
                $("#left").removeClass('lh');
                $("#right").addClass('rh');
        });
        $("#dianboend").mouseover(function(evt){
                $("#left").addClass('lh');
                $("#right").removeClass('rh');
        });
    }
</script>