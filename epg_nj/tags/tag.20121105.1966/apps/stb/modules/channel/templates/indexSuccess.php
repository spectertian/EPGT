<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<?php use_helper('Week') ?>
<div class="wapper" id="wapper">
	<div class="bg">
		<div class="nav">
			<h1>江苏有线云媒体电视</h1>
			<ul id="navul">
				<li><a href="/channel/index" class="there">一周节目</a></li>
				<li><a href="/user/cliplist">我的片单</a></li>
				<li><a href="/search">搜索</a></li>
				<li><a href="/default/index">智能门户</a></li>
				<li><a href="/vod/index">影片库</a></li>
			</ul>
		</div>

		<div class="weeklist clr">
			<div class="weekl">
				<h2 id="tv"><img src="/pic/6.jpg" alt=""/>东方卫视</h2>
				<div class="htime">
					<ul>
						<?php $i=0; $day=date('Y-m-d'); foreach(aweek() as $key=>$value):?>
                        <?php $text_valuea=explode('-',$value); $text_value=$text_valuea[1].'月'.$text_valuea[2].'日'?>
                        <li <?php if($i%2==0):?>  class="odd"<?php endif;?>><a href="#" <?php if($day==$value):?>class="there" <?php endif;?> id="<?php echo $value;?>" onclick="getDayProgram('','<?php echo $value;?>',this)"><?php echo $text_value?></a></li>
                        <?php $i++; endforeach;?>
					</ul>
				</div>
				<p><a href="<?php echo url_for('channel/list');?>">查看其他</a></p>
			</div>
			
			<div class="weekr">
				<div class="weekrlist">
					<ul id="content">
						<!--<li><a href="#" class="playsnow"><span>14:25</span><strong>正者无敌 9</strong><b>回看</b></a></li>
						<li class="odd"><a href="#" class="reserve"><span>14:25</span><strong>正者无敌 9</strong><b>预约</b></a></li>
						<li><a href="#"><span>14:25</span><strong>正者无敌 9</strong><b>回看</b></a></li>
						<li class="odd"><a href="#" class="cancel"><span>14:25</span><strong>正者无敌 9</strong><b>取消</b></a></li>
						<li><a href="#"><span>14:25</span><strong>正者无敌 9</strong><b>回看</b></a></li>
						<li class="odd"><a href="#"><span>14:25</span><strong>正者无敌 9</strong><b>回看</b></a></li>
						<li><a href="#"><span>14:25</span><strong>正者无敌 9</strong><b>回看</b></a></li>
						<li class="odd"><a href="#"><span>14:25</span><strong>正者无敌 9</strong><b>回看</b></a></li>
						<li><a href="#"><span>14:25</span><strong>正者无敌 9</strong><b>回看</b></a></li>
						<li class="odd"><a href="#"><span>14:25</span><strong>正者无敌 9</strong><b>回看</b></a></li>
						<li><a href="#"><span>14:25</span><strong>正者无敌 9</strong><b>回看</b></a></li>
						<li class="odd"><a href="#"><span>14:25</span><strong>正者无敌 9</strong><b>回看</b></a></li>
						<li><a href="#"><span>14:25</span><strong>正者无敌 9</strong><b>回看</b></a></li>--></ul>
				</div>
				
				<ul class="weelg">
					<!--<li>
						<a href="#">
							<img src="/pic/7.jpg" alt=""/>
							<span>
								<strong>心术</strong>
								<b>险象环生的谜题</b>
							</span>
						</a>
					</li>
					<li>
						<a href="#">
							<img src="/pic/7.jpg" alt=""/>
							<span>
								<strong>心术</strong>
								<b>险象环生的谜题</b>
							</span>
						</a>
					</li>--></ul>
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

	//initPage();  //一定要先初始化，在body onload事件不行，在这获取不到	
    function initPage(){
    	publicInit();	
        playVideo();
        $("#navul").splotNav();
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
    	getRecommendWiki(tvName);
        <?php else :?>
    	getLogo('<?php echo $channel?>');  
    	getRecommendWiki('<?php echo $channel?>'); 
        goChannelByNameThis('<?php echo $channel?>');
        <?php endif;?>
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
    					html+='<li><a href="#"><img src="'+data[i].cover+'"/><span><strong>'+data[i].title+'</strong><b>'+data[i].des+'</b></span></a></li>';
                    }
                    $('.weelg').html(html);
                }
            }
        });
	}

	//ajax 获得当天节目信息
	//ajax 获取台标
	function getLogo(tvName) {
		$.ajax({
            url: '<?php echo url_for('channel/getLogoUrl');?>',
            type: 'post',
            dataType: 'json',
            data: { 'tvName': tvName },
            success: function(data)
            {
                var html = "<img src='"+data.url+"'/>"+tvName;
                $('#tv').html(html);
                getDayProgram(tvName,<?php echo "'".date("Y-m-d",time())."'";?>,null);
            }
            
        });
	}
	//向下滚动
    var num=0;	
	//获得指定日期电视台节目
	function getDayProgram(tvname,day,obj) {
		var html = '';
        if(tvname==''){
            tvname=$('#tv').text();
        }
		var now = new Date();
		$.ajax({
            url: '<?php echo url_for('channel/getProgram');?>',
            type: 'post',
            dataType: 'json',
            data: { 'tvName': tvname,'date': day },
            success: function(data)
            {
                if(obj){
            		$('.there').removeClass('there');
            		obj.className = 'there';
                }
                var arr =new Array();
            	for (i=0; i<data.length; i++){
                	var addCss = (i%2!=0)?'  class="odd"':'';
                	var et = new Date(data[i].endTime);
                	var st =  new Date(data[i].startTime);
                    mystartTime=data[i].startTime.replace(" ","*");  //否则浏览器解析空格出来有错误
					if(now>st&&now<et){
					    num=i;  //记录当前正在播放的节目
					    html+='<li'+addCss+'><a href="#" class="playsnow"><span>'+data[i].time+'</span><strong>'+data[i].name+'</strong><b>正在播放</b></a></li>';
					}else if(now<st){
                        html+="<li"+addCss+"><a href='#'  class='reserve' onclick=orderAdd('"+tvname+"','"+data[i].name+"','"+mystartTime+"','"+data[i].channelCode+"')><span>"+data[i].time+"</span><strong>"+data[i].name+"</strong><b>预约</b></a></li>";
				        //html+="<li"+addCss+"><a href='#'  class='reserve' onclick=alert('你好')><span>"+data[i].time+"</span><strong>"+data[i].name+"</strong><b>预约</b></a></li>";
                	}else{
						html+='<li'+addCss+'><a href="#"><span>'+data[i].time+'</span><strong>'+data[i].name+'</strong></a></li>';
					}
                }
                $('#content').html(html);
                //$('.weekrlist ul').css('top',0); //重新置为0
                //计算当前的位置
                //alert(num);
                var $length = $('.weekrlist a').length;
                if(num < $length-8){
                    if(num>8){
                        $top=-50*(num-4);
                        num=num-4;
                        $('.weekrlist ul').css('top',$top);
                    }
                }
                //$('.weekrlist ul').find("a")[num].focus();
                scrollUpDown();
            }
        });
	}
	function scrollUpDown(){
	    //num=0;
		var $top = parseInt($('.weekrlist ul').css('top'));
		var $length = $('.weekrlist a').length;
		$('.weekrlist a').each(function(){
			//$(this).focus(function(){
				$(this).keydown(function(event){
					if(event.keyCode==jLim.VK_DOWN){
					    if(num<$length){
					       num=num+1;
                           if(num < $length-8){
                               $top=-50*num;
                               $('.weekrlist ul').css('top',$top);
                               $('.weekrlist ul').find("a")[num].focus();
                           }
					    }
                        return false;
                        //showTip(num);
                        //event.preventDefault();  //这个不行
					}
					if(event.keyCode==jLim.VK_UP&& num > 0){	
					    num=num-1;
                        if(num < $length-8){
    						$top=-50*num;
    						$('.weekrlist ul').css('top',$top);
                        }
                        $('.weekrlist ul').find("a")[num].focus();
                        return false;
                        //showTip(num);
                        //event.preventDefault();
					}
				});
			//});
		});
	}
    
    function orderAdd(channelname,programsName,starttime,channelCode){
        try {
            starttime=starttime.replace("*"," ");
            starttime=new Date(starttime);
            //alert(starttime);
    		for(var i = 0; i < SerList.length; i++) {
    			    var ser = SerList.getAt(i);				
    				if(ser.name ==channelname){
                        programs=ser.getPrograms(0);
                        //alert(programs.length);
                        for(var j = 0; j < programs.length; j++) {
                				//if(programs[j].name ==programsName){
                				//alert(programs[j].startTime);
                				if(starttime>=programs[j].startTime && starttime<programs[j].endTime){
                                    var location = programs[j].getLocation();
                                	var order = new Order(location);                         	
                                	var or=Orders.add(order);
                                    Orders.save();
                                    if(or==0){
                                        orderAjax(channelCode,programsName,starttime,channelname)
                                        alert('预约成功');
                                    }else if(or==-5){
                                        alert('已经预约过该节目');
                                    }else{
                                        alert('预约失败');
                                    }
                				}
                		}
    				}
    		}     
    	}catch(err) {
    		alert("没有发现中间件！");
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
</script>