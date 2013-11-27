<div class="wapper" id="wapper">
	<div class="bg">
		<span class="week" title="按一返回智能门户"></span>
		
		<h1 class="titbg"><?php echo $wiki->getTitle()?></h1>

		<div class="tv clr">
			<div class="tvl">
				<div class="tvinfo">
					<ul>
						<li>主演：
                        <?php if($stars = $wiki->getStarring()): $i = 0 ?>
                        <?php foreach($stars as $star){$i++;
                                if($i<5){echo ($i > 1) ? ' /' : ''; echo $star;}
                              }
                              endif; 
                        ?>          
                        </li>
						<li>国家：<?php echo $wiki->getCountry()?></li>
						<li>类型：
                        <?php if($tags = $wiki->getTags()): $i = 0?>
                        <?php foreach($tags as $tag){$i++;
                                echo ($i > 1) ? ' /' : ''; echo $tag;
                              }
                              endif; 
                        ?> 
                        </li>
						<li>年代：<?php echo $wiki->getReleased()?></li>
						<li>简介：<?php echo $wiki->getHtmlCache(60, ESC_RAW);?> ...</li>
					</ul>
					<img src="<?php echo thumb_url($wiki->getCover(),150,200)?>" alt=""/>
				</div>
                <?php if ($videos = $wiki->getVideos()) :?>
				<div class="dbzy">
					<h2>点播资源</h2>
					<ul class="clr">
                        <?php 
                              $n=0;
                              foreach($videos as $video) :
                                  if($n>=3) break;
                        ?>
                        <li><a href="#" onclick="tclSave('<?php echo (string)$wiki->getId();?>','http://hditv.jsamtv.com/epg/show.do?app=vpg&hd=y&content=forsearch&movieassetid=10819891&inquiry=y&clientid=');"><img src="/pic/9.jpg" alt=""/></a></li>
                        <?php 
                              $n++;
                              endforeach;
                        ?>    
                        <!--
						<li><a href="#"><img src="/pic/10.jpg" alt=""/></a></li>
						<li><a href="#"><img src="/pic/11.jpg" alt=""/></a></li>
                        -->
					</ul>
				</div>
                <?php else: ?>
				<div class="notv">
					<h3>暂无点播资源，向您推荐以下内容</h3>
					<ul class="clr">
						<?php include_component('wiki', 'related_movies')?>
					</ul>
				</div>                
                <?php endif;?>
			</div>
			
			<div class="tvr">
				<?php include_partial('program_guide', array('count_programs' => $count_programs,'hot_programs'=>$hot_programs,'played_programs'=>$played_programs,'unplayed_programs'=>$unplayed_programs))?>
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
function initPage() {	
	publicInit();	
    playVideo();
    $("#hotLiveProgram").scroll("b",5); 
}
function orderAdd(channelname,programsName,starttime,channelCode){
    try {
        //starttime.replace("-","/");
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

function tclSave(wikiid,url) {
    user_id=hardware.smartCard.serialNumber;
    $.ajax({
        url: '<?php echo url_for('wiki/tclSave')?>',
        type: 'post',
        dataType: 'text',
        data: {uid: user_id,wiki_id: wikiid},
        success: function(data){
            if(data==1)
                alert('成功保存用户数据');
            else
                alert('保存用户数据失败');    
        }
    });
    location.href=url;
}
</script>