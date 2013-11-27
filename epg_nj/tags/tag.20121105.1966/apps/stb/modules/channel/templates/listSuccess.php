<div class="wapper" id="wapper">
	<div class="bg">
		<span class="week" title="按一返回智能门户"></span>
		
		<h1 class="titbg">频道列表</h1>

		
		<div class="channel clr">
			<ul class="chanel_menu">
				<li class="t"><a href="#" class="there" onclick="getChannels(11,this)">中央频道</a></li>
				<li class="odd"><a href="#" onclick="getChannels(12,this)">本地频道</a></li>
				<li class="even"><a href="#" onclick="getChannels(13,this)">省外频道</a></li>
				<li class="odd"><a href="#" onclick="getChannels('pro',this)">专业频道</a></li>
				<li class="even"><a href="#" onclick="getChannels(22,this)">高清频道</a></li>
				<li class="b"><a href="#" onclick="getChannels(15,this)">全部频道</a></li>
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
			</ul>
		</div>
	</div>
	
</div>
<script type="text/javascript">
	var ob = $('.t a');
	getChannels(11,ob);
	function getChannels(type,obj){
		var html='';
        var j=0;
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
        $('.channel_list ul').css('top',0);
		/*$.ajax({
            url: '<?php echo url_for('channel/getChannelsByType');?>',
            type: 'post',
            dataType: 'json',
            data: { 'type': type },
            success: function(data)
            {
		        $('.there').removeClass('there');
		        obj.className = 'there';
            	
            	for (i=0; i<data.length; i++){
                	var addCss = (i%2==0)?' class="cl"':' class="cr"';
                	var name = "'"+data[i].name+"'";
					html+='<li'+addCss+'><a href="#" onclick="goTo('+name+')"><span>021</span>'+data[i].name+'</a></li>';
                }
                $('#content').html(html);
            }
        });*/
	}
    function scrollUpDown(){
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
	}
	function goTo(channel){
		//channel.toString();
		window.location.href='/channel/index?channel='+channel;
	}
	
    function initPage() {	
    	publicInit();	
        playVideo();
    }
</script>