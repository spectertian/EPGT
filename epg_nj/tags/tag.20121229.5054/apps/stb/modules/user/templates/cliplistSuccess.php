<div class="wapper" id="wapper">
	<div class="bg">
		<div class="nav">
			<h1>江苏有线云媒体电视</h1>
            <div class="navh">
    			<ul id="navul">
    				<li><a href="/user/cliplist" class="there">我的片单</a></li>
    				<li><a href="/search">搜索</a></li>            
    				<li><a href="/default/index">智能门户</a></li>
    				<li><a href="/vod/index">影片库</a></li>
    				<li><a href="/channel/index">一周节目</a></li>
    			</ul>
            </div>
		</div>

		<div class="my_list clr">
			<div class="mylist_hide">
				<ul id="mylist">
                <!--
					<li class="even">
						<a href="#">
							<b>10月2日</b>
							<span>14:28</span>
							<strong>金太郎的幸福生活</strong>
							<em>cctv1综合频道</em>
							<i>回看</i>
						</a>						
					</li>
                -->    
				</ul>
			</div>
			<span class="ad200"><img src="/pic/ad.png" /></span>
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
    function initPage() {	
    	publicInit();	
        playVideo();
        $("#navul").animateNav({speed: 10, step: 75, width: 150});
        getEPGOrders();
        $("#mylist").scroll('big',9);        
    }
    function getEPGOrders() {
        var myfocus=arguments[0]?arguments[0]:0;   
        var ordersCount = Orders.getOrderCount(Orders.ORDER_TYPE_EPG);
        $("#mylist").html("");
        if(ordersCount==0){
      	    //showTip('暂无片单!');
            $(".mylist_hide").html("<p>暂无片单</p>");
            $("#navul").find("a")[0].focus();
        }
        for(var i = 0; i < ordersCount; i++ ) {
            var order = Orders.getAt(i,Orders.ORDER_TYPE_EPG);
            var time=order.startTime.format("hh:mm");
            //var riqi=new Date();
            //riqi=riqi.format("MM月dd日");
            var riqi=order.startTime.format("MM月dd日");;
            if(i%2==0){
                var list='<li class="even"><a href="#" onclick="delEPGOrder('+i+')"><b>'+riqi+'</b><span>'+time+'</span><strong><big>'+order.name+'</big></strong><em>'+order.serviceName+'</em><i>删除</i></a></li>';
            }else{
                var list='<li><a href="#" onclick="delEPGOrder('+i+')"><b>'+riqi+'</b><span>'+time+'</span><strong><big>'+order.name+'</big></strong><em>'+order.serviceName+'</em><i>删除</i></a></li>';
            }
            $("#mylist").append(list);
            if(myfocus==1){
                $("#mylist").find("a")[0].focus();
            }
        }
    }
    function delEPGOrder(i) {
        var order = Orders.getAt(i,Orders.ORDER_TYPE_EPG);
        var r=Orders.deleteOrder (order);
        if(r==0){
            showTip('删除成功');
        }else if(r==1){
            showTip('无此预订');
        }
        getEPGOrders(1);
        //location.reload();
    }
</script>