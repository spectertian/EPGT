<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<div class="wapper" id="wapper">
	<div class="smartnav">
        <?php if($programTop):?>
        <?php $all = strtotime($programTop->getEndTime()->format("Y-m-d H:i:s")) - strtotime($programTop->getTime());?>
    	<?php $plan = time() - strtotime($programTop->getTime());?>
    	<?php $width = round($plan/$all,2) * 100?>
    	<?php 
    		//$currentPro = "<i>".$programTop->getChannelName().'：'.$programTop->getWikiTitle().'</i> <span class="time"><span class="timebg"><b class="timenow" '."style='"."width:".$width."%'></b></span>".$programTop->getStartTime()->format('H:i')."</span>";
    	?>
		<h2 id="tvplay"><i><?php echo $programTop->getChannelName();?>：<?php echo $programTop->getWikiTitle()?></i> <span class="time"><span class="timebg"><b class="timenow" style="width:<?php echo $width?>%"></b></span><?php echo $programTop->getStartTime()->format("H:i");?></span></h2>
        <?php else:?>
       
        <h2 id="tvplay"></h2>
        <?php endif;?>
        <div class="snav clr">
            <input type="hidden" name="type" id="f_type" value="<?php echo $type;?>" />
            <input type="hidden" name="page" id="f_page" value="<?php echo $page?>" />
			<h3><a href="#" id="program_detail">节目详情</a><a href="/channel/index" id="program_channel">节目表</a></h3>
			<ul id="mytype">
                <?php foreach ($types as $type):?>
				<li><a href="javascript:void(0);" onclick="showProgram('<?php echo $type;?>','1')"><?php echo $type;?></a></li>
                <?php endforeach;?>
			</ul>
		</div>
		
		<div class="snavlisth">
			<ul class="snavlist" id="showProgram"></ul>
		</div>
	</div>		
</div>
<script type="text/javascript">
    //initPage();  //一定要先初始化，在body onload事件不行，在这获取不到    
    function initPage() {
    	publicInit();
        showProgram('电视剧',1);
        ckeckPageEvt();	
        currentProgram(); //获取当前电视节目
        $("#a0")[0].focus();
        //getcurrentpro();
        playVideo();
    }
    
	
	function getmouseout(param){
		//var act = document.activeElement.id;当前获取焦点控件
		currentProgram();
		//alert($('#currentPro').html());
	}
    
    function showProgram(type,page){
        $("#mytype").find("a").each(function(){
            if($(this).html() == type)
                $(this).addClass("there");
            else 
                $(this).removeClass("there");
        });
        $.ajax({
            url: '<?php echo url_for('list/showProgram')?>',
            type: 'post',
            dataType: 'html',
            data: {type: type,page:page},
            success: function(data){
                $('#showProgram').html(data);
                $("#f_type").attr("value",type);
                $("#f_page").attr("value",page);
                ckeckPageEvt();
                currentProgram();
                $("#showProgram").scroll("em",4);
                $('#a0')[0].focus();
            }
        });
    }    
    function ckeckPageEvt() {
        $(".last").each(function(){
            $(this).keydown(function(evt){
                var evtcode = evt.which ? evt.which : evt.code;
                switch (evtcode) {
                    case jLim.VK_RIGHT: 
                      showPageNext();
                      evt.preventDefault();
                      break;
                } 
                return false;
            });
        });
        $(".first").each(function(){
            $(this).keydown(function(evt){
                var evtcode = evt.which ? evt.which : evt.code;
                switch (evtcode) {		
                    case jLim.VK_LEFT:
                      showPagePrior();
                      evt.preventDefault();
                      break;
                } 
                return false;
            });
        });
    }
    
    function showPagePrior() {   
        var page = parseInt($("#f_page").attr("value"));
        var type = $("#f_type").attr("value");
        if(page <= 1) {
            //showTip("已经是第一页了！");
        }else{
            page=page-1;
            showProgram(type,page); 
        }
        //$("#a0")[0].focus();
    }
    
    function showPageNext() {
        var page = parseInt($("#f_page").attr("value"));
        var type = $("#f_type").attr("value");
        page=page+1;
        showProgram(type,page);
        //$("#a0")[0].focus();
    }    

    function hiddenPage() {
        hidPlay();
        $("#wapper").style("visibility","hidden");
    } 
    function autohiddenPage() {
        //clearTimeout(id);
        id=setTimeout (hiddenPage,15000);
    }     
    function showHotLivePage() {
        if($("#wapper").style("visibility") == "hidden"){
            $("#wapper").style("visibility","visible");
            $("#tvplay").style("visibility","visible");
        }else{
            $("#wapper").style("visibility","hidden");
            $("#tvplay").style("visibility","hidden");
        }
    }        
    function showPlay(channel,name,start,end,width){
        var myDate = new Date();
        var mytime=myDate.format("h:m");
    	$("#tvplay").html("<i>"+channel+"："+name+"</i><span class='time'><span class='timebg'><b style='width: "+width.toString()+"%;' class='timenow'></b></span>"+mytime+"</span>");
        $("#tvplay").style("visibility","visible");
    }
    function showWiki(name){
        var myDate = new Date();
        var mytime=myDate.format("h:m");
    	$("#tvplay").html("<i>"+name+"</i><span class='time'><span class='timebg'><b style='width: 0%;' class='timenow'></b></span>"+mytime+"</span>");
        $("#tvplay").style("visibility","visible");
    }
    function currentProgram(){
        //获取当前节目名称
        var myDate = new Date();
        var mytime=myDate.format("h:m");
        var Location=mp.getServiceLocation(0);
        var ser = new Service(Location);
        var programs=ser.getPrograms(0);
        if(programs.length>0){
            startTime=programs[0].startTime;
            endTime=programs[0].endTime;
            all=endTime.getTime()-startTime.getTime();
            plan=myDate.getTime()-startTime.getTime();
            var width=Math.round((plan/all)*100);
            //alert(width);
            keyword=programs[0].name; //当前节目名称
            keyword=keyword.replace(/[^\u4e00-\u9fa5]/gi,""); //只获取汉字，去掉诸如(5)一类的
            $("#program_detail").attr('href','/wiki/show/slug/'+keyword);
            $("#program_channel").attr('href','/channel/index/channel/'+ser.name);
            $("#tvplay").html("<i>"+ser.name+"："+keyword+"</i><span class='time'><span class='timebg'><b style='width: "+width.toString()+"%;' class='timenow'></b></span>"+mytime+"</span>");
        }
    }    
    function hidPlay() {
        $("#tvplay").style("visibility","hidden");
    }

    function thefocus(){
    	$('.program_list_gb').find('a').focus();
    }
</script>
