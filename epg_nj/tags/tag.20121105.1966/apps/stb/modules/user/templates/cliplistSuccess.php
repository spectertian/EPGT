<div class="wapper" id="wapper">
	<div class="bg">
		<div class="nav">
			<h1>江苏有线云媒体电视</h1>
			<ul id="navul">
				<li><a href="/user/cliplist" class="there">我的片单</a></li>
				<li><a href="/search">搜索</a></li>            
				<li><a href="/default/index">智能门户</a></li>
				<li><a href="/vod/index">影片库</a></li>
				<li><a href="/channel/index">一周节目</a></li>

			</ul>
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
			<a href="#" class="ad200"><img src="/pic/ad.png" alt=""/></a>
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
    getEPGOrders();
    function getEPGOrders() {
        var ordersCount = Orders.getOrderCount(Orders.ORDER_TYPE_EPG);
        for(var i = 0; i < ordersCount; i++ ) {
            var order = Orders.getAt(i,Orders.ORDER_TYPE_EPG);
            var time=order.startTime.format("hh:mm");
            var riqi=new Date();
            riqi=riqi.format("MM月dd日");
            if(i%2==0){
                var list='<li class="even"><a href="#" onclick="delEPGOrder('+i+')"><b>'+riqi+'</b><span>'+time+'</span><strong>'+order.name+'</strong><em>'+order.serviceName+'</em><i>删除</i></a></li>';
            }else{
                var list='<li><a href="#" onclick="delEPGOrder('+i+')"><b>'+riqi+'</b><span>'+time+'</span><strong>'+order.name+'</strong><em>'+order.serviceName+'</em><i>删除</i></a></li>';
            }
            $("#mylist").append(list);
        }
    }
    function delEPGOrder(i) {
        var order = Orders.getAt(i,Orders.ORDER_TYPE_EPG);
        var r=Orders.deleteOrder (order);
        if(r==0){
            alert('删除成功');
        }else if(r==1){
            alert('无此预订');
        }
        location.reload();
    }     
</script>