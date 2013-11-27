<div class="wapper">
	<div class="bg">
		<div class="nav">
			<h1>江苏有线云媒体电视</h1>
            <div class="navh">
                <ul id="navul">
    				<li><a href="/default/index">智能门户</a></li>
    				<li><a href="/vod/index">影片库</a></li>
    				<li><a href="/channel/index">一周节目</a></li>
                    <li><a href="/user/cliplist" class="there">预约管理</a></li>       
                </ul>
            </div>
		</div>

		<div class="my_list clr">
			<div class="mylist_hide">
				<ul id="mylist">
                    <!--
					<li>
						<a href="#">
							<b>10月2日</b>
							<span>14:28</span>
							<strong><big>金太郎的幸福生活</big></strong>
							<em><img src="/pic/13.png" alt=""/>cctv1综合频道</em>
							<i>回看</i>
						</a>						
					</li>
                    -->
				</ul>
			</div>
			<a class="ad200"><img src="/pic/ad.png" alt=""/></a>
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
			</ul>
		</div>
	</div>
	
</div>
<script type="text/javascript">
    function initPage() {	
    	publicInit();	
        playVideo();
        //$("#navul").animateNav({speed: 10, step: 75, width: 150});
        $('#navul').find('a')[3].focus();   
        getEPGOrders();
        $("#mylist").scroll('big',8);     
    }
    function getEPGOrders() {
        var myfocus=arguments[0]?arguments[0]:0;   
        var ordersCount = Orders.getOrderCount(Orders.ORDER_TYPE_EPG);
        $("#mylist").html("");
        if(ordersCount==0){
      	    //showTip('暂无片单!');
            $(".mylist_hide").html("<p>该栏目暂无相关内容，请选择其他选项观看节目</p>");
            $("#navul").find("a")[3].focus();
        }
        for(var i = 0; i < ordersCount; i++ ) {
            var order = Orders.getAt(i,Orders.ORDER_TYPE_EPG);
            var time=order.startTime.format("hh:mm");
            var riqi=order.startTime.format("MM月dd日");;
            if(i%2==0){
                var list='<li class="even"><a href="#" onclick="showOkTip('+i+')"><b>'+riqi+'</b><span>'+time+'</span><strong><big>'+order.name+'</big></strong><em>'+order.serviceName+'</em><i>删除</i></a></li>';
            }else{
                var list='<li><a href="#" onclick="showOkTip('+i+')"><b>'+riqi+'</b><span>'+time+'</span><strong><big>'+order.name+'</big></strong><em>'+order.serviceName+'</em><i>删除</i></a></li>';
            }
            $("#mylist").append(list);
            if(myfocus==1){
                $("#mylist").find("a")[0].focus();
            }
        }
    }

    function showOkTip(i) {
    	$(".tipc").style("display","block");
        $("#tipInfo").html('是否删除此预约<p><a href="#" onclick="delEPGOrder('+i+')"><i>是</i></a>&nbsp;|&nbsp;<a href="#" onclick="closeTip('+i+')"><i>否</i></a>');
        $("#tipInfo").find("a")[0].focus();
    }

    function closeTip(i){
    	$(".tipc").style("display","none");
    	$("#mylist").find("a")[i].focus();
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