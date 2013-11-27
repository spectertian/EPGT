<div class="wapper" id="wapper">
	<div class="bg">
		<span class="week" title="按一返回智能门户"></span>
		
		<h1 class="titbg">频道列表</h1>

		
		<div class="channel clr">
			<ul class="chanel_menu">
				<li class="t"><a href="#" class="there" onclick="getChannels('cctv',this)">中央频道</a></li>
				<li class="odd"><a href="#" onclick="getChannels('local',this)">本地频道</a></li>
				<li class="even"><a href="#" onclick="getChannels('tv',this)">省外频道</a></li>
				<li class="odd"><a href="#" onclick="getChannels('pay',this)">专业频道</a></li>
				<li class="even"><a href="#" onclick="getChannels('hd',this)">高清频道</a></li>
				<li class="b"><a href="#" onclick="getChannels('all',this)">全部频道</a></li>
			</ul>
			
			<div class="channel_list">
				<ul id="content">
					<!--<li class="cl"><a href="#"><span>021</span>江苏卫视</a></li>
					<li class="cr"><a href="#"><span>021</span>江苏卫视</a></li>-->
				</ul>
			</div>
		</div>			
		
		<div class="help">
			<ul>
				<li><img src="/img/fx.jpg" alt="选择"/>选择</li>
				<li><img src="/img/ok.jpg" alt="选择"/>进入</li>
				<li><img src="/img/cd.jpg" alt="选择"/>云媒体首页</li>
				<li><img src="/img/pd.jpg" alt="选择"/>帮助</li>
                <li><img src="/img/backs.png" alt=""/><img src="/img/nexts.png" alt=""/>翻页</li>
			</ul>
		</div>
	</div>
	
</div>
<script type="text/javascript">
	var ob = $('.t a');
	getChannels('cctv',ob);
    var channelsJSON,page,html,num;
	function getChannels(type,obj){
        /*var j=0;
        $('.there').removeClass('there');
        obj.className = 'there';
        var SerLists = ServiceDB.getServiceList();	
        var SerList = SerLists.filterService(ServiceDB.LIST_TYPE_BAT,type);
        for(var i = 0; i < SerList.length; i++) {
                var addCss = (i%2==0)?' class="cl"':' class="cr"';
    			var ser = SerList.getAt(i);
                var name = "'"+ser.name+"'";
                if(ser.logicNumber<10)
                    var logicNumber ='00'+ser.logicNumber;
                else if(ser.logicNumber>=10&&ser.logicNumber<100)
                    var logicNumber ='0'+ser.logicNumber;
                else
                    var logicNumber =ser.logicNumber;
                html+='<li'+addCss+'><a href="#" onclick="goTo('+name+');"><span>'+logicNumber+'</span>'+ser.name+'</a></li>';
    		}
        $('#content').html(html);
        scrollUpDown();
        $('.channel_list ul').css('top',0);*/
		$.ajax({
            url: '<?php echo url_for('channel/getChannelsByType');?>',
            type: 'post',
            dataType: 'json',
            data: { 'type': type },
            success: function(data)
            {
		        $('.there').removeClass('there');
		        obj.className = 'there';
                if (data)
                {   
                    num=5;obj.focus();
                    channelsJSON = data;
            	    insertHTML(data,1);
                    ckeckPageEvt();
                }
            }
        });
	}

    function insertHTML(data,pageNum){
        page = pageNum;
        var start = (pageNum-1)*18;
        var end = ((start+18)<data.length)?start+18:data.length;
        var logicNumber;
        html = '';
        if (data[start])
        {
            
            for (i=start; i<end; i++){
                var addCss = (i%2==0)?' class="cl"':' class="cr"';
                if (i==start){addCss += ' id="first"'}
                else if (i==(start+1)){addCss += ' id="first2"'}
                else if (i==(end-1)){addCss += ' id="last"';}
                else if (i==(end-2)){addCss += ' id="last2"';}
                if(data[i].logicNumber<10){
                    logicNumber ='00'+data[i].logicNumber;
                }else if(data[i].logicNumber>=10&&data[i].logicNumber<100){
                    logicNumber ='0'+data[i].logicNumber;
                }else{
                    logicNumber =data[i].logicNumber;
                }
                var name = "'"+data[i].name+"'";
                html+='<li'+addCss+'><a href="#" onclick="goTo('+name+')"><span>'+logicNumber+'</span>'+data[i].name+'</a></li>';
                
            }
            $('#content').html(html);
            if(num == 1){
                $('#content').find("a")[16].focus();
            }else if(num == 2){
                $('#content').find("a")[17].focus();
            }else if(num == 3){
                $('#content').find("a")[1].focus();
            }else if(num == 4){
                $('#content').find("a")[0].focus();
            }
            ckeckPageEvt();
        }
    }


    function ckeckPageEvt() {
        $("#first").each(function(){
            $(this).keydown(function(evt){
                var evtcode = evt.which ? evt.which : evt.code;
                switch (evtcode) {
                   case jLim.VK_UP:
                       showPagePrior(1);
                       evt.preventDefault();
                       break;
                }
                return false;
            });
        });
        $("#first2").each(function(){
            $(this).keydown(function(evt){
                var evtcode = evt.which ? evt.which : evt.code;
                switch (evtcode) {
                   case jLim.VK_UP: 
                       showPagePrior(2);
                       evt.preventDefault();
                       break;
                }
                return false;
            });
        });
        
		$("#last").each(function(){
			$(this).keydown(function(evt){
				var evtcode = evt.which ? evt.which : evt.code;
				switch (evtcode) {
					case jLim.VK_DOWN:
                        showPageNext(3);
                        evt.preventDefault();
					    break;
				} 
				return false;
			});
		});
        $("#last2").each(function(){
			$(this).keydown(function(evt){
				var evtcode = evt.which ? evt.which : evt.code;
				switch (evtcode) {
					case jLim.VK_DOWN:
                        showPageNext(4);
					    evt.preventDefault();
					    break;
				} 
				return false;
			});
		});
	}

    function showPagePrior(){
        if(page>1) {
            num = arguments[0]?arguments[0]:4;
            page = page-1;
            insertHTML(channelsJSON,page);
        }
    }

    function showPageNext(){
        if(page<(channelsJSON.length/18)){
            num = arguments[0]?arguments[0]:4;
            page = page+1;
            insertHTML(channelsJSON,page);
        }
    }


    /*function scrollUpDown(){
	    num=0;
		var $top = parseInt($('.channel_list ul').css('top'));
		var $length = $('.channel_list a').length;
		$('.channel_list a').each(function(){
			//$(this).focus(function(){
				$(this).keydown(function(event){
					if(event.keyCode==jLim.VK_DOWN&&num<$length-3){
                        num=num+2;
					    if(num<$length){   
                           if(num < $length-17){
                               if(num%2==0)
                               var j=num;
                               else
                               var j=num-1;
                               $top=-50*(j/2);
                               $('.channel_list ul').css('top',$top);
                               $('.channel_list ul').find("a")[num].focus();
                           }
					    }
                        return false;
                        //showTip(num);
                        //event.preventDefault();  //这个不行
					}
                    if(event.keyCode==jLim.VK_LEFT&&(num%2==1)){
                        num=num-1;
					    if(num>=0){      
                            $('.channel_list ul').find("a")[num].focus();
					    }
                        return false;
                        //showTip(num);
                        //event.preventDefault();  //这个不行
					}
                    if(event.keyCode==jLim.VK_RIGHT&&(num%2==0)){
                        num=num+1;
					    if(num>0){   
                           $('.channel_list ul').find("a")[num].focus();
					    }
                        return false;
                        //showTip(num);
                        //event.preventDefault();  //这个不行
					}
					if(event.keyCode==jLim.VK_UP){
					    if(num > 1){
                            num=num-2;
                            if(num < $length-17){ 
                                if(num%2==0)
                                   var j=num;
                                   else
                                   var j=num-1;
        						$top=-50*j/2;
        						$('.channel_list ul').css('top',$top);
                                
                             }
                            $('.channel_list ul').find("a")[num].focus(); 
					    }else{
					        $('.channel_list ul').find("a")[num].focus(); 
					    }
                        return false;
					}
				});
			//});
		});
	}*/
	function goTo(channel){
		//channel.toString();
		window.location.href='/channel/index?channel='+channel;
	}
	
    function initPage() {	
    	publicInit();	
        playVideo();
    }
</script>