<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<?php use_helper('Week') ?>
<div class="wapper">
	<div class="bg">
		<div class="nav">
			<h1>江苏有线云媒体电视</h1>
            <div class="navh">
                <ul id="navul">
    				<li><a href="/default/index">智能门户</a></li>
    				<li><a href="/vod/index">影片库</a></li>
    				<li><a href="/channel/index" class="there">一周节目</a></li>
                    <li><a href="/user/cliplist">预约管理</a></li>
                </ul>
            </div>
		</div>

		<div class="weeklist clr">
			<div class="weekl">
                <input type="hidden" value="" name="channel_name" id="channel_name"/>
				<h2 id="tv"><img src="/pic/12.png" alt=""/>CCTV-1</h2>
				<div class="htime">
					<ul id="myweek">
						<?php $i=0; $day=date('Y-m-d'); foreach(aweek() as $key=>$value):?>
                        <?php $text_valuea=explode('-',$value); $text_value=$text_valuea[1].'月'.$text_valuea[2].'日'?>
                        <li <?php if($i%2==0):?>  class="odd"<?php endif;?>><a href="#" <?php if($day==$value):?>class="there" <?php endif;?> id="<?php echo $value;?>" onclick="getDayProgram('','<?php echo $value;?>',this,1)"><?php echo ($day==$value)?'今天':$text_value?></a></li>
                        <?php $i++; endforeach;?>
					</ul>
				</div>
				<p><a href="<?php echo url_for('channel/list');?>">其他频道</a></p>
			</div>
			
			<div class="weekr">
				<div class="weekrlist">
                <!--<p>该栏目暂无相关内容，请选择其他栏目观看</p>-->
					<ul id="content">
                        <!--
						<li><a href="#" class="playsnow"><span>10:30</span><strong><i>正者无敌 9</i></strong><b>正在播放</b></a></li>
						<li class="odd"><a href="#" class="reserve"><span>21:22</span><strong><i>正者无敌 9</i></strong><b>预约</b></a></li>
						<li><a href="#"><span>13:47</span><strong><i>正者无敌 9</i></strong><b>回看</b></a></li>
						<li class="odd"><a href="#" class="cancel"><span>14:10</span><strong><i>正者无敌 9</i></strong><b>取消</b></a></li>
						<li><a href="#"><span>15:30</span><strong><i>正者无敌 9</i></strong><b>回看</b></a></li>
						<li class="odd"><a href="#"><span>16:25</span><strong><i>正者无敌 9</i></strong><b>回看</b></a></li>
						<li><a href="#"><span>17:21</span><strong><i>正者无敌 9</i></strong><b>回看</b></a></li>
						<li class="odd"><a href="#"><span>18:36</span><strong><i>正者无敌 9</i></strong><b>回看</b></a></li>
			            -->
            		</ul>
				</div>
				
				<ul class="weelg">
					<li>
						<a href="#">
							<img src="/pic/4.jpg" alt=""/>
							<span>
								<strong>步步惊心</strong>
								<b>宫廷剧</b>
							</span>
						</a>
					</li>
					<li class="weelgtwo">
						<a href="#">
							<img src="/pic/8.jpg" alt=""/>
							<span>
								<strong>甄嬛传</strong>
								<b>宫廷剧</b>
							</span>
						</a>
					</li>
				</ul>
			</div>
		</div>			
		
		<div class="help">
			<ul>
                <li><img src="/pic/footchannel.png"/></li>
                <!--
				<li>按<img src="/img/sty2/fx.png" alt="选择"/>选择</li>
				<li>按<img src="/img/sty2/ok.png" alt="选择"/>进入</li>
				<li>按<img src="/img/sty2/cd.png" alt="选择"/>云媒体首页</li>
                <li>按<img src="/img/sty2/tv.png" alt="选择"/>进入频道</li>
                <li>按<img src="/img/sty2/bn.png" alt=""/>翻页</li>
                <li>按<font style="color:#00ff00">数字键</font>输入频道号可快速跳转</li>
                -->
                <li id="keynum"></li>
			</ul>
		</div>
	</div>
	
</div>

<script type="text/javascript">

	//initPage();  //一定要先初始化，在body onload事件不行，在这获取不到	
    function initPage(){
        try{
        	Utility.ioctlWrite("JsAddKeyState","Y");  //重新定义默认键值
        }catch(error){
            
        }
    	publicInit();	
        playVideoReturn();
        //$("#navul").animateNav({speed: 10, step: 75, width: 150});
        $("#content").scroll('i',11);
        //设置默认焦点
        $('#navul').find('a')[2].focus();
        <?php if (!$channel):?>
    	//获得该频道节目数据
    	var tvName='';
    	var Location=mp.getServiceLocation(0);
    	var ser = new Service(Location);
    	tvName=ser.name;
    	if(!ser||tvName==''||tvName==null){
    	   tvName='CCTV-1';
    	}    
    	tvName = tvName.toString();
    	getLogo(tvName);
        getDayProgram(tvName,'<?php echo date("Y-m-d");?>',null,1);
    	getRecommendWiki(tvName);
        <?php else :?>
    	getLogo('<?php echo $channel?>');
        getRecommendWiki('<?php echo $channel?>'); 
        getDayProgram('<?php echo $channel?>','<?php echo date("Y-m-d");?>',null,1);  
        goChannelByName('<?php echo $channel?>',2,'visible');
        <?php endif;?>
    }

    //本周
    function currentWeek(){
        var util=new MyUtil();
        var week=util.getCurrentWeek();
        var currentday=new Date(); 
        var html='';
        var dayi=0;  //控制翻页后的焦点位置
        for(var i = 0; i < week.length; i++) {
            weekstr=week[i].format("MM月dd日");
            day=week[i].format("yyyy-MM-dd");
            if(currentday.format("yyyy-MM-dd")==day){
                aclass=" class='there'";
                myday="今天";
                getDayProgram('',day,null,1); //重新加载节目数据
                dayi=i;
            }else{
                aclass="";
                myday=weekstr;
            }
            if(i%2==0){
                liclass=" class='odd'";
            }else{
                liclass="";
            }
            html+="<li"+liclass+"><a href='#' id=''"+aclass+" onclick=getDayProgram('','"+day+"',this,1)>"+myday+"</a></li>";
        }
        $('#myweek').html(html);
        $('#myweek').find("a")[dayi].focus();
        ckeckPageEvt();
    } 
    //上周
    function previousWeek(){
        var util=new MyUtil();
        //var week=util.getPreviousWeek();
        var week=util.getSevenDays();
        var html='';
        for(var i = 0; i < week.length; i++) {
            weekstr=week[i].format("MM月dd日");
            day=week[i].format("yyyy-MM-dd");
            if(i==6){
                aclass=" class='there'";
                getDayProgram('',day,null,1); //重新加载节目数据
            }else{
                aclass="";
            }
            if(i%2==0){
                liclass=" class='odd'";
            }else{
                liclass="";
            }
            html+="<li"+liclass+"><a href='#' id=''"+aclass+" onclick=getDayProgram('','"+day+"',this,1)>"+weekstr+"</a></li>";
        }
        $('#myweek').html(html);
        $('#myweek').find("a")[6].focus();
        ckeckPageEvt();
    } 
	//ajax 获得该电视台推荐节目
	function getRecommendWiki(tvName) {
		$.ajax({
            url: '<?php echo url_for('channel/getRecommendWiki');?>',
            type: 'post',
            dataType: 'json',
            data: { 'tvName': tvName },
            success: function(data)
            {
                if(data.length>0){
                    var html = '';
                    for (i=0; i<data.length; i++){
                        if(i==1){
                            html+='<li class="weelgtwo"><a href="/wiki/show/id/'+data[i].id+'"><img src="'+data[i].cover+'"/><span><strong>'+data[i].title+'</strong><b>'+data[i].des+'</b></span></a></li>';
                        }else{
                            html+='<li><a href="/wiki/show/id/'+data[i].id+'"><img src="'+data[i].cover+'"/><span><strong>'+data[i].title+'</strong><b>'+data[i].des+'</b></span></a></li>';
                        }
                    }
                    $('.weelg').html(html);
                    $(".weelg").scroll('strong',6);
                }
            }
        });
	}

	//ajax 获取台标
	function getLogo(tvName) {
	    var channel = ['CCTV-1','CCTV-2','CCTV-3','CCTV-4','CCTV-5','CCTV-6','CCTV-7','CCTV-8','CCTV-10','CCTV-11','CCTV-12','CCTV-新闻','CCTV-少儿','CCTV-NEWS','CCTV-音乐','CCTV-9纪录','重庆卫视'];
		$.ajax({
            url: '<?php echo url_for('channel/getLogoUrl');?>',
            type: 'post',
            dataType: 'json',
            data: { 'tvName': tvName },
            success: function(data)
            {
                if(data.url==''){
                    var html = ""+tvName;
                }else{
                    /*
                    if(channel.toString().indexOf(tvName) > -1) {
                        var html = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='"+data.url+"'/>";
                    }else{
                        var html = "<img src='"+data.url+"'/>"+tvName;
                    }
                    */
                    var html = "<img src='"+data.url+"'/>";
                }
                if(strlen(tvName)>8){
					$('#tv').addClass('small');
                }
                $('#tv').html(html);
                $('#channel_name').attr('value',tvName);
            },
            error: function(data){
				getLogo('CCTV-1');
				getRecommendWiki('CCTV-1');
                showTip('未获取到当前频道节目信息，已为您跳转至CCTV-1节目信息'); 
                $('#channel_name').attr('value','CCTV-1');
			}
        });
	}

	var page = 1;//当前页数
    var page2 = 0;   //第二页上翻到第一页时特殊处理
    var programsJSON,playNow,et,st,now,num;
    //获取某一天的节目数据
	function getDayProgram(tvname,day,obj,pageNum) {
        if(tvname==''){
            //tvname=$('#tv').text();
            tvname=$('#channel_name').attr('value');
        }

        page = pageNum;
        
        var currentday=new Date();
        if(currentday.format("yyyy-MM-dd") > day){
      	    url = '/channel/getCpgProgram';
        }else{
        	url = '/channel/getProgram';
        }
		$.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            //data: { 'tvName': tvname,'date': day, 'page': pageNum ,'userid': SmartCardNumber},
            data: { 'tvName': tvname,'date': day, 'page': pageNum },
            success: function(data){
                if(obj){
            		$('.there').eq(1).removeClass('there');
            		obj.className = 'there';
                }
				if(data){
                    programsJSON = data;
                    playNow = getNowProgramPage();
                    if (playNow>0) {
                        pageNum=page=playNow; num=0;
                    }else{
                        pageNum=page=1;num=0;
                    }
                    insertHTML(data,pageNum,1);
                    checkOrdered(tvname);
				}else{
                    $('#content').html('<p>该栏目暂无相关内容，请选择其他选项观看节目</p>');
				}
			},
            error:function(){
                $('#content').html('<p>该栏目暂无相关内容，请选择其他选项观看节目</p>');
            }
        });
	}
	

	function insertHTML(data,pageNum) {
        //var tvname=$('#tv').text();
        var tvname=$('#channel_name').attr('value');
		var html = '';
		now = new Date();
        var focus = arguments[2]?arguments[2]:0;
        //设置起始值
        var start = (pageNum-1)*9;
        var end = ((start+9)<data.length)?start+9:data.length;
        if(pageNum == 1&&page2 == 1) num=1;
        if(data[start]){
            for (i=start; i<end; i++){
                var addCss = (i%2!=0)?'  class="odd"':'';
                if(i==start) addCss += ' id="first"';
                if(i==(end-1)) addCss += ' id="last"';
                et = new Date(data[i].endTime);
                st =  new Date(data[i].startTime);
                mystartTime=data[i].startTime.replace(" ","*");  //否则浏览器解析空格出来有错误
                myendTime=data[i].endTime.replace(" ","*"); 
                if(now>st&&now<et){
                    //num=i;  //记录当前正在播放的节目
                    html+='<li'+addCss+'><a stime="'+mystartTime+'" etime="'+myendTime+'" href="#" class="playsnow" onclick=showPlayPage()><span>'+data[i].time+'</span><strong><i>'+data[i].name+'</i></strong><b>正在播放</b></a></li>';
                }else if(now<st){
                    html+="<li"+addCss+"><a stime='"+mystartTime+"' etime='"+myendTime+"' href='#' onclick=orderAdd('"+tvname+"','"+data[i].name+"','"+mystartTime+"','"+data[i].channelCode+"',this)><span>"+data[i].time+"</span><strong><i>"+data[i].name+"</i></strong><b>预约</b></a></li>";
                }else{
                    if(now.format("yyyy-MM-dd")==st.format("yyyy-MM-dd")){
                  	    var temphtml = '';
                  	    var tempurl  = '#'
                    }else{
                    	var temphtml = '<b>回看</b>';
                    	var tempurl  = '/cpg/show/contented/'+data[i].contentid+'/clientid/'+StbNumber;
                    }
                    html+='<li'+addCss+'><a href="'+tempurl+'"><span>'+data[i].time+'</span><strong><i>'+data[i].name+'</i></strong>'+temphtml+'</a></li>';
                }
            }
        }
        $('#content').html(html);
        if(focus==0){
            if(num == 0){
               $('.weekrlist ul').find("a")[0].focus();
            }else{
               $('.weekrlist ul').find("a")[8].focus();
            }
        }
        page2 = 0;
        ckeckPageEvt();
        $("#content").scroll('i',11);
	}
    //查看是否有预约
	function checkOrdered(channel){
		var stime,etime,order,ordertime;
		var ordersCount = Orders.getOrderCount(Orders.ORDER_TYPE_EPG);
		if(ordersCount>0){
		  $("#content").find('a').each(function(){
                stime=this.getAttribute('stime');
                etime=this.getAttribute('etime');
                if(stime&&etime){
                	stime=stime.replace("*"," ");
                	stime=new Date(stime);
                	etime=etime.replace("*"," ");
                	etime=new Date(etime);
                	for(var k = 0; k < ordersCount; k++ ) {
                        order = Orders.getAt(k,Orders.ORDER_TYPE_EPG);
                        ordertime=order.startTime;
                        var orderChannel = order.serviceName;
                		if((ordertime>=stime) && (ordertime<etime) && (channel == orderChannel)){
                			this.className = 'cancel';
                            $(this).find("b").eq(0).html('已预约');
                            //this.onclick=showOrderTip(k);
                            //this.setAttribute("onclick","showOrderTip(k)");
                            
                            /*
                            $(this).bind('click',function(){
                                showOrderTip(k);
                                $("#currentorder").attr('id','');
                                $(this).attr('id','currentorder');
                            });
                            */
                			break;
                		}
                		
                	}
                }
		  });
		}
	}
    function showOrderTip(i) {
    	$(".tipc").style("display","block");
        $("#tipInfo").html('是否删除此预约<p><a href="#" onclick="delEPGOrder('+i+')"><i>是</i></a>&nbsp;|&nbsp;<a href="#" onclick="closeOrderTip('+i+')"><i>否</i></a>');
        $("#tipInfo").find("a")[0].focus();
    }

    function closeOrderTip(i){
    	$(".tipc").style("display","none");
        $("#currentorder")[0].focus();
        $("#currentorder").attr('id','');
    	//$("#mylist").find("a")[i].focus();
    }
    
    function delEPGOrder(i) {
        var order = Orders.getAt(i,Orders.ORDER_TYPE_EPG);
        var r=Orders.deleteOrder (order);
        if(r==0){
            showTip('删除成功');
        }else if(r==1){
            showTip('无此预订');
        }
        $("#currentorder")[0].className='';
        $("#currentorder").unbind('click');
        $("#currentorder")[0].focus();
        $("#currentorder").attr('id','');
    }
	//日期及节目翻页
	function ckeckPageEvt() {
         //日期按上页，下页翻页
         $("#myweek").find("a").each(function(){
            $(this).keydown(function(evt){
                var evtcode = evt.which ? evt.which : evt.code;
                switch (evtcode) {
                    case 0x78:   //上页
                        previousWeek();
                        return false;  //不加这句按上页不起作用
                    case 0x79:   //下页
                        currentWeek();
                        return false;
                } 
            });
        });
        //tvname=$('#tv').text();
        tvname=$('#channel_name').attr('value');
	     //节目按上页，下页翻页
         $("#content").find("a").each(function(){
            $(this).keydown(function(evt){
                var evtcode = evt.which ? evt.which : evt.code;
                switch (evtcode) {
                    case 0x78:   //上页
                        if(page>1) {
                         page = page - 1;
                         num = 1;
                         if (page == 2) page2 = 1;
                         insertHTML(programsJSON,page);
                         checkOrdered(tvname);
                        }
                        return false;  //不加这句按上页不起作用
                    case 0x79:   //下页
                        if(page<(programsJSON.length/9)){
                          page = page + 1;
                          num = 0;
                          insertHTML(programsJSON,page);
                          checkOrdered(tvname);
                        }
                        return false;
                }
            });
        });
		$("#last").each(function(){
			$(this).keydown(function(evt){
				var evtcode = evt.which ? evt.which : evt.code;
				switch (evtcode) {
					case jLim.VK_DOWN:
                      if(page<(programsJSON.length/9)){
                          page = page + 1;
                          num = 0;
                          //getDayProgram('',date,null,page);
                          insertHTML(programsJSON,page);
                          checkOrdered(tvname);
                      }
					  //$('.weekrlist ul').find("a")[0].focus();
					  evt.preventDefault();
					  break;
				} 
				return false;
			});
		});
	}

    //获取当前正在播放节目所在页数
    function getNowProgramPage() {
        now = new Date();
        for (i=0; i<programsJSON.length; i++)
        {
            et = new Date(programsJSON[i].endTime);
            st =  new Date(programsJSON[i].startTime);
            if (now>st&&now<et)
            {
                playNow = i+1;
            }
        }
        return Math.ceil(playNow/9);
    }
    //预约添加
    function orderAdd(channelname,programsName,starttime,channelCode,obj){
        try {
            var ordersCount = Orders.getOrderCount(Orders.ORDER_TYPE_EPG);
            if(ordersCount>=7){
                showTip('最多只可预约7个节目');   
            }else{
                starttime=starttime.replace("*"," ");
                starttime=new Date(starttime);
                now=new Date();
                var year = now.getFullYear();       //年
                var month = now.getMonth() + 1;     //月
                var day = now.getDate();            //日
                var todays=year + '/'+month+'/'+day+ ' 00:00:00';  
                var today=new Date(todays);       
                var micros=starttime.getTime()-today.getTime();
                var daynum=Math.floor(micros/(24*3600*1000));
        		for(var i = 0; i < SerList.length; i++) {
        			    var ser = SerList.getAt(i);				
        				if(ser.name ==channelname){
                            programs=ser.getPrograms(daynum); //当天节目
                            for(var j = 0; j < programs.length; j++) {
                    				//if(programs[j].name ==programsName){
                    				if(starttime>=programs[j].startTime && starttime<programs[j].endTime){
                                        var location = programs[j].getLocation();
                                    	var order = new Order(location);                         	
                                    	var or=Orders.add(order);
                                        Orders.save();
                                        if(or==0){
                                            orderAjax(channelCode,programsName,starttime,channelname)
                                            obj.className='cancel';
                                            $(obj).find("b").eq(0).html('已预约');
                                            showTip('预约成功');
                                        }else if(or==-5){
                                            showTip('已经预约过该节目');      
                                        }else{
                                            showTip('预约失败');      
                                        }
                    				}
                    		}
        				}
        		}
                //checkOrdered(tvname);
            }
    	}catch(err) {
    		showTip("没有发现中间件！");
    	}   
    }
    function orderAjax(channel_code,programsName,starttime,channelname){
        var userId=hardware.smartCard.serialNumber;
        $.ajax({
            url: '<?php echo url_for('wiki/orderAdd')?>',
            type: 'post',
            data: {'user_id': userId,'channel_code': channel_code,'program_name':programsName,'start_time':starttime,'channel_name':channelname},
            success: function(data){
                /*
                if (data == 1) {
                    alert('预约成功');
                }
                */
            }
        });
    }
	//判断含有中文的字符长度
    function strlen(str){
    	var s = 0;
    	var lst = /[u00-uFF]/;
    	for(var i = 0; i < str.length; i++) {
    		if(!lst.test(str.charAt(i))) {
    			s+=2;
    		}else{
    			s++;
    		}
    	}
    	return s;
    }
    
    function eventHandler(evt){
    	var evtcode = evt.which ? evt.which : evt.code;
        //$("#keynum").html(evtcode);
    	switch (evtcode) {
            case 48:
    	    case 49:
            case 50:
            case 51:
            case 52:
            case 53:
            case 54:
            case 55:
            case 56:
            case 57:
                goChannelByNumber(parseInt(evtcode)-48);
                break;
    		case 112:   //"KEY_INFO"
            case 35:    //end键
    			showHotLivePage();
    			break;		
    		case 36:    //"HOME键"
            case 3864:  //"KEY_LIANXIANG"
            //case 0x31:  //1
    			showIndexPage();
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
            case 0x79:  //下页
                //showPageNext();
                evt.preventDefault();
                break;
            case 640:   //后退
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
    
    var remeberKeyValue="", remeberTimer=-1;
    function goChannelByNumber(_str){
    	if(remeberTimer!=-1){
    		clearTimeout(remeberTimer);
    	}
        remeberKeyValue += _str;
    	if(remeberKeyValue.length<4&&parseInt(remeberKeyValue)!=0&&parseInt(remeberKeyValue)<=181){
    		//location.href='<?php echo url_for('channel/number');?>?number='+remeberKeyValue;
            showOkTip(remeberKeyValue);
    	}else{
    	    remeberKeyValue=""; 
    	}
    	remeberTimer = setTimeout('remeberKeyValue=""; remeberTimer=-1', 5000);
    }
    function showOkTip(num) {
        name=getNameByNum(num);
    	$(".tipc").style("display","block");
        $("#tipInfo").html('CH  '+num+'<br/>是否跳转到<font color="#ff0000">'+name+'</font>节目单<p><a href="#" onclick="jump('+num+')"><i>确认</i></a>&nbsp;|&nbsp;<a href="#" onclick="closeTip()"><i>取消</i></a>&nbsp;|&nbsp;<a href="<?php echo url_for('channel/list');?>"><i>所有频道</i></a>');
        $("#tipInfo").find("a")[0].focus();
    }
    function jump(num){
        location.href='<?php echo url_for('channel/number');?>?number='+num;
    }    
    function closeTip(){
    	$(".tipc").style("display","none");
        $('#navul').find('a')[0].focus();
        remeberKeyValue=""; 
        remeberTimer=-1;
    }
</script>