<?php
if ($wiki->countVideosByWikiId()){
    $yangcomCount=$wiki->countVideosByWikiId('yang.com','N');
    $yangcomHdCount=$wiki->countVideosByWikiId('yang.com','Y');
    $avpressCount=$wiki->countVideosByWikiId('avpress','N');
    $avpressHdCount=$wiki->countVideosByWikiId('avpress','Y');
    //$m1905Count=$wiki->countVideosByWikiId('1905yy00');
    $pptvCount=$wiki->countVideosByWikiId('2A08_003');
    if($yangcomHdCount>0){
      $referer='yang.com';
      $hd='Y';
      $title='点播库HD';
      $count=$yangcomHdCount;
    }elseif($avpressHdCount>0){
      $referer='avpress';
      $hd='Y';
      $title='点播库HD';
      $count=$avpressHdCount;
    }elseif($yangcomCount>0){
      $referer='yang.com';
      $hd='N';
      $title='点播库';
      $count=$yangcomCount;
    }elseif($avpressCount>0){
      $referer='avpress';
      $hd='N';
      $title='点播库';
      $count=$avpressCount;
    }elseif($pptvCount>0){
      $referer='2A08_003';
      $hd='';
      $title='PPTV';
      $count=$pptvCount;
    }
    $videoCount=$count;
}else{
    $videoCount=0;
}
?>
<div class="wapper">
	<div class="bg">
		<span class="week" title="按一返回智能门户"></span>
		
		<h1 class="titbg"><?php echo $wiki->getTitle()?><?php if($videoCount>30):?>(<?php echo $videoCount?>集)<?php endif;?></h1>

		<div class="tv clr">
			<div class="tvl">
				<div class="tvinfo">
					<ul>
						<li class="hides">主演：
                        <?php if($stars = $wiki->getStarring()): $i = 0 ?>
                        <?php foreach($stars as $star){
                                  $i++;
                                  if($i<5){echo ($i > 1) ? ' /' : ''; echo $star;}
                              }
                              endif;
                        ?> 
                        </li>
						<li class="hides">国家：<?php echo $wiki->getCountry()?>&nbsp;&nbsp;&nbsp;&nbsp;<span class="movieyear">年代：<?php echo $wiki->getReleased()?></span></li>
						<li class="hides">类型：
                        <?php if($tags = $wiki->getTags()): $i = 0?>
                        <?php foreach($tags as $tag){$i++;
                                echo ($i > 1) ? ' /' : ''; echo $tag;
                              }
                              endif; 
                        ?>    
                        </li>
						<li>简介：<?php echo mb_strimwidth($wiki->getContent(), 0, 100, '......', 'utf-8');?></li>
					</ul>
					<img src="<?php echo thumb_url($wiki->getCover(),150,200)?>" alt=""/>
				</div>
				
				<?php if ($videoCount>0) :?>
				<div class="playlibrary">
					<div class="itemchoice">
						<ul class="clr" id="myrefer">
							<li><a id="a0" href="#" onmouseover="showVideos(0,'<?php echo (string)$wiki->getId();?>','<?php echo $referer;?>','<?php echo $hd;?>')"><?php echo $title;?></a></li>
                            <!--<?php //if($pptvCount>0):?><li><a href="a2" href="#" onmouseover="showVideos(1,'<?php echo (string)$wiki->getId();?>','2A08_003','')"><img src="/img/pptv.png" alt=""/></a></li><?php //endif;?>-->
                            <!--<li><a id="a1" href="#" onmouseover="showVideos(2,'<?php echo (string)$wiki->getId();?>','1905yy00','')">M1905</a></li>-->
						</ul>
					</div>
					
                    
					<div class="itemnum" id="myvideo">
						<div class="hidenum">
							<ul class="clr" id='playlist_part'>
                            <?php 
                                      $videos = $wiki->getVideosByWikiId($referer,$hd);
                                	  $totalVideos = count($videos);
                                	  $j = 1;
                                	  foreach($videos as $video):
                                          //$config=$video->getConfig();
                                          //$asset_id=$config['asset_id'];
                                          $asset_id=$video->getPageId();
                                          //if($j==30&&$totalVideos>30):
                                          $k=intval($j/30);
                                          if($j%30==0&&$totalVideos>$k*30):
                                          
                           ?>
                        		<li class="videohs <?php echo ceil($j/10); ?>" id="<?php echo $j; ?>" value="<?php echo ceil($j/30); ?>"><a href="javascript:void(0);"  onclick="more(<?php echo $k*30;?>,<?php echo $k;?>);return false;">更多</a></li>
                           <?php              $j++;?>   
                        		<li class="videohs <?php echo ceil($j/10); ?>" id="<?php echo $j; ?>" value="<?php echo ceil($j/30); ?>"><a href="javascript:void(0);"  onclick="playWikiVideo('<?php echo $asset_id;?>','<?php echo $video->getReferer()?>');return false;"><?php echo $video->getMark()?$video->getMark():$j-1;?></a></li>   
                           <?php          else:?>
								<li class="videohs <?php echo ceil($j/10); ?>" id="<?php echo $j; ?>" value="<?php echo ceil($j/30); ?>"><a href="javascript:void(0);"  onclick="playWikiVideo('<?php echo $asset_id;?>','<?php echo $video->getReferer()?>');return false;"><?php echo $video->getMark()?$video->getMark():$j;?></a></li>
                           <?php          endif;?>
                           <?php
                                	  $j++;
                                	  endforeach;
                           ?>
							</ul>
                            
						</div>
					</div>
				</div>
                <?php else: $dianbo_scroll=1;?>
				<div class="notv">
					<h3>暂无点播资源，向您推荐以下内容</h3>
					<ul class="clr" id="dianbo">
                        <!--
						<li><a href="#"><img src="/pic/4.jpg" alt=""/><span><i><big>步步惊心</big></i></span></a></li>
                        -->
					</ul>
				</div>
                <?php endif;?>      
			</div>
			
			<div class="tvr" id="program_guide">
                <?php //include_partial('program_guide', array('count_programs' => $count_programs,'hot_programs'=>$hot_programs,'played_programs'=>$played_programs,'unplayed_programs'=>$unplayed_programs))?>
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
function initPage() {
    publicInit();
    playVideoReturn();
    nextpageorg();
    //$("#hotplays").scroll("big",10,8); 
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
    $.ajax({
            url: '<?php echo url_for('wiki/programGuide')?>',
            type: 'post',
            dataType: 'html',
            data: {'wiki_id':"<?php echo $wiki->getId()?>",'cardId': SmartCardNumber,'stbId': StbNumber},
            success: function(data){
                $("#program_guide").html(data);
                $("#hotplays").scroll("big",10,8);
            }
    }); 
    $("#playlist_part").find("a")[0].focus();
    Utility.ioctlWrite("JsAddKeyState","Y");  //重新定义默认键值
    //$("#huikan").scroll("big",10); 
    //$("#yugao").scroll("big",8); 
    $("#myrefer").find("a").eq(0).addClass('there');
}

function nextpageorg(){
    if(!arguments[0]){
    	$('.videohs').each(function(){
    		if($(this).attr('value')!=1){
    			$(this).addClass("disforli");
    		}
    	}); 
    }
	var count = $('.videohs').size();
	//if(count<=30){return false;}
	var pz	  = Math.ceil(count/30);
	var p     = 1;
    var page=arguments[0]?arguments[0]:1;
	$('.videohs').each(function(){
		var id = $(this).attr('id');
		var hang = this.className.split(' ');
		var ischange = (hang[1]%3 == 0);
		//this.onmouseover=function(){
		this.onkeydown=function(event){
				var p = Math.ceil(id/30);
				//alert(event.keyCode);
				//alert(ischange);
				if((event.keyCode == 40||event.keyCode == 29) && ischange){
					$('.videohs').each(function(){
						var hangson = this.className.split(' ');
						if(this.className!='videohs '+hangson[1]+' disforli'){
							value = $(this).attr('value');
							//alert(value);
						}
					});
					if(value>=pz){
						showTip('已至最后一页');
						return false;
					}
					var newvalue = value+1; 
					$('.videohs').each(function(i){
						if($(this).attr('value')==newvalue){
							$(this).removeClass("disforli");
						}else if($(this).attr('value') == value){
							$(this).addClass("disforli");
						}
					});
					
					if(p < pz){
						p = p+1;
					}
					if(p > 1){
						var focusid = 30*(p-1)+1;
                        page = page+1;
					}
					$('#'+focusid).find('a')[0].focus();
				}else if((event.keyCode == 38||event.keyCode == 28) && p>1 && hang[1] == (p*3-2)){//测试用p*1,其他p*3-2
					$('.videohs').each(function(){
						var hangson = this.className.split(' ');
						if(this.className!='videohs '+hangson[1]+' disforli'){
							value = $(this).attr('value');
						}
					});
					if(value<=1){
						showTip('已至第一页');
						return false;
					}
					var newvalue = value-1; 
					$('.videohs').each(function(){
						if($(this).attr('value')==newvalue){
							$(this).removeClass("disforli");
						}else if($(this).attr('value') == value){
							$(this).addClass("disforli");
						}
					});
					var focusid = 1;
					if(p <= pz && p >1){
						p = p-1;
						focusid = 30*p;
                        page = page-1;
					}
					$('#'+focusid).find('a')[0].focus();
				}else if((event.keyCode == 34||event.keyCode == 0x79) && pz>1){
					$('.videohs').each(function(){
						var hangson = this.className.split(' ');
						if(this.className!='videohs '+hangson[1]+' disforli'){
							value = $(this).attr('value');
							//alert(value);
						}
					});
					if(value>=pz){
						showTip('已至最后一页');
						return false;
					}
					var newvalue = value+1; 
					$('.videohs').each(function(i){
						if($(this).attr('value')==newvalue){
							$(this).removeClass("disforli");
						}else if($(this).attr('value') == value){
							$(this).addClass("disforli");
						}
					});
				    //下页
					if(page < pz){
						page = page+1;
					}
                    var focusid = 30*(page-1)+1;
                    //showTip(focusid);
					$('#'+focusid).find('a')[0].focus();
				}else if((event.keyCode == 33||event.keyCode == 0x78) && page>1){
					$('.videohs').each(function(){
						var hangson = this.className.split(' ');
						if(this.className!='videohs '+hangson[1]+' disforli'){
							value = $(this).attr('value');
						}
					});
					if(value<=1){
						showTip('已至第一页');
						return false;
					}
					var newvalue = value-1; 
					$('.videohs').each(function(){
						if($(this).attr('value')==newvalue){
							$(this).removeClass("disforli");
						}else if($(this).attr('value') == value){
							$(this).addClass("disforli");
						}
					});
				    //上页
					var focusid = 1;
					if(page >1){
						page = page-1;
						focusid = 30*page-29;
					}
                    //showTip(focusid);
					$('#'+focusid).find('a')[0].focus();
				}
			}
		//}
	})
	
}
//单击更多显示
function more(k,page){
	$('.videohs').each(function(){
		var hangson = this.className.split(' ');
		if(this.className!='videohs '+hangson[1]+' disforli'){
			value = $(this).attr('value');
		}
	});
	var newvalue = value+1; 
	$('.videohs').each(function(i){
		if($(this).attr('value')==newvalue){
			$(this).removeClass("disforli");
		}else if($(this).attr('value') == value){
			$(this).addClass("disforli");
		}
	});
    var focusid = k+1;
    //showTip(focusid);
	$('#'+focusid).find('a')[0].focus();
    nextpageorg(page+1);
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


function up_dn_mouseover(){
	$('#backview_up').addClass('addheight');
	//$('#backview_dn').removeClass('hotplays');
	$('#backview_dn').addClass('addheight');
}

function up_dn_mouseout(){
	$('#backview_up').removeClass('addheight');
	$('#backview_dn').removeClass('addheight');
	//$('#backview_dn').addClass('hotplash ');
}

function showVideos(num,wiki_id,refer,hd){
    if(num==1){
        $("#myvideo").addClass('itemnum2');
        $("#myvideo").removeClass('itemnum3');
    }else if(num==2){
        $("#myvideo").addClass('itemnum3');
        $("#myvideo").removeClass('itemnum2');
    }else{
        $("#myvideo").removeClass('itemnum2');
        $("#myvideo").removeClass('itemnum3');
    }

    $("#myrefer").find("a").each(function(){
        $(this).removeClass('there');
    });
    $("#myrefer").find("a").eq(num).addClass('there');

    $.ajax({
        url: '<?php echo url_for('wiki/videos')?>',
        type: 'post',
        data: {'num': num,'wiki_id':wiki_id,'model':'teleplay','refer':refer,'hd':hd},
        success: function(data){
            $("#playlist_part").html(data);    
            nextpageorg();
        }       
    });
}

function playWikiVideo(asset_id,sp_code) {
    Utility.ioctlWrite("JsAddKeyState","N");
    //location.href='/wiki/play/asset_id/'+asset_id+'/sp_code/'+sp_code+'/user_id/'+SmartCardNumber;
    $.ajax({
        url: '<?php echo url_for('wiki/play')?>',
        type: 'post',
        dataType: 'text',
        data: {asset_id: asset_id,sp_code: sp_code,user_id: SmartCardNumber,wiki_id:"<?php echo $wiki->getId()?>"},
        success: function(data){
            if(data!='')
                //alert(data);
                location.href=data;
            else
                showTip('该影片暂不能播放，请稍后再试');    
        }
    });
    return false;
}
function played(contentid){
    url='/cpg/show/contented/'+contentid+'/clientid/'+StbNumber;
    location.href=url;
}
function showdate(riqi){
    if(riqi!=''){
        $('#bochuyugao').html('播出预告 <font style="color:#ffff00;font-size:20px">播出日期:'+riqi+'</font>');
    }else{
        $('#bochuyugao').html('播出预告');
    }
}
function showdate1(riqi){
    if(riqi!=''){
        $('#pindaohuikan').html('频道回看 <font style="color:#ffff00;font-size:20px">播出日期:'+riqi+'</font>');
    }else{
        $('#pindaohuikan').html('频道回看');
    }
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
            //showPagePrior();
            //evt.preventDefault();
            break;
        case 34:    //"Pg Down键"
        case 0x79:  //下页
            //showPageNext();
            //evt.preventDefault();
            break;
        case 0x72:  //退出
        case 640:   //后退
            if('<?php echo $refer?>'=='history'){
                history.back();
            }else{
                location.href='/<?php echo $refer?>';
            }
            evt.preventDefault();
            break;
        case 0x30:  //0
            //getChannelsAndPost();
            //evt.preventDefault();
            break;
	}	
}
</script>