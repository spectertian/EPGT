<div class="wapper" id="wapper">
	<div class="clr">
		<div class="tv_info">
			<div class="tv_intros">
  	            <img src="<?php echo thumb_url($wiki->getCover(),260,332)?>" alt=""/>
				<ul>
					<li><h2><?php echo $wiki->getTitle()?><a href="#" onclick="collecting('<?php echo (string)$wiki->getId()?>')">点击收藏</a></h2></li>
                    <?php include_partial('introduction', array('wiki' => $wiki,'model'=>$wiki->getModel()))?>
				</ul>
			</div>
			 <?php if ($wiki->getVideos()) :             ?>
			<div class="tv_list">
				<h2>点播资源<a href="#">奇艺</a><a href="#">新浪</a><a href="#">优酷</a></h2>
				<ul class="clr">
                    <?php include_partial('dianbo', array('wiki' => $wiki,'model'=>$wiki->getModel()))?>
				</ul>
			</div>
            <?php else:  ?>
            <div class="about">
					<h2>相关电影</h2>
					<ul class="">
                    <?php include_component('wiki', 'related_movies')?>
					</ul>
			</div>
            <?php endif;?>
		</div>	
		<ul class="time_list">
			<li><h2><?php echo $titlea;?></h2></li>
			 <?php include_partial('program_guide', array('programs' => $related_programs,'model'=>$wiki->getModel(),'title'=>$titlea))?>
		</ul>
	</div>
	
	<div class="shadows" id="shadows">
		<div class="tips">
			<h2>是否取消预定该节目?</h2>
			<a href="#" class="btn" id= "btn1" onclick="tipsclick()">确定</a>
			<a href="#" class="btn" id= "btn2" onclick="tipsclick()" >取消</a>
		</div>
	</div>
</div>
<script type="text/javascript">
function collecting(wiki) {
    var a=hardware.smartCard.serialNumber;
    $.ajax({
        url: '<?php echo url_for('@wiki_do')?>',
        type: 'GET',
        data: {'wiki_id': wiki, 'id': a },
        success: function(data){
            if (data == 1) {
                alert('收藏成功');
            }else if (data == 2) {
                alert('已经收藏');
            }else{
                alert('网络繁忙！请稍候再试..');
            }

        }       
    });
}
function tipsclick() {
    document.getElementById('shadows').setAttribute('style','');
}
function tvList(num){    
    var a = document.getElementById('pid'+num).getAttribute('pid');
    document.getElementById('shadows').setAttribute('style','display:block;');
    document.getElementById('btn1').setAttribute('href',a); 
    document.getElementById("btn1").focus();
}
function goOrderadd(channelname,programsName,starttime,channelCode){
    try {
        //starttime.replace("-","/");
        starttime=new Date(starttime);
		for(var i = 0; i < SerList.length; i++) {
			    var ser = SerList.getAt(i);				
				if(ser.name ==channelname){
                    programs=ser.getPrograms(0);
                    //alert(programs.length);
                    for(var j = 0; j < programs.length; j++) {
            				//if(programs[j].name ==programsName){
            				if(starttime>=programs[j].startTime && starttime<programs[j].endTime){
                                var location = programs[j].getLocation();
                            	var order = new Order(location);                         	
                            	var or=Orders.add(order);
                                Orders.save();
                                if(or==0){
                                    orderAjax(channel_code,programsName,starttime,channelname)
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
    var a=hardware.smartCard.serialNumber;
    $.ajax({
        url: '<?php echo url_for('wiki/doOrder')?>',
        type: 'GET',
        data: {'user_id': a,'channel_code': channel_code,'programsName':programsName,'starttime':starttime,'channelname':channelname},
        success: function(data){
            if (data == 1) {
                alert('预约成功');
            }
        }       
    });
}
</script>