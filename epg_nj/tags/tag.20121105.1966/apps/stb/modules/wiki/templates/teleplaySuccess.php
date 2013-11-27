<div class="wapper" id="wapper">
<style>
.disforli{display:none};
</style>
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
                        <?php if($tags = $wiki->getTags()):$i = 0  ?>
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
				<?php if ($playList = $wiki->getPlayList()) :?>
				<div class="playlibrary">
					<div class="itemchoice">
						<ul class="clr">
							<li><a href="#" class="there"><img src="/img/dbk.png" alt=""/></a></li>
							<li><a href="#"><img src="/img/pptv.png" alt=""/></a></li>
							<li><a href="#"><img src="/img/m1905.png" alt=""/></a></li>
						</ul>
					</div>
					
					<div class="itemnum">
						<div class="hidenum">
							<ul class="clr" id='playlist_part'>
                            <?php 
                                  $k=0;
                                  foreach($playList as $playlist) :
                                      if($k>0) break; //目前暂只判断一个来源
                            ?>
                                <?php $videos = $playlist->getVideos()?>
                                
                                <?php 
                                	  $totalVideos = count($videos);
                                	  
                                	  	
                                	  $j = 1;
                                	  foreach($videos as $video):
                                ?>
                                
                                
									<li class="videohs <?php echo ceil($j/10); ?>" id="<?php echo $j; ?>" value="<?php echo ceil($j/30); ?>"><a href="#"  onclick="tclSave('<?php echo (string)$wiki->getId();?>','http://hditv.jsamtv.com/epg/show.do?app=vpg&hd=y&content=forsearch&movieassetid=10819891&inquiry=y&clientid=');"><?php echo $video->getMark()?></a></li>
                                <?php
                                	  $j++;
                                	  endforeach;
                                ?>
							<?php 
                                  $k++;
                                  endforeach;
                            ?>
							</ul>
						</div>
					</div>
				</div>
                <?php else: ?>
				<div class="notv">
					<h3>暂无点播资源，向您推荐以下内容！</h3>
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
	$('.videohs').each(function(){
		if($(this).attr('value')!=1){
			$(this).addClass("disforli");
		}
	});
    //nextlist();
    //nextpage();
    nextpageorg();
	publicInit();	
    playVideo();
    $("#hotLiveProgram").scroll("b",5); 
}

function nextpageorg(){
	var count = $('.videohs').size();
	//if(count<=30){return false;}
	var pz	  = Math.ceil(count/30);
	var p     = 1;
	
	$('.videohs').each(function(){
		var id = $(this).attr('id');
		var hang = this.className.split(' ');
		var ischange = (hang[1]%3 == 0);
		this.onmouseover=function(){
			onkeydown=function(event){
				var p = Math.ceil(id/30);
				//alert(event.keyCode);
				//alert(ischange);
				if(event.keyCode == 40 && ischange){
					$('.videohs').each(function(){
						var hangson = this.className.split(' ');
						if(this.className!='videohs '+hangson[1]+' disforli'){
							value = $(this).attr('value');
							//alert(value);
						}
					});
					if(value>=pz){
						alert('已至最后一页');
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
					}
					$('#'+focusid).find('a')[0].focus();
				}else if(event.keyCode == 38 && p>1 && hang[1] == (p*3-2)){//测试用p*1,其他p*3-2
					$('.videohs').each(function(){
						var hangson = this.className.split(' ');
						if(this.className!='videohs '+hangson[1]+' disforli'){
							value = $(this).attr('value');
						}
					});
					if(value<=1){
						alert('已至第一页');
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
					}
					$('#'+focusid).find('a')[0].focus();
				}
			}
		}
	})
	
	
	
	//onkeydown=function(event){
	//	if(event.keyCode == 110){
	//		
	//	}
	//}
}

function nextpage(){
	var count = $('.videohs').size();
	var pz	  = Math.ceil(count/7);
	var p     = 1;
	
	$('.videohs').each(function(){
		var id = $(this).attr('id');
		var ischange = (id%7 == 0);
		this.onmouseover=function(){
			onkeydown=function(event){
				var p = Math.ceil(id/7);
				//alert(event.keyCode);
				if((event.keyCode == 39 || event.keyCode == 40) && ischange){
					$('.videohs').each(function(){
						if(this.className!='videohs disforli'){
							value = $(this).attr('value');
						}
					});
					if(value>=pz){
						alert('已至最后一页');
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
						var focusid = 7*(p-1)+1;
					}
					$('#'+focusid).find('a')[0].focus();
				}else if((event.keyCode == 37 || event.keyCode == 38) && p>1 && id == 7*(p-1)+1){
					$('.videohs').each(function(){
						if(this.className!='videohs disforli'){
							value = $(this).attr('value');
						}
					});
					if(value<=1){
						alert('已至第一页');
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
						focusid = 7*p;
					}
					$('#'+focusid).find('a')[0].focus();
				}
			}
		}
	})
	
	
	
	//onkeydown=function(event){
	//	if(event.keyCode == 120){
	//		
	//	}
	//}
}

function nextlist(){
	onkeydown=function(event){
		//alert(event.keyCode);
		var count = $('.videohs').size();
		var pz	  = Math.ceil(count/7);
		if(event.keyCode == 120){
			$('.videohs').each(function(){
				if(this.className!='videohs disforli'){
					value = $(this).attr('value');
				}
			});
			if(value<=1){
				alert('已至第一页');
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
		}else if(event.keyCode == 121){
			$('.videohs').each(function(){
				if(this.className!='videohs disforli'){
					value = $(this).attr('value');
				}
			});
			if(value>=pz){
				alert('已至最后一页');
				return false;
			}
			var newvalue = value+1; 
			$('.videohs').each(function(){
				if($(this).attr('value')==newvalue){
					$(this).removeClass("disforli");
				}else if($(this).attr('value') == value){
					$(this).addClass("disforli");
				}
			});
		}
	}
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