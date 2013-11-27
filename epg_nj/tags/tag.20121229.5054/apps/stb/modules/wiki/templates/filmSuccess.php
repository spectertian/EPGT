<div class="wapper" id="wapper">
	<div class="bg">
		<span class="week" title="按一返回智能门户"></span>
		
		<h1 class="titbg"><?php echo $wiki->getTitle()?></h1>

		<div class="tv clr">
			<div class="tvl">
				<div class="tvinfo">
					<ul>
						<li class="hides">主演：
                        <?php if($stars = $wiki->getStarring()): $i = 0 ?>
                        <?php foreach($stars as $star){$i++;
                                if($i<5){echo ($i > 1) ? ' /' : ''; echo $star;}
                              }
                              endif; 
                        ?>          
                        </li>
						<li class="hides">国家：<?php echo $wiki->getCountry()?>
                            <span class='movieyear'>年代：<?php echo $wiki->getReleased()?></span>
                        </li>
						<li>简介：<?php echo $wiki->getHtmlCache(100, ESC_RAW);?> ...
                        <a href="#" onclick="showTip('<?php echo $wiki->getContent();?>',6000,'big')" id="showall">查看详情</a>
                        </li>
					</ul>
					<img src="<?php echo thumb_url($wiki->getCover(),150,200)?>" alt=""/>
				</div>
                <?php if ($videos = $wiki->getVideos()) :?>
				<div class="dbzy">
					<h2>点播资源</h2>
                    <!--
					<ul class="clr dbzypic">
                        <?php 
                              $n=0;
                              $arr_img=array('yang.com'=>9,'2A08_003'=>10,'CP1N02A08_003'=>10,'1905yy00'=>11);
                              foreach($videos as $video) :
                                  if($n>=3) break;
                        ?>
                        <li><a href="#" onclick="tclSave('<?php echo (string)$wiki->getId();?>','<?php echo $video->getUrl()."&backurl=http://172.31.139.17"?>');"><img src="/pic/<?php echo $arr_img[$video->getReferer()]?>.jpg" alt=""/></a></li>
                        <?php 
                              $n++;
                              endforeach;
                        ?>
					</ul>
                    -->
                    <ul class="dbzylist">
                        <?php 
                              $n=0;
                              $arr_img=array('yang.com'=>'dbk.png','2A08_003'=>'pptv.png','CP1N02A08_003'=>'pptv.png','1905yy00'=>'m1905.png');
                              foreach($videos as $video) :
                                  if($n>=3) break;
                                  $config=$video->getConfig();
                                  $asset_id=$config['asset_id'];
                        ?>
                        <li><span><img src="/img/<?php echo $arr_img[$video->getReferer()]?>"></img></span><a href="#" onclick="playvideo('<?php echo $asset_id;?>','<?php echo $video->getReferer()?>');"><?php echo $video->getTitle();?></a></li>
                        <?php 
                              $n++;
                              endforeach;
                        ?>
                    </ul>
				</div>
                <?php else:  $dianbo_scroll=1;?>
				<div class="notv">
					<h3>暂无点播资源，向您推荐以下内容</h3>
					<ul class="clr" id="dianbo">
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
    try{
        //playVideo();
        //------播放当前视频
        var curSer=getCurrentService(); 
        //playVideoByLocation(curSer.getLocation());  //用这种方法从其他应用返回来不播放 
        var modulation = 16 * Math.pow(2, curSer.modulation - 1);
    	deliver = "deliver://"+curSer.frequency+"000."+curSer.symbolRate+"."+modulation+"."+curSer.serviceId;
        playVideoByLocation(deliver);
    }catch(err){
        
    }
    //------播放当前视频    
    $("#hotplays").scroll("big",10,8); 
    <?php if($dianbo_scroll==1): //防止滚动错误?>
    $("#dianbo").scroll1("big",4);
    <?php endif;?> 
    //$("#huikan").scroll("big",10); 
    //$("#yugao").scroll("big",8); 
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
                                    showTip('预约成功');
                                }else if(or==-5){
                                    showTip('已经预约过该节目');
                                }else{
                                    showTip('预约失败');
                                }
            				}
            		}
				}
		}     
	}catch(err) {
		showTip("没有发现中间件！");
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
    /*
    user_id=hardware.smartCard.serialNumber;
    $.ajax({
        url: '<?php echo url_for('wiki/tclSave')?>',
        type: 'post',
        dataType: 'text',
        data: {uid: user_id,wiki_id: wikiid},
        success: function(data){
            if(data==1)
                showTip('成功保存用户数据');
            else
                showTip('保存用户数据失败');    
        }
    });
    */
    location.href=url;
}
function playvideo(asset_id,sp_code) {
    location.href='/wiki/play/asset_id/'+asset_id+'/sp_code/'+sp_code+'/user_id/'+SmartCardNumber;
}
function played(contentid){
    url='/cpg/show/contented/'+contentid+'/clientid/'+StbNumber;
    location.href=url;
}
</script>