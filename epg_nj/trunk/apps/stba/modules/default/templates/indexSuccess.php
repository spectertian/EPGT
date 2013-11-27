<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<div class="wapper">
	<div class="bg">
		<div class="nav">
			<h1>江苏有线云媒体电视</h1>
			<div class="navh">
				<ul id="navul">
                    <li><a href="/default/index" class="there">智能门户</a></li>
                    <li><a href="/vod/index">影片库</a></li>
                    <li><a href="/channel/index">一周节目</a></li>
                    <li><a href="/user/cliplist">预约管理</a></li>
				</ul>		
			</div>
		</div>
		
		<div class="liststy playnow">
        	<h2><span class="num" id="num1"><b>1</b>/<em>6</em></span><span class="line"></span>热播频道</h2>
			<div class="hlist">
				<ul class="clr" id="livelist">
                    <?php include_component('default','liveList');?>
				</ul>
			</div>
		</div>
		
		<div class="liststy playnow">
        	<h2><span class="num" id="num2"><b>1</b>/<em>6</em></span><span class="line"></span>猜你喜欢</h2>
			<div class="hlist">
				<ul class="clr" id="recommend">
                <!--
					<li>
						<a href="#" title="" class="there">
							<img src="pic/1.jpg" alt=""/>
							<span>
								<i><big>张三李四王二麻子电视台</big></i>
								<strong><b style="width:30%"></b></strong>
							</span>
							<em>224567人</em>
							<em class="tvnumber">广西卫视</em>
						</a>
					</li>
                -->
				</ul>
			</div>
		</div>
        
        <div class="help">
            <ul>
                <li><img src="/pic/footindex.png"/></li>
                <!--
                <li>按<img src="/img/sty2/fx.png" alt="选择"/>选择</li>
                <li>按<img src="/img/sty2/ok.png" alt="选择"/>进入</li>
                <li>按<img src="/img/sty2/cd.png" alt="选择"/>云媒体首页</li>
                <li>按<img src="/img/sty2/tv.png" alt="选择"/>进入频道</li>
                <li>按<img src="/img/sty2/pd.png" alt="选择"/>帮助</li>
                -->
                <li id="key"></li>
            </ul>
        </div>
	</div>
</div>
<script type="text/javascript">
    function initPage() {
    	publicInit();	
        playVideoReturn();
        //加载推荐内容
        recommend();       
        //导航滚动    
        //$("#navul").animateNav({speed: 10, step: 75, width: 150});
        //图层滚动
        $("#livelist").animateNav({speed:10, step:122, width:244});
        $("#recommend").animateNav({speed:10, step:83, width:165}); 
        //文字滚动 
        $("#livelist").scroll("big",6); 
        $("#recommend").scroll("big",6);
        //设置默认焦点
        $('#navul').find('a')[0].focus();
        //重新定义默认键值
        //setTimeout(keyRefresh(),1000);
        Utility.ioctlWrite("JsAddKeyState","Y");  
        
    }
    function keyRefresh() {
        //alert('调用了');
        Utility.ioctlWrite("JsAddKeyState","Y");  
    }
    function showIndexPage() {
        showPlayPage(); 
    }  
    function goChannel(name) {
        showTip('正在跳转到该频道');
        setTimeout(function(){goChannelByName(name);},2000); //隔2秒再跳转
        return true;
    }
    function recommend(){
        $.ajax({
            url: '<?php echo url_for('default/recommend')?>',
            type: 'post',
            data: {'cardId': SmartCardNumber,'stbId': StbNumber},
            success: function(data){
                $("#recommend").html(data);    
                $("#recommend").animateNav({speed:10, step:83, width:165});   
                $("#recommend").scroll("big",4);
            }       
        });
    }
    function showNum(num,i) {
        $("#"+num+" b").html(i);
    }
    var keynum=0;
    function eventHandler(evt){
    	var evtcode = evt.which ? evt.which : evt.code;
        if(keynum==0){
            Utility.ioctlWrite("JsAddKeyState","Y");  
            //$("#key").html("执行了"+evtcode);
        }else{
            //$("#key").html(evtcode);
        }
        keynum++;
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
</script>