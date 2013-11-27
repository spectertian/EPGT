<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<?php use_helper('Week') ?>
<content class="clr">
        	<menu class="menu" data-role="menu">
            	<ul>
            		<li><a href="<?php echo url_for("/") ?>" class="index">首页</a></li>
                    <li class="there"><a href="<?php echo url_for("live/index") ?>" class="zb">直播</a></li>
                    <li><a href="#" class="jj">剧集</a></li>
                    <li><a href="#" class="lm">栏目</a></li>
                    <li><a href="#" class="sz">设置</a></li>	
                </ul>
            </menu>
            
            <div class="content " data-role="page">
            	<div class="ll">
                	<h2 class="tab"><a href="<?php echo url_for("channel/index") ?>" class="there">频道列表</a><a href="<?php echo url_for("live/index") ?>">正在播放</a></h2>
                    <div class="clr">
                    	<div class="tvchoice" id="transition">
                        	<ul>
                            <?php $i=0; foreach($channels as $channel):
                                  if($i==0):
                                      echo "<input type='hidden' id='channel_now' name='channel_now' value='".$channel->getCode()."'/>";
                                  endif;
                            ?>
                                <li><a href="#" <?php if($i==0):?>class="there"<?php endif;?> id="<?php echo $channel->getCode()?>" onclick="changechannel(this)"><img src="<?php echo thumb_url($channel->getLogo(),45,45)?>" alt="" /><?php echo $channel->getName()?></a></li>
                            <?php $i++; endforeach;?>
                            </ul>
                        </div>
                    	
                        <div class="daychoice">
                        	<ul class="weektab clr">
                                <input type='hidden' id='week_now' name='week_now' value='<?php echo date('Y-m-d');?>'/>
                            	<?php $day=date('Y-m-d'); foreach(aweek() as $key=>$value): ?>
                                <li><a href="#" <?php if($day==$value):?>class="there"<?php endif;?> id="<?php echo $value?>" onclick="changeweek(this)"><?php echo $key?></a></li>
                                <?php endforeach;?>
                            </ul>
                            
                            <div class="timechoice" id="standard">
                            	<ul>
                                    <!--节目内容
                            		<li><a href="#">00:09<span>法制进行时</span></a></li>
                                    -->
                            	</ul>      
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="lr">
                     <!--右边节目简介-->
                </div>
            </div>
        </content>
<script type="text/javascript">
$(document).ready(function() {
    program();  //加载节目数据  
    var code=$('#channel_now').attr('value');
    getProgram(code);  //加载右部分节目数据
    //loaded();       
})

function changechannel(obj){
    $(".tvchoice").find('a').removeClass("there");
    obj.className='there';
    $('#channel_now').attr('value',obj.id);
    program();
    var code=$('#channel_now').attr('value');
    getProgram(code);  //加载右部分节目数据    
}
function changeweek(obj){
    $(".weektab").find('a').removeClass("there");
    obj.className='there';  
    $('#week_now').attr('value',obj.id);
    program();
}
//加载节目数据
function program(){
	//var doc = $('.weektab clr li').find("a[class=there]");
	//var day = doc.attr('id');
    var code=$('#channel_now').attr('value');
    var day=$('#week_now').attr('value');
    //alert(day);
    //alert(code);

	$.ajax({
	    url: '/channel/program',
	    type: 'get',
	    dataType: 'json',
	    data: { 'code': code,'day': day },
	    success: function(data)
	    {
        	$('.timechoice ul').html('');
        	if(data==null)
        		$('.timechoice ul').append("<li><a href='#'><span>该频道暂无播放信息</span></a></li>");
        	else
        	{
	        	$(data).each(function(i){
    	            if(this.jialiang==1){
    	                $('.timechoice ul').append("<li><a href='/wiki/show/slug/"+this.wiki_slug+"' class='there'>"+this.time+"<span>"+this.name+"</span></a></li>");
    	            }else{
    	                $('.timechoice ul').append("<li><a href='/wiki/show/slug/"+this.wiki_slug+"'>"+this.time+"<span>"+this.name+"</span></a></li>");
    	            }
		        	//$('.timechoice ul').append("<li>"+this.name+"</li>");
	        	});
        	}
            scroll1.refresh();
            //scroll2.refresh();             
	    }
	});
}

//加载选中节目信息
function getProgram(channel_code){
    $.ajax({
        url: '<?php echo url_for('channel/showProgram')?>',
        type: 'get',
        dataType: 'html',
        data: {'channel_code': channel_code},
        beforeSend: function (XMLHttpRequest) {
            $('.lr').html("数据加载中...");
        },         
        success: function(html){
            $('.lr').html(html); 
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) { 
            $('.lr').html(textStatus);
        }

    });
}
</script>         