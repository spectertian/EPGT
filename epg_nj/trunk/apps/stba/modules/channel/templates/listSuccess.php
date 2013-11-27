<div class="wapper">
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
                    <!--
					<li class="cl"><a href="#"><span>021</span>江苏卫视</a></li>
					<li class="cr"><a href="#"><span>021</span>江苏卫视</a></li>
					-->
				</ul>
			</div>
		</div>			
		
		<div class="help">
			<ul>
                <li><img src="/pic/footwiki.png"/></li>
                <!--
				<li>按<img src="/img/sty2/fx.png" alt="选择"/>选择</li>
				<li>按<img src="/img/sty2/ok.png" alt="选择"/>进入</li>
				<li>按<img src="/img/sty2/cd.png" alt="选择"/>云媒体首页</li>
                <li>按<img src="/img/sty2/tv.png" alt="选择"/>进入频道</li>
				<li>按<img src="/img/sty2/pd.png" alt="选择"/>帮助</li>
                <li>按<img src="/img/sty2/bn.png" alt=""/>翻页</li>
                -->
			</ul>
		</div>
	</div>
	
</div>

<script type="text/javascript">
	var ob = $('.t a')[0];
    var channelsJSON,page,html,num;
    function initPage() {	
    	publicInit();	
        playVideo();
        getChannels('cctv',ob);
    }
	function getChannels(type,obj){
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
            },
            error: function(data){
            	$('#content').html('<p>暂无该类型相关内容，请选择其他类型查看</p>');
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

	function goTo(channel){
		window.location.href='/channel/index?channel='+channel;
	}
</script>