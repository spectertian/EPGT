<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<div class="wapper" id="wapper">
	<div class="smartnav smartnavr">
       	<h2 id="tvplay"><strong></strong> <span class="time"><span class="timebg"><b class="timenow" style="width:10%"></b></span></span></h2>
        <div class="snav clr">
            <input type="hidden" name="type" id="f_type" value="<?php echo $type;?>" />
            <input type="hidden" name="page" id="f_page" value="<?php echo $page?>" />
			<h3>
            <!--<span class="movie_infoh"></span><span href="#" class="movie_list"></span>-->
            <a  class="movie_info" id="program_detail" onmouseover="showCurrentProgram();this.className='movie_infoh'" onmouseout="this.className='movie_info'"></a><a class="movie_list" id="program_channel" onmouseover="showCurrentProgram();this.className='movie_listh'" onmouseout="this.className='movie_list'"></a>
            </h3>
			<ul id="mytype">
                <?php foreach ($types as $type):?>
				<li><a href="javascript:void(0);" onclick="showProgram('<?php echo $type;?>','1',1,1,'<?php echo $arr_type[$type]?>')" onmouseover="showCurrentProgram();"><?php echo $type;?></a></li>
                <?php endforeach;?>
			</ul>
		</div>		
		<div class="snavlisth">
			<ul class="snavlist" id="showProgram"></ul>
		</div>
	</div>		
</div>
<script type="text/javascript">
    var curProgramName;
    var curService;
    var curServiceName;
    var curProgramStartTime;
    var curProgramEndTime;
    var time_id;
	//var j = 0;
    
    function initPage() {
    	publicInit();
        showProgram('电视剧',1,1,1,'Series');  //不设置焦点
        currentProgram(); //获取当前电视节目
        //playVideo();
        //------播放当前视频
        var curSer=getCurrentService(); 
        //playVideoByLocation(curSer.getLocation());  //用这种方法从其他应用返回来不播放 
        var modulation = 16 * Math.pow(2, curSer.modulation - 1);
		deliver = "deliver://"+curSer.frequency+"000."+curSer.symbolRate+"."+modulation+"."+curSer.serviceId;
        playVideoByLocation(deliver);
        //------播放当前视频
        $("#mytype").find("a")[0].focus();
        Utility.ioctlWrite("JsAddKeyState","Y");  //重新定义默认键值
        ckeckType();
    }
    
    /*
     * myfocus    控制是否设置焦点(为0设置焦点)  
     * scrollpage 控制是否到最后一页还继续加载空内容(为1则无论有没有都加载)
     */
    function showProgram(type,page){
        var myfocus=arguments[2]?arguments[2]:0;   
        var scrollpage=arguments[3]?arguments[3]:0;
        var interfacetype=arguments[4]?arguments[4]:'Series';
        $("#mytype").find("a").each(function(){
            if($(this).html() == type)
                $(this).addClass("there");
            else 
                $(this).removeClass("there");
        });
        $.ajax({
            url: '<?php echo url_for('list/showProgram')?>',
            type: 'post',
            dataType: 'html',
            data: {'type': type,'page':page,'scrollpage':scrollpage,'cardId': SmartCardNumber,'stbId': StbNumber,'interfacetype':interfacetype},
            success: function(data){
                $('#showProgram').html(data);
                $("#f_type").attr("value",type);
                $("#f_page").attr("value",page);
                ckeckPageEvt();
                /*
				if(j=0){
	                currentProgram();
                };
                */
                if(myfocus==0){
	                $("#a0")[0].focus();
                }else{
                    currentProgram();
                }
                //$("#showProgram").scrollul($("#showProgram"));  //推荐滚动
                $("#showProgram").animateNav({speed:10, step:140, width:140});
                $("#showProgram").scroll("em",4); 
                //ckeckUp();
            }
        });
    }     

    function hiddenPage() {
        hidPlay();
        $("#wapper").style("visibility","hidden");
    } 
    
    function autohiddenPage() {
        clearTimeout(time_id);
        time_id=setTimeout(hiddenPage,15000);
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
    function showPlay(channel,name,start,end){
        var mt = new Date();
        //var mytime = mt.getHours()+':'+mt.getMinutes();
        var mytime = mt.format("h:m");
        var newname = setStringCut(name,20,'...');
        var width = percentAge(start,end);
    	$("#tvplay").html("<strong>"+channel+"："+newname+"</strong><span class='time'><span class='timebg'><b style='width: "+width.toString()+"%;' class='timenow'></b></span>"+mytime+"</span>");
        $("#tvplay").style("visibility","visible");
    }
    
    function showWiki(name){
        var myDate = new Date();
        var mytime=myDate.format("h:m");
    	$("#tvplay").html("<strong>"+name+"</strong><span class='time'><span class='timebg'><b style='width: 0%;' class='timenow'></b></span>"+mytime+"</span>");
        $("#tvplay").style("visibility","visible");
    }
    
    function showCurrentProgram() {
        clearTimeout(time_id);
        showPlay(curServiceName,curProgramName,curProgramStartTime,curProgramEndTime);
    }
    
    //获取当前节目名称
    function currentProgram(){
        var currentSer = getCurrentService();
        var program = currentSer.presentProgram;
        curProgramName = program.name;
        curServiceName = currentSer.name;
        curProgramStartTime = program.startTime.getTime()/1000;
        curProgramEndTime = program.endTime.getTime()/1000;                   
        //$("#program_detail").attr('href','#');
        //$("#program_channel").attr('href','/channel/index/channel/' + currentSer.name);
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
                    //$("#program_detail").attr('href','/wiki/show/slug/' + program.wiki_slug);
                    if(!program.wiki_slug){
                    	//$("#program_detail").attr('href','#');
                        $('#program_detail').bind('click', function () {
                            showTip('未获取到节目详情');
                        });
                    }else{
                        $("#program_detail").unbind("click");
                    }
                    showPlay(currentSer.name,program.name,program.start_time,program.end_time);
                }else{
                    $('#program_detail').bind('click', function () {
                        showTip('未获取到节目详情');
                    });
                }
            }
        });
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
                        location.href='/wiki/show/slug/' + program.wiki_slug;
                    }
                }else{
                    showTip('未获取到节目详情');
                }
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
    function eventHandler(evt){
    	var evtcode = evt.which ? evt.which : evt.code;
    	switch (evtcode) {		
    		case 112:   //"KEY_INFO"
            case 35:    //end键
    			showHotLivePage();
    			break;		
    		case 36:    //"HOME键"
            case 3864:  //"KEY_LIANXIANG"
                showIndexPage();
                break;
            case 0x31:  //1,改写
                getProgram();
    			break;
            case 0x32:  //2,改写
                getChannel();
    			break;
    		case 113:    //"KEY_MENU"
    			showTip("KEY_MENU");
    			break;	
            case 33:     //"Pg Up键"
            case 0x78:   //上页
                //showPagePrior();
                evt.preventDefault();
                break;
            case 34:    //"Pg Down键"
                break;
            case 38:    //
                //$("#mytype").find("a")[0].focus();
                $(".there")[0].focus();
                evt.preventDefault();
                break;
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
    function ckeckType() {
        $("#mytype").find("a").eq(0).keydown(function(evt){
                var evtcode = evt.which ? evt.which : evt.code;
                switch (evtcode) {
                    case jLim.VK_LEFT: 
                      $("#mytype").find("a")[7].focus();
                      return false;
                      break;
                } 
        });
        $("#mytype").find("a").eq(7).keydown(function(evt){
                var evtcode = evt.which ? evt.which : evt.code;
                switch (evtcode) {
                    case jLim.VK_RIGHT: 
                      $("#mytype").find("a")[0].focus();
                      return false;
                      break;
                } 
        });
    }
    function ckeckUp() {
        $(".program_list_gb").find("a").each(function(){
              $(this).keydown(function(evt){
                    var evtcode = evt.which ? evt.which : evt.code;
                    switch (evtcode) {
                        case jLim.VK_UP: 
                          $(".there")[0].focus();
                          return false;
                          break;
                    } 
             });
        });
    }
</script>
