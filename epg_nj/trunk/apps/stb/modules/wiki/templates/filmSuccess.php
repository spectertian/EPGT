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
                    <ul class="dbzylist">
                        <?php 
                              $n=0;
                              //$arr_img=array('yang.com'=>'dbk.png','2A08_003'=>'pptv.png','CP1N02A08_003'=>'pptv.png','1905yy00'=>'m1905.png');
                              $arr_img=array('yang.com'=>'dbk.png','1905yy00'=>'m1905.png');
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
    playVideoReturn();  
    $("#hotplays").scroll("big",10,8); 
    <?php if($dianbo_scroll==1): //防止滚动错误?>
    $.ajax({
            url: '<?php echo url_for('wiki/relevance')?>',
            type: 'post',
            dataType: 'html',
            data: {'wiki_id':"<?php echo $wiki->getId()?>",'cardId': SmartCardNumber,'stbId': StbNumber},
            success: function(data){
                $("#dianbo").html(data);
                $("#dianbo").scroll1("big",4);
            }
    });
    <?php endif;?> 
    Utility.ioctlWrite("JsAddKeyState","Y");  //重新定义默认键值
    //$("#huikan").scroll("big",10); 
    //$("#yugao").scroll("big",8); 
}
function orderAdd(channelname,programsName,starttime,channelCode){
    try {
        //starttime.replace("-","/");
        starttime=new Date(starttime);
        now=new Date();
        var year = now.getFullYear();       //年
        var month = now.getMonth() + 1;     //月
        var day = now.getDate();            //日
        var todays=year + '/'+month+'/'+day+ ' 00:00:00';  
        var today=new Date(todays);       
        var micros=starttime.getTime()-today.getTime();
        var daynum=Math.floor(micros/(24*3600*1000));
		for(var i = 0; i < SerList.length; i++) {
			    var ser = SerList.getAt(i);				
				if(ser.name ==channelname){
                    programs=ser.getPrograms(daynum);
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
function eventHandler(evt){
	var evtcode = evt.which ? evt.which : evt.code;
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
        //case 640:   //后退
            location.href='/<?php echo $refer?>';
            evt.preventDefault();
            break;
        case 0x30:  //0
            //getChannelsAndPost();
            //evt.preventDefault();
            break;
	}	
}
</script>